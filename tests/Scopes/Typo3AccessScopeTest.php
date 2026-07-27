<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Scopes;

use Egg2CodeLabs\FilamentTypo3\Enums\Typo3AccessTabFieldsEnum;
use Egg2CodeLabs\FilamentTypo3\Scopes\Typo3AccessScope;
use Egg2CodeLabs\FilamentTypo3\Tests\Models\TestModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    // Create test records
    TestModel::factory()->create(['hidden' => false, 'starttime' => null, 'endtime' => null]);
    TestModel::factory()->create(['hidden' => true, 'starttime' => null, 'endtime' => null]);
    TestModel::factory()->create(['hidden' => false, 'starttime' => now()->addDay(), 'endtime' => null]);
    TestModel::factory()->create(['hidden' => false, 'starttime' => null, 'endtime' => now()->subDay()]);
    TestModel::factory()->create(['hidden' => false, 'starttime' => now()->subDay(), 'endtime' => now()->addDay()]);
});

it('filters hidden records by default', function (): void {
    $scope = new Typo3AccessScope();
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    expect($results)->toHaveCount(3); // Only non-hidden records
    expect($results->pluck('hidden')->all())->toContain(false);
    expect($results->pluck('hidden')->all())->not->toContain(true);
});

it('does not filter hidden records when HIDDEN field is disabled', function (): void {
    $scope = new Typo3AccessScope(disabledFields: [Typo3AccessTabFieldsEnum::HIDDEN]);
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    expect($results)->toHaveCount(5); // All records
});

it('filters records by starttime', function (): void {
    $scope = new Typo3AccessScope();
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    // Should exclude record with starttime in future
    expect($results)->toHaveCount(3);
    expect($results->pluck('id')->all())->not->toContain(3); // Record with future starttime
});

it('does not filter by starttime when STARTTIME field is disabled', function (): void {
    $scope = new Typo3AccessScope(disabledFields: [Typo3AccessTabFieldsEnum::STARTTIME]);
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    // Should include record with starttime in future
    expect($results)->toHaveCount(4); // All except hidden ones
    expect($results->pluck('id')->all())->toContain(3);
});

it('filters records by endtime', function (): void {
    $scope = new Typo3AccessScope();
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    // Should exclude record with endtime in past
    expect($results)->toHaveCount(3);
    expect($results->pluck('id')->all())->not->toContain(4); // Record with past endtime
});

it('does not filter by endtime when ENDTIME field is disabled', function (): void {
    $scope = new Typo3AccessScope(disabledFields: [Typo3AccessTabFieldsEnum::ENDTIME]);
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    // Should include record with endtime in past
    expect($results)->toHaveCount(4); // All except hidden ones
    expect($results->pluck('id')->all())->toContain(4);
});

it('sorts records by sorting field by default', function (): void {
    // Create records with different sorting values
    TestModel::factory()->create(['hidden' => false, 'sorting' => 10]);
    TestModel::factory()->create(['hidden' => false, 'sorting' => 5]);
    TestModel::factory()->create(['hidden' => false, 'sorting' => 15]);

    $scope = new Typo3AccessScope();
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    $sortingValues = $results->pluck('sorting')->toArray();
    expect($sortingValues)->toBeSorted();
});

it('does not sort when sorting is disabled', function (): void {
    // Create records with different sorting values
    TestModel::factory()->create(['hidden' => false, 'sorting' => 10]);
    TestModel::factory()->create(['hidden' => false, 'sorting' => 5]);
    TestModel::factory()->create(['hidden' => false, 'sorting' => 15]);

    $scope = new Typo3AccessScope(sorting: false);
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    // Should not be sorted
    $sortingValues = $results->pluck('sorting')->toArray();
    expect($sortingValues)->not->toBeSorted();
});

it('can disable multiple fields', function (): void {
    $scope = new Typo3AccessScope(disabledFields: [
        Typo3AccessTabFieldsEnum::HIDDEN,
        Typo3AccessTabFieldsEnum::STARTTIME,
        Typo3AccessTabFieldsEnum::ENDTIME
    ]);
    $query = TestModel::query();
    $scope->apply($query, new TestModel());

    $results = $query->get();

    expect($results)->toHaveCount(5); // All records
});
