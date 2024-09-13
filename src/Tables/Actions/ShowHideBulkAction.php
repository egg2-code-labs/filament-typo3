<?php

namespace Egg2CodeLabs\FilamentTypo3\Tables\Actions;

use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ShowHideBulkAction extends BulkAction
{
    /**
     * @var ShowHideBulkActionEnum Show or hide the records
     */
    protected ShowHideBulkActionEnum $showOrHide;


    /**
     * @param string|null $name
     *
     * @return static
     */
    public static function make(null|string $name = null): static
    {
        return parent::make($name);
    }

    /**
     * Convenience function to make this a show action
     *
     * @return $this
     */
    public function show(): static
    {
        return $this->showOrHide(ShowHideBulkActionEnum::SHOW);
    }

    /**
     * @param ShowHideBulkActionEnum|bool|int|string $showOrHide
     *
     * @return ShowHideBulkAction
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
            $showOrHide = ShowHideBulkActionEnum::from((int)$showOrHide);
        }

        $this->showOrHide = $showOrHide;

        return $this;
    }

    /**
     * Convenience function to make this a hide action
     *
     * @return $this
     */
    public function hide(): static
    {
        return $this->showOrHide(ShowHideBulkActionEnum::HIDE);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->requiresConfirmation()
            ->action(function (Collection $records) {
                /**
                 * Initially I wanted to update all elements in one single update query, but for some reason that does
                 * not work when enabling records again. So here we are back to a loop.
                 */
                $records->each(function (Model $record) {
                    $record->hidden = $this->showOrHide->value;
                    $record->save();
                });
            });
    }
}
