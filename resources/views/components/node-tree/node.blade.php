<div class="node">
    {{--    TODO: re-render the page tree when a page changes--}}
    {{--    TODO: build two main modules into the main navigation: Page & List, functioning like the TYPO3 modules--}}
    <div class="node-line flex gap-2">
        @if($this->node->hasChildren())
            <button wire:click="toggle">
                @if ($this->isOpen)
                    <x-heroicon-s-chevron-down class="size-4" />
                @else
                    <x-heroicon-s-chevron-up class="size-4" />
                @endif
            </button>
        @endif
        <div @class([
            'title',
            'ml-6' => !$this->node->hasChildren(),
            'flex gap-2 items-center'
            ])
        >
            <div class="title-icon size-4">
                <x-filament-typo3::node-tree.icon-proxy :doctype="$this->node->doctype" />
            </div>
            <div class="title-text">{{ $this->node->title }}</div>
            {{--            @dump(session()->all())--}}
        </div>
    </div>

    @if ($this->isOpen || $this->isRootNode)
        <div class="children ml-4">
            @foreach($this->node->children as $child)
                <livewire:filament-typo3::node-tree-node :node="$child" />
            @endforeach
        </div>
    @endif
</div>

