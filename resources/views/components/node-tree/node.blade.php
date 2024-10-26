<div class="node">
    {{-- TODO: re-render the page tree when a page changes --}}
    {{-- TODO: build two main modules into the main navigation: Page & List, functioning like the TYPO3 modules --}}
    {{-- TODO: build click action on node title & click context menu on node icon --}}
    <div class="node-line flex gap-2">
        @if($this->node->children_count > 0)
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
            'ml-6' => !$this->node->children_count > 0,
            'flex gap-2 items-center'
            ])
        >

            <x-filament-typo3::node-tree.node-context-menu :actions="$this->nodeActions">
                <button class="title-icon size-4 cursor-pointer">
                    {{-- TODO: add disabled indicator icon --}}
                    {{-- TODO: for more flexible use icons need to be extendable --}}
                    <x-filament-typo3::node-tree.icon-proxy
                        :doctype="$this->node->doctype"
                        :is-hidden="$this->node->hidden"
                    />
                </button>
            </x-filament-typo3::node-tree.node-context-menu>

            <button class="title-text cursor-pointer" wire:click.debounce="onLabelClick">
                {{ $this->node->title }}
            </button>
        </div>
    </div>

    @if ($this->isOpen || $this->isRootNode)
        <div class="children ml-4">
            @foreach($this->children as $child)
                <livewire:filament-typo3::node-tree-node :node="$child" />
            @endforeach
        </div>
    @endif
</div>

