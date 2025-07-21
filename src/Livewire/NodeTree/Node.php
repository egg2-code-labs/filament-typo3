<?php

namespace Egg2CodeLabs\FilamentTypo3\Livewire\NodeTree;

use Egg2CodeLabs\FilamentTypo3\Interfaces\HasExpandablesInterface;
use Egg2CodeLabs\FilamentTypo3\Models\ContextAction;
use Filament\Resources\Resource;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Throwable;

class Node extends Component
{
    #[Locked]
    public string|int $nodeId;

    /**
     * @var string Classname of the nodes
     */
    #[Locked]
    public string $nodeModel;

    /**
     * @var bool Whether this page is a root page or not
     */
    #[Locked]
    public bool $isRootNode = false;

    #[Locked]
    public string $resource;

    /**
     * @var string[]
     */
    private static $defaultSelect = ['id', 'doctype', 'title', 'hidden'];

    /**
     * @param HasExpandablesInterface $node
     * @param bool $isRootNode
     *
     * @return void
     */
    public function mount(HasExpandablesInterface $node, bool $isRootNode = false): void
    {
        $this->nodeId = $node->getKey();
        $this->nodeModel = $node::class;
        $this->resource = $node::getFilamentResource();

        $this->isRootNode = $isRootNode;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('filament-typo3::components.node-tree.node');
    }

    /**
     * @return HasExpandablesInterface
     */
    #[Computed]
    public function node(): HasExpandablesInterface
    {
        return $this->nodeModel::query()
            ->select(static::$defaultSelect)
            ->withoutGlobalScopes()
            ->where('id', $this->nodeId)
            ->orderBy('sorting')
            ->withCount('children')
            ->first();
    }

    #[Computed]
    public function children(): Collection
    {
        return $this->nodeModel::query()
            ->select(static::$defaultSelect)
            ->withoutGlobalScopes()
            ->where('pid', $this->nodeId)
            ->orderBy('sorting')
            ->get();
    }

    #[Computed]
    public function isOpen(): bool
    {
        return auth()->user()
            ->expandables()
            ->where('expandable_type', $this->node()::class)
            ->where('expandable_id', $this->node()->getKey())
            ->exists();
    }

    /**
     * @return array Array of actions
     */
    #[Computed]
    public function nodeActions(): array
    {
        /** @var Resource $resource */
        $resource = $this->resource;
        $record = $this->node;

        return [
            ContextAction::make('Edit')
                ->label(__('Edit'))
                ->icon('heroicon-o-pencil')
                ->color('white')
                ->url(fn () => $resource::getUrl('edit', ['record' => $this->node])),
            ContextAction::make('Disable')
                ->label(fn () => $record->hidden === false ? __('Disable') : __('Enable'))
                ->icon(fn () => $record->hidden === false ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color('white')
                ->action("toggleRecordHidden")
        ];
    }

    /**
     * @throws Throwable
     */
    public function toggleRecordHidden(): void
    {
        /** @var Model $record */
        $record = $this->node;

        if ($record->hasAttribute('hidden')) {
            $record->updateOrFail([
                'hidden' => !$record->hidden
            ]);
            // TODO: depending on the view, the whole UI might need to refresh
        }
    }

    /**
     * @return void
     */
    public function toggle(): void
    {
        $isOpen = !$this->isOpen();

        $user = auth()->user();

        /**
         * Delete DB entry when item is closed
         */
        if ($isOpen !== true) {
            $user->expandables()
                ->where('expandable_type', $this->node()::class)
                ->where('expandable_id', $this->node()->getKey())
                ->delete();
        }

        /**
         * Create DB entry when item is opened
         */
        if ($isOpen === true) {
            $attributesAndValues = [
                'expandable_type' => $this->node()::class,
                'expandable_id' => $this->node()->getKey(),
                'user_id' => $user->id
            ];
            $user->expandables()
                ->updateOrCreate(
                    attributes: $attributesAndValues,
                    values: $attributesAndValues
                );
        }
    }

    /**
     * @return array
     */
    private function getEventData(): array
    {
        return [
            'nodeId' => $this->nodeId,
            'nodeModel' => $this->nodeModel,
            'isRootNode' => $this->isRootNode,
        ];
    }

    /**
     * @return void
     */
    #[Renderless]
    public function onLabelClick(): void
    {
        $this->dispatch('tree-node-clicked', data: $this->getEventData());
    }

    /**
     * @return void
     */
    #[Renderless]
    public function onIconClick(): void
    {
        $this->dispatch("tree-node-icon-clicked", data: $this->getEventData());
    }
}
