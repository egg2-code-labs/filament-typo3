<?php

namespace Egg2CodeLabs\FilamentTypo3\Tables\Columns;

use Filament\Tables\Columns\ToggleColumn;

/**
 * This column operates on the `hidden` column, but inverts the logic to be more intuitive in the tables.
 * Basically the toggle shows active when the `hidden` column is false and vice versa. The idea being that it is
 * more intuitive to disable something by disabling a switch rather than enabling something by disabling a switch.
 */
class ActiveToggleColumn extends ToggleColumn
{
    /**
     * @param string $name
     *
     * @return static
     */
    public static function make(string|null $name = 'hidden'): static
    {
        return parent::make($name);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Active')
            ->getStateUsing(
                callback: fn ($record): bool => !($record?->{$this->getName()} === true)
            )
            ->updateStateUsing(
                callback: function ($record, $state) {
                    $record->update([
                        $this->getName() => !($state === true) // Invert the toggle column value for better UX
                    ]);
                }
            )
            ->sortable();
    }
}
