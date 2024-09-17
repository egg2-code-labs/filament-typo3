<div class="page">
    {{--    TODO: store state of the page tree into the user session/profile so it is the same after page reload--}}
    {{--    TODO: re-render the page tree when a page changes--}}
    {{--    TODO: build two main modules into the main navigation: Page & List, functioning like the TYPO3 modules--}}
    <div class="page-line flex gap-2">
        @if($this->page->hasChildren())
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
            'ml-6' => !$this->page->hasChildren(),
            'flex gap-2 items-center'
            ])
        >
            <div class="title-icon size-4">
                <x-filament-typo3::page-tree.icon-proxy :doctype="$this->page->doctype" />
            </div>
            <div class="title-text">{{ $this->page->title }}</div>
        </div>
    </div>

    @if ($this->isOpen)
        <div class="children ml-4">
            @foreach($this->page->children as $child)
                <livewire:filament-typo3::page-tree-page :page="$child" />
            @endforeach
        </div>
    @endif
</div>

