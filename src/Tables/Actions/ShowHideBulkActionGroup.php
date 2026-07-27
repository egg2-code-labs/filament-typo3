<?php

declare(strict_types=1);

namespace Egg2CodeLabs\FilamentTypo3\Tables\Actions;

use Filament\Actions\BulkActionGroup;

final class ShowHideBulkActionGroup extends BulkActionGroup
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Show / Hide');
        $this->actions([
            ShowHideBulkAction::make('Hide')->hide(),
            ShowHideBulkAction::make('Show')->show()
        ]);
    }
    public static function make(array $actions = []): static
    {
        return parent::make($actions);
    }
}
