<div class="page">
    {{--    TODO: do not use typo3 query scope--}}
    {{--    TODO: implement icons, somewhat like typo3 --}}
    {{--    TODO: re-render the page tree when a page changes--}}
    {{--    TODO: store state of the page tree into the user session/profile so it is the same after page reload--}}
    {{--    TODO: get tailwind running for this package--}}
    <div class="page-line flex gap-2">
        @if($this->page->hasChildren())
            <button wire:click="toggle">
                @if ($this->isOpen)
                    <x-heroicon-s-chevron-down class="size-4" />
                @else
                    <x-heroicon-s-chevron-up class="size-4 " />
                @endif
            </button>
        @endif
        <div @class([
            'title',
            'ml-6' => !$this->page->hasChildren()
            ])
        >{{ $this->page->title }}</div>
    </div>

    @if ($this->isOpen)
        <div class="children ml-4">
            @foreach($this->page->children as $child)
                <livewire:filament-typo3::page-tree-page :page="$child" />
            @endforeach
        </div>
    @endif
</div>

