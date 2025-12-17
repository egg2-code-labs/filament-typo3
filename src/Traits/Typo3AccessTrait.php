<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\Typo3AccessTabFieldsEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait Typo3AccessTrait
{
    public function isDisabledSchedule(): bool
    {
        return !$this->isEnabledSchedule();
    }

    public function isEnabledSchedule(): bool
    {
        if (
            !$this->hasSchemaColumnCached(Typo3AccessTabFieldsEnum::STARTTIME->value)
            || !$this->hasSchemaColumnCached(Typo3AccessTabFieldsEnum::ENDTIME->value)
        ) {
            return false;
        }

        if (!empty($this->starttime) && !empty($this->endtime)) {
            return now()->betweenIncluded(
                date1: $this->starttime,
                date2: $this->endtime,
            );
        }

        if (!empty($this->starttime) && now()->isBefore($this->starttime)) {
            return false;
        }

        return !(!empty($this->endtime) && now()->isAfter($this->endtime));
    }

    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    public function isEnabled(): bool
    {
        if ($this->isHidden()) {
            return false;
        }

        return $this->isEnabledSchedule();
    }

    public function isHidden(string|null $hiddenColumn = null): bool
    {
        if (empty($hiddenColumn)) {
            $hiddenColumn = Typo3AccessTabFieldsEnum::HIDDEN->value;
        }

        if (!$this->hasSchemaColumnCached($hiddenColumn)) {
            return false;
        }

        return (bool)$this->getAttribute($hiddenColumn);
    }

    private function hasSchemaColumn(string $column): bool
    {
        return Schema::hasColumn(table: $this->getTable(), column: $column);
    }

    private function hasSchemaColumnCached(string $column): bool
    {
        $function = __FUNCTION__;
        $class = $this::class;

        return Cache::rememberForever(
            key: "{$function}-{$class}-{$column}",
            callback: fn (): bool => $this->hasSchemaColumn($column)
        );
    }
}
