<?php

namespace Egg2CodeLabs\FilamentTypo3\Tables\Actions;

use Exception;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;

/**
 * The default actions in this group can be dynamically overwritten by providing actions that use the same name. Actions
 * added to the stack later override actions that have been added earlier, including default actions.
 */
class DefaultActionGroup extends ActionGroup
{
    /**
     * @var array<StaticAction | ActionGroup>
     */
    protected array $actions;

    /**
     * @var array<string, StaticAction>
     */
    protected array $flatActions;

    /**
     * @param array<StaticAction | ActionGroup> $actions
     * @throws Exception
     */
    public function actions(array $actions): static
    {
        $this->actions = [];
        $this->flatActions = [];

        $this->addActions($actions);

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->addActions([
            EditAction::make(),
            ViewAction::make(),
            DeleteAction::make()
                ->requiresConfirmation(),
            RestoreAction::make()
                ->requiresConfirmation(),
        ]);
    }

    /**
     * @param array<StaticAction | ActionGroup> $actions
     * @return $this
     * @throws Exception
     */
    public function addActions(array $actions): static
    {
        foreach ($actions as $action) {
            $action->group($this);

            if ($action instanceof ActionGroup) {
                $action->dropdownPlacement('right-top');

                $this->flatActions = [
                    ...$this->flatActions,
                    ...$action->getFlatActions(),
                ];
            } else {
                $this->flatActions[$action->getName()] = $action;
            }

            $this->actions[$action->getName()] = $action;
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public static function make(array $actions = []): static
    {
        $static = parent::make([]);

        $static->addActions($actions);

        return $static;
    }

}
