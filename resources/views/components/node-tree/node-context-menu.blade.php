@php use Egg2CodeLabs\FilamentTypo3\Models\ContextAction; @endphp
@php use Filament\Support\Enums\IconPosition; @endphp
@props([
    'actions' => []
])

<x-filament::dropdown placement="bottom-start">
    <x-slot name="trigger">
        {{ $slot }}
    </x-slot>

    <x-filament::dropdown.list class="divide-y divide-gray-100 dark:divide-white/5">
        @php /** @var ContextAction[] $actions */ @endphp
        @foreach($actions as $action)
            @if ($action->isVisible() === true)
                <div class="dropdown-list-item p-3">
                    @php
                        echo $action
                            ->link()
                            ->iconPosition(IconPosition::Before)
                            ->render();
                    @endphp
                </div>
            @endif
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
