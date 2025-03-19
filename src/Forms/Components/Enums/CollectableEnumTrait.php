<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums;

use Illuminate\Support\Collection;

trait CollectableEnumTrait
{
    public static function collect(): Collection
    {
        return collect(static::cases());
    }

    /**
     * Convert to filament Select input field options
     *
     * @return Collection
     */
    public static function toOptions(): Collection
    {
        return static::collect()
            ->mapWithKeys(
                fn (\BackedEnum $value): array => [
                    $value->name => $value->value
                ]
            );
    }
}
