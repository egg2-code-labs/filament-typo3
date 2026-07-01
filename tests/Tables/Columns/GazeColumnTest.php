<?php

use Egg2CodeLabs\FilamentTypo3\Tables\Columns\GazeColumn;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::clear();
});

afterEach(function () {
    Cache::clear();
});

it('can be created', function () {
    $column = GazeColumn::make();
    
    expect($column)->toBeInstanceOf(GazeColumn::class);
});

it('has default label', function () {
    $column = GazeColumn::make();
    
    expect($column->getLabel())->toBe('Viewing');
});

it('is not sortable', function () {
    $column = GazeColumn::make();
    
    expect($column->isSortable())->toBeFalse();
});

it('is not searchable', function () {
    $column = GazeColumn::make();
    
    expect($column->isSearchable())->toBeFalse();
});

it('is toggleable', function () {
    $column = GazeColumn::make();
    
    expect($column->isToggleable())->toBeTrue();
});

it('uses custom view', function () {
    $column = GazeColumn::make();
    
    expect($column->getView())->toBe('filament-typo3::tables.columns.gaze-column');
});

it('excludeCurrentUser method works', function () {
    $column = GazeColumn::make()->excludeCurrentUser(true);
    
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    $identifier = get_class($record) . '-' . $record->getKey();
    
    Cache::put('filament-gaze-' . $identifier, [
        [
            'id' => 1,
            'name' => 'Current User',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ]
    ], now()->addMinutes(10));

    $this->mock(\Illuminate\Contracts\Auth\Guard::class, function ($mock) {
        $mock->shouldReceive('id')->andReturn(1);
    });

    expect($column->getState($record))->toBeFalse();
});

it('getState returns true when viewers exist', function () {
    $column = GazeColumn::make();
    
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    $identifier = get_class($record) . '-' . $record->getKey();
    
    Cache::put('filament-gaze-' . $identifier, [
        [
            'id' => 999,
            'name' => 'Other User',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ]
    ], now()->addMinutes(10));

    expect($column->getState($record))->toBeTrue();
});

it('getState returns false when no viewers', function () {
    $column = GazeColumn::make();
    
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    expect($column->getState($record))->toBeFalse();
});

it('getViewerCount returns correct count', function () {
    $column = GazeColumn::make();
    
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    $identifier = get_class($record) . '-' . $record->getKey();
    
    Cache::put('filament-gaze-' . $identifier, [
        [
            'id' => 1,
            'name' => 'User 1',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ],
        [
            'id' => 2,
            'name' => 'User 2',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ],
    ], now()->addMinutes(10));

    $this->mock(\Illuminate\Contracts\Auth\Guard::class, function ($mock) {
        $mock->shouldReceive('id')->andReturn(1);
    });

    expect($column->getViewerCount($record))->toBe(1);
});

it('getIsOpened returns correct value', function () {
    $column = GazeColumn::make();
    
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    $identifier = get_class($record) . '-' . $record->getKey();
    
    Cache::put('filament-gaze-' . $identifier, [
        [
            'id' => 999,
            'name' => 'Other User',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ]
    ], now()->addMinutes(10));

    expect($column->getIsOpened($record))->toBeTrue();
});

it('extra attributes include viewer data', function () {
    $column = GazeColumn::make();
    
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    $identifier = get_class($record) . '-' . $record->getKey();
    
    Cache::put('filament-gaze-' . $identifier, [
        [
            'id' => 1,
            'name' => 'User 1',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ],
        [
            'id' => 2,
            'name' => 'User 2',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ],
    ], now()->addMinutes(10));

    $this->mock(\Illuminate\Contracts\Auth\Guard::class, function ($mock) {
        $mock->shouldReceive('id')->andReturn(1);
    });

    $extraAttributes = $column->getExtraAttributes($record);
    
    expect($extraAttributes)->toHaveKey('data-gaze-viewers');
    expect($extraAttributes)->toHaveKey('data-gaze-is-opened');
    expect($extraAttributes['data-gaze-viewers'])->toBe('1');
    expect($extraAttributes['data-gaze-is-opened'])->toBe('true');
});
