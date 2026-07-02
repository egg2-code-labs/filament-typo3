<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Database\Factories;

use Egg2CodeLabs\FilamentTypo3\Tests\Models\TestModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;

uses(RefreshDatabase::class);

it('published state creates non-hidden record with no time constraints', function (): void {
    $model = TestModel::factory()->published()->create();

    expect($model->hidden)->toBeFalse();
    expect($model->starttime)->toBeNull();
    expect($model->endtime)->toBeNull();
});

it('hiddenInNav state creates record hidden in navigation', function (): void {
    $model = TestModel::factory()->hiddenInNav()->create();

    expect($model->nav_hide)->toBeTrue();
});

it('expired state creates record with endtime in past', function (): void {
    $before = Date::now()->subWeek();
    $model = TestModel::factory()->expired()->create();
    $after = Date::now();

    expect($model->hidden)->toBeFalse();
    expect($model->starttime)->toBeNull();
    expect($model->endtime)->not->toBeNull();
    expect($model->endtime)->toBeBetween($before, $after, true);
});

it('scheduled state creates record with starttime in future', function (): void {
    $now = Date::now();
    $model = TestModel::factory()->scheduled()->create();
    $later = Date::now()->addWeek();

    expect($model->hidden)->toBeFalse();
    expect($model->starttime)->not->toBeNull();
    expect($model->starttime)->toBeBetween($now, $later, true);
    expect($model->endtime)->toBeNull();
});

it('can chain states', function (): void {
    $model = TestModel::factory()
        ->published()
        ->hiddenInNav()
        ->create();

    expect($model->hidden)->toBeFalse();
    expect($model->nav_hide)->toBeTrue();
    expect($model->starttime)->toBeNull();
    expect($model->endtime)->toBeNull();
});
