<?php

use Egg2CodeLabs\FilamentTypo3\Gaze;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::clear();
});

afterEach(function () {
    Cache::clear();
});

it('isOpened returns false when no viewers', function () {
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    expect(Gaze::isOpened($record))->toBeFalse();
});

it('isOpened returns true when viewers exist', function () {
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    $identifier = get_class($record) . '-' . $record->getKey();
    
    Cache::put('filament-gaze-' . $identifier, [
        [
            'id' => 999,
            'name' => 'Test User',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ]
    ], now()->addMinutes(10));

    expect(Gaze::isOpened($record))->toBeTrue();
});

it('isOpened excludes current user by default', function () {
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

    expect(Gaze::isOpened($record, true))->toBeFalse();
});

it('isOpened includes current user when exclude is false', function () {
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

    expect(Gaze::isOpened($record, false))->toBeTrue();
});

it('getViewerCount returns correct count', function () {
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
        [
            'id' => 3,
            'name' => 'User 3',
            'expires' => now()->addMinutes(10)->toDateTimeString(),
            'has_control' => false,
        ],
    ], now()->addMinutes(10));

    $this->mock(\Illuminate\Contracts\Auth\Guard::class, function ($mock) {
        $mock->shouldReceive('id')->andReturn(1);
    });

    expect(Gaze::getViewerCount($record, true))->toBe(2);
});

it('getIdentifier returns correct format', function () {
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 123;
    };

    $identifier = Gaze::getIdentifier($record);
    
    expect($identifier)->toBe(get_class($record) . '-123');
});

it('isLockedByOther returns true when locked by other user', function () {
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
            'has_control' => true,
        ]
    ], now()->addMinutes(10));

    $this->mock(\Illuminate\Contracts\Auth\Guard::class, function ($mock) {
        $mock->shouldReceive('id')->andReturn(1);
    });

    expect(Gaze::isLockedByOther($record))->toBeTrue();
});

it('isLockedByOther returns false when current user has control', function () {
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
            'has_control' => true,
        ]
    ], now()->addMinutes(10));

    $this->mock(\Illuminate\Contracts\Auth\Guard::class, function ($mock) {
        $mock->shouldReceive('id')->andReturn(1);
    });

    expect(Gaze::isLockedByOther($record))->toBeFalse();
});

it('getViewers returns correct list', function () {
    $record = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test_models';
        public $id = 1;
    };

    $identifier = get_class($record) . '-' . $record->getKey();
    
    $viewers = [
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
    ];

    Cache::put('filament-gaze-' . $identifier, $viewers, now()->addMinutes(10));

    $this->mock(\Illuminate\Contracts\Auth\Guard::class, function ($mock) {
        $mock->shouldReceive('id')->andReturn(1);
    });

    $result = Gaze::getViewers($record, true);
    
    expect($result)->toHaveCount(1);
    expect($result[0]['name'])->toBe('User 2');
});

it('isOpened returns false for null record', function () {
    expect(Gaze::isOpened(null))->toBeFalse();
});

it('getViewerCount returns zero for null record', function () {
    expect(Gaze::getViewerCount(null))->toBe(0);
});

it('getViewers returns empty array for null record', function () {
    expect(Gaze::getViewers(null))->toBe([]);
});

it('isLockedByOther returns false for null record', function () {
    expect(Gaze::isLockedByOther(null))->toBeFalse();
});
