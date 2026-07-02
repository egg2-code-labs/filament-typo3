<?php

namespace Egg2CodeLabs\FilamentTypo3\Tables\Actions;

use Egg2CodeLabs\FilamentTypo3\Tables\Actions\ShowHideBulkActionEnum;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Bulk action for showing or hiding multiple records.
 *
 * Provides functionality to bulk update the hidden status of records,
 * similar to TYPO3 CMS bulk actions.
 */
class ShowHideBulkAction extends BulkAction
{
    /**
     * @var ShowHideBulkActionEnum Show or hide the records
     */
    protected ShowHideBulkActionEnum $showOrHide;

    public static function make(null|string $name = null): static
    {
        return parent::make($name);
    }

    /**
     * Convenience function to make this a show action.
     *
     * @return $this
     */
    public function show(): static
    {
        return $this->showOrHide(ShowHideBulkActionEnum::SHOW);
    }

    /**
     * Set whether to show or hide records.
     *
     * @param ShowHideBulkActionEnum|bool|int|string $showOrHide The action to perform
     * @return $this
     */
    public function showOrHide(ShowHideBulkActionEnum|bool|int|string $showOrHide): static
    {
        if ($showOrHide === 'show') {
            $showOrHide = ShowHideBulkActionEnum::SHOW;
        }

        if ($showOrHide === 'hide') {
            $showOrHide = ShowHideBulkActionEnum::HIDE;
        }

        if (is_int($showOrHide)) {
            $showOrHide = ShowHideBulkActionEnum::from($showOrHide);
        }

        if (is_bool($showOrHide)) {
            $showOrHide = ShowHideBulkActionEnum::from((int) $showOrHide);
        }

        $this->showOrHide = $showOrHide;

        return $this;
    }

    /**
     * Convenience function to make this a hide action.
     *
     * @return $this
     */
    public function hide(): static
    {
        return $this->showOrHide(ShowHideBulkActionEnum::HIDE);
    }

    /**
     * Set up the bulk action with default configuration.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->requiresConfirmation()
            ->action(function (Collection $records): void {
                /**
                 * Update each record individually to ensure proper handling
                 * of the hidden status.
                 */
                $records->each(function (Model $record): void {
                    $record->hidden = $this->showOrHide->value;
                    $record->save();
                });
            });
    }
}
