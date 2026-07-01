<?php

namespace Egg2CodeLabs\FilamentTypo3\Tables\Columns;

use Egg2CodeLabs\FilamentTypo3\Gaze;
use Filament\Tables\Columns\Column;

/**
 * A table column that indicates whether a record is currently being viewed by someone.
 * This column integrates with filament-gaze to show viewing status.
 */
class GazeColumn extends Column
{
    /**
     * Whether to exclude the current user from the check.
     */
    protected bool $excludeCurrentUser = true;

    /**
     * Create a new GazeColumn instance.
     *
     * @param string|null $name The column name (not used, as this is a computed column)
     * @return static
     */
    public static function make(string|null $name = null): static
    {
        return parent::make($name ?? 'gaze_status');
    }

    /**
     * Set whether to exclude the current user from the check.
     *
     * @param bool $exclude Whether to exclude the current user
     * @return $this
     */
    public function excludeCurrentUser(bool $exclude = true): static
    {
        $this->excludeCurrentUser = $exclude;

        return $this;
    }

    /**
     * Set up the column.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Viewing')
            ->state(
                fn ($record) => $this->getIsOpened($record)
            )
            ->view('filament-typo3::tables.columns.gaze-column')
            ->extraAttributes(
                fn ($record) => [
                    'data-gaze-viewers' => $this->getViewerCount($record),
                    'data-gaze-is-opened' => $this->getIsOpened($record) ? 'true' : 'false',
                ]
            )
            ->toggleable()
            ->sortable(false)
            ->searchable(false);
    }

    /**
     * Get whether the record is currently being viewed.
     *
     * @param mixed $record The record
     * @return bool True if the record is being viewed
     */
    public function getIsOpened($record): bool
    {
        return Gaze::isOpened($record, $this->excludeCurrentUser);
    }

    /**
     * Get the number of viewers for the record.
     *
     * @param mixed $record The record
     * @return int The number of viewers
     */
    public function getViewerCount($record): int
    {
        return Gaze::getViewerCount($record, $this->excludeCurrentUser);
    }

    /**
     * Get the state of the column.
     *
     * @param mixed $record The record
     * @return bool The state (whether the record is being viewed)
     */
    public function getState($record): bool
    {
        return $this->getIsOpened($record);
    }
}
