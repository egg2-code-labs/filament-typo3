@props([
    'record',
    'column',
])

<?php
/** @var \Egg2CodeLabs\FilamentTypo3\Tables\Columns\GazeColumn $column */
/** @var \Illuminate\Database\Eloquent\Model $record */

$isOpened = $column->getIsOpened($record);
$viewerCount = $column->getViewerCount($record);
?>

<div 
    {{ $attributes->merge($column->getExtraAttributes($record))->className(
        Filament\Tables\Columns\Column::getAlpineClickHandler($column->getAction()) ?? ''
    ) }}
    wire:key="{{ $this->rowLoop->index . '-' . $column->getId() }}"
>
    <div class="flex items-center gap-2">
        @if($isOpened)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            @if($viewerCount > 0)
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $viewerCount }}
                </span>
            @endif
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
        @endif
    </div>
</div>
