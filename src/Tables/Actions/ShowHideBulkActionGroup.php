<?php

namespace Egg2CodeLabs\FilamentTypo3\Tables\Actions;

use Filament\Tables\Actions\BulkActionGroup;

class ShowHideBulkActionGroup extends BulkActionGroup
{
    /**
     * @param array $actions
     *
     * @return static
     */
    public static function make(array $actions = []): static
    {
        return parent::make($actions);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Show / Hide');
        $this->actions([
            ShowHideBulkAction::make('Hide')->hide(),
            ShowHideBulkAction::make('Show')->show()
        ]);
    }
}
