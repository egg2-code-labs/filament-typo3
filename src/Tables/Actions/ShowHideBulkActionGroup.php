<?php

namespace Egg2CodeLabs\FilamentTypo3\Tables\Actions;

use Filament\Actions\BulkActionGroup;

/**
 * Bulk action group for show/hide actions.
 *
 * Provides a dropdown with show and hide bulk actions for table records.
 */
class ShowHideBulkActionGroup extends BulkActionGroup
{
    public static function make(array $actions = []): static
    {
        return parent::make($actions);
    }

    /**
     * Set up the action group with default show/hide actions.
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
