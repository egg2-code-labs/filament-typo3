<?php

namespace Egg2CodeLabs\FilamentTypo3\Tables\Actions;

use Exception;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\StaticAction;
use Filament\Actions\ViewAction;

/**
 * The default actions in this group can be dynamically overwritten by providing actions
 * that use the same name. Actions added to the stack later override actions that have
 * been added earlier, including default actions.
 */
class DefaultActionGroup extends ActionGroup
{
    /**
     * @var array<StaticAction|ActionGroup>
     */
    protected array $actions;

    /**
     * @var array<string, StaticAction>
     */
    protected array $flatActions;

    /**
     * Set the actions for the group.
     *
     * @param array<StaticAction|ActionGroup> $actions The actions to add
     * @return $this
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
     * Set up the action group with default actions.
     *
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
     * Add actions to the group.
     *
     * @param array<StaticAction|ActionGroup> $actions The actions to add
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
     * Create a new instance of the action group.
     *
     * @param array $actions The initial actions to add
     * @return static The new instance
     * @throws Exception
     */
    public static function make(array $actions = []): static
    {
        $static = parent::make([]);

        $static->addActions($actions);

        return $static;
    }
}
