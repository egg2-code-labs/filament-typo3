<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

use Egg2CodeLabs\FilamentTypo3\Enums\Typo3AccessTabFieldsEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait Typo3AccessTrait
{
    /**
     * Check if the record is disabled in schedule (has time constraints that prevent it from being shown).
     */
    public function isDisabledSchedule(): bool
    {
        return !$this->isEnabledSchedule();
    }

    /**
     * Check if the record is enabled in schedule (has no time constraints or current time is within range).
     */
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

    /**
     * Check if the record is disabled (hidden or not in schedule).
     */
    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    /**
     * Check if the record is enabled (not hidden and in schedule).
     */
    public function isEnabled(): bool
    {
        if ($this->isHidden()) {
            return false;
        }

        return $this->isEnabledSchedule();
    }

    /**
     * Check if the record is hidden.
     *
     * @param string|null $hiddenColumn The column name to check, defaults to 'hidden'
     */
    public function isHidden(string|null $hiddenColumn = null): bool
    {
        if (empty($hiddenColumn)) {
            $hiddenColumn = Typo3AccessTabFieldsEnum::HIDDEN->value;
        }

        if (!$this->hasSchemaColumnCached($hiddenColumn)) {
            return false;
        }

        return (bool) $this->getAttribute($hiddenColumn);
    }

    /**
     * Check if a column exists in the database schema.
     *
     * @param string $column The column name to check
     */
    private function hasSchemaColumn(string $column): bool
    {
        return Schema::hasColumn(table: $this->getTable(), column: $column);
    }

    /**
     * Check if a column exists in the database schema with caching.
     *
     * @param string $column The column name to check
     */
    private function hasSchemaColumnCached(string $column): bool
    {
        return Cache::remember(
            key: "filament-typo3:{$this->getTable()}:{$column}:schema",
            ttl: now()->addDay(),
            callback: fn (): bool => $this->hasSchemaColumn($column)
        );
    }
}
