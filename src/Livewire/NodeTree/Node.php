<?php

namespace Egg2CodeLabs\FilamentTypo3\Livewire\NodeTree;

use Egg2CodeLabs\FilamentTypo3\Interfaces\HasExpandablesInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Component;

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
            'nodeModel' => $this->nodeModel
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
        $this->dispatch('tree-node-icon-clicked', data: $this->getEventData());
    }
}
