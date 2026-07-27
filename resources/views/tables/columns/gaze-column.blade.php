@props ([
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
    {{ $attributes->merge($column->getExtraAttributes($record)) }}
    wire:key="{{ $column->getRowLoop()->index . '-' . $column->getId() }}"
>
    <div class="flex items-center gap-2">
        <x-heroicon-o-user
            @class([
              'size-4',
              $isOpened ? 'text-red-500' : 'text-green-500',
            ])
        />
        <span> {{ $viewerCount }} </span>
    </div>
</div>
