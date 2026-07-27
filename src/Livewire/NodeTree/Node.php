<?php

namespace Egg2CodeLabs\FilamentTypo3\Livewire\NodeTree;

use Egg2CodeLabs\FilamentTypo3\Actions\ContextAction;
use Egg2CodeLabs\FilamentTypo3\Interfaces\HasExpandablesInterface;
use Egg2CodeLabs\FilamentTypo3\Models\ExpandableState;
use Filament\Resources\Resource;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Throwable;

/**
 * Livewire component for rendering individual nodes in the node tree.
 *
 * Handles node rendering, child loading, and context menu actions for the
 * TYPO3-style page tree functionality.
 */
class Node extends Component
{
    /**
     * @var string|int The node identifier
     */
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
     * @var string[] Default columns to select for node queries
     */
    private static array $defaultSelect = ['id', 'doctype', 'title', 'hidden'];

    /**
     * Mount the component with the given node.
     *
     * @param HasExpandablesInterface $node The node to render
     * @param bool $isRootNode Whether this is a root node
     */
    public function mount(HasExpandablesInterface $node, bool $isRootNode = false): void
    {
        $this->nodeId = $node->getKey();
        $this->nodeModel = $node::class;
        $this->resource = $node::getFilamentResource();

        $this->isRootNode = $isRootNode;
    }

    /**
     * Render the node view.
     *
     * @return View The rendered view
     */
    public function render(): View
    {
        return view('filament-typo3::components.node-tree.node');
    }

    /**
     * Get the current node from the database.
     *
     * @return HasExpandablesInterface The node model instance
     */
    #[Computed]
    public function node(): HasExpandablesInterface
    {
        return $this->nodeModel::query()
            ->select(self::$defaultSelect)
            ->withoutGlobalScopes()
            ->where('id', $this->nodeId)
            ->orderBy('sorting')
            ->withCount('children')
            ->first();
    }

    /**
     * Get the children of the current node.
     *
     * @return Collection<HasExpandablesInterface> The child nodes
     */
    #[Computed]
    public function children(): Collection
    {
        return $this->nodeModel::query()
            ->select(self::$defaultSelect)
            ->withoutGlobalScopes()
            ->where('pid', $this->nodeId)
            ->orderBy('sorting')
            ->get();
    }

    /**
     * Check if the current node is expanded in the UI.
     *
     * @return bool True if the node is expanded, false otherwise
     */
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
     * Get the available actions for the current node.
     *
     * @return array<ContextAction> Array of context menu actions
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
                ->label(fn (): string|array|null => $record->hidden === false ? __('Disable') : __('Enable'))
                ->icon(fn (): string => $record->hidden === false ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color('white')
                ->action("toggleRecordHidden")
        ];
    }

    /**
     * Toggle the hidden status of the current record.
     *
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
     * Toggle the expanded state of the current node.
     */
    public function toggle(): void
    {
        $isOpen = !$this->isOpen();

        $user = auth()->user();

        /**
         * Delete DB entry when item is closed
         */
        if (!$isOpen) {
            $user->expandables()
                ->where('expandable_type', $this->node()::class)
                ->where('expandable_id', $this->node()->getKey())
                ->delete();
        }

        /**
         * Create DB entry when item is opened
         */
        if ($isOpen) {
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
     * Get the event data for node events.
     *
     * @return array<string, mixed> The event data
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
     * Handle label click event.
     */
    #[Renderless]
    public function onLabelClick(): void
    {
        $this->dispatch('tree-node-clicked', data: $this->getEventData());
    }

    /**
     * Handle icon click event.
     */
    #[Renderless]
    public function onIconClick(): void
    {
        $this->dispatch("tree-node-icon-clicked", data: $this->getEventData());
    }
}
