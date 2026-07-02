<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Traits;

use Egg2CodeLabs\FilamentTypo3\Tests\Models\TestModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::clear();
});

it('isEnabled returns true for published records', function (): void {
    $model = TestModel::factory()->published()->create();

    expect($model->isEnabled())->toBeTrue();
    expect($model->isDisabled())->toBeFalse();
});

it('isEnabled returns false for hidden records', function (): void {
    $model = TestModel::factory()->create(['hidden' => true]);

    expect($model->isEnabled())->toBeFalse();
    expect($model->isDisabled())->toBeTrue();
});

it('isEnabled returns false for expired records', function (): void {
    $model = TestModel::factory()->expired()->create();

    expect($model->isEnabled())->toBeFalse();
    expect($model->isDisabled())->toBeTrue();
});

it('isEnabled returns false for scheduled records', function (): void {
    $model = TestModel::factory()->scheduled()->create();

    expect($model->isEnabled())->toBeFalse();
    expect($model->isDisabled())->toBeTrue();
});

it('isEnabledSchedule returns true for records in schedule', function (): void {
    $model = TestModel::factory()->create([
        'hidden' => false,
        'starttime' => now()->subDay(),
        'endtime' => now()->addDay(),
    ]);

    expect($model->isEnabledSchedule())->toBeTrue();
    expect($model->isDisabledSchedule())->toBeFalse();
});

it('isEnabledSchedule returns false for records with starttime in future', function (): void {
    $model = TestModel::factory()->create([
        'hidden' => false,
        'starttime' => now()->addDay(),
        'endtime' => null,
    ]);

    expect($model->isEnabledSchedule())->toBeFalse();
    expect($model->isDisabledSchedule())->toBeTrue();
});

it('isEnabledSchedule returns false for records with endtime in past', function (): void {
    $model = TestModel::factory()->create([
        'hidden' => false,
        'starttime' => null,
        'endtime' => now()->subDay(),
    ]);

    expect($model->isEnabledSchedule())->toBeFalse();
    expect($model->isDisabledSchedule())->toBeTrue();
});

it('isHidden returns true for hidden records', function (): void {
    $model = TestModel::factory()->create(['hidden' => true]);

    expect($model->isHidden())->toBeTrue();
});

it('isHidden returns false for non-hidden records', function (): void {
    $model = TestModel::factory()->create(['hidden' => false]);

    expect($model->isHidden())->toBeFalse();
});

it('isHidden returns false when column does not exist', function (): void {
    // Create a model without the hidden column in schema
    $model = new class extends TestModel {
        protected $table = 'test_models_no_hidden';
    };

    // Mock the schema check to return false
    $model->shouldReceive('hasSchemaColumnCached')->andReturn(false);

    expect($model->isHidden())->toBeFalse();
});

it('caches schema column checks', function (): void {
    $model = TestModel::factory()->create();

    // First call should check schema
    $model->isHidden();

    // Second call should use cache
    $model->isHidden();

    // Cache should have the key
    expect(Cache::has("filament-typo3:test_models:hidden:schema"))->toBeTrue();
});
