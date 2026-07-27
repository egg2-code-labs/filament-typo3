<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Database;

use Egg2CodeLabs\FilamentTypo3\Database\Schema\BlueprintMixin;
use Egg2CodeLabs\FilamentTypo3\Enums\Typo3AccessTabFieldsEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('can add typo3 access fields', function (): void {
    Schema::create('test_typo3_access', function (Blueprint $table): void {
        $table->typo3Access();
    });

    $columns = Schema::getColumnListing('test_typo3_access');

    expect($columns)->toContain('hidden');
    expect($columns)->toContain('nav_hide');
    expect($columns)->toContain('starttime');
    expect($columns)->toContain('endtime');
});

it('can exclude specific access fields', function (): void {
    Schema::create('test_typo3_access_exclude', function (Blueprint $table): void {
        $table->typo3Access([Typo3AccessTabFieldsEnum::STARTTIME, Typo3AccessTabFieldsEnum::ENDTIME]);
    });

    $columns = Schema::getColumnListing('test_typo3_access_exclude');

    expect($columns)->toContain('hidden');
    expect($columns)->toContain('nav_hide');
    expect($columns)->not->toContain('starttime');
    expect($columns)->not->toContain('endtime');
});

it('can exclude access fields by string', function (): void {
    Schema::create('test_typo3_access_exclude_string', function (Blueprint $table): void {
        $table->typo3Access(['starttime', 'endtime']);
    });

    $columns = Schema::getColumnListing('test_typo3_access_exclude_string');

    expect($columns)->toContain('hidden');
    expect($columns)->toContain('nav_hide');
    expect($columns)->not->toContain('starttime');
    expect($columns)->not->toContain('endtime');
});

it('can add typo3 sorting field', function (): void {
    Schema::create('test_typo3_sorting', function (Blueprint $table): void {
        $table->typo3Sorting();
    });

    $columns = Schema::getColumnListing('test_typo3_sorting');

    expect($columns)->toContain('sorting');
});

it('typo3Sorting creates unsigned integer column', function (): void {
    Schema::create('test_typo3_sorting_type', function (Blueprint $table): void {
        $table->typo3Sorting();
    });

    $column = Schema::getConnection()->getDoctrineColumn('test_typo3_sorting_type', 'sorting');

    expect($column->getType()->getName())->toBe('integer');
    expect($column->getUnsigned())->toBeTrue();
    expect($column->getNotnull())->toBeFalse(); // nullable
});

it('typo3Access creates correct column types', function (): void {
    Schema::create('test_typo3_access_types', function (Blueprint $table): void {
        $table->typo3Access();
    });

    $hiddenColumn = Schema::getConnection()->getDoctrineColumn('test_typo3_access_types', 'hidden');
    $navHideColumn = Schema::getConnection()->getDoctrineColumn('test_typo3_access_types', 'nav_hide');
    $starttimeColumn = Schema::getConnection()->getDoctrineColumn('test_typo3_access_types', 'starttime');
    $endtimeColumn = Schema::getConnection()->getDoctrineColumn('test_typo3_access_types', 'endtime');

    expect($hiddenColumn->getType()->getName())->toBe('boolean');
    expect($hiddenColumn->getDefault())->toBe('1'); // true

    expect($navHideColumn->getType()->getName())->toBe('boolean');
    expect($navHideColumn->getDefault())->toBe('0'); // false

    expect($starttimeColumn->getType()->getName())->toBe('datetime');
    expect($starttimeColumn->getNotnull())->toBeFalse(); // nullable

    expect($endtimeColumn->getType()->getName())->toBe('datetime');
    expect($endtimeColumn->getNotnull())->toBeFalse(); // nullable
});
