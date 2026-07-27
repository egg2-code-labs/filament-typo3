<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Enums;

use Egg2CodeLabs\FilamentTypo3\Enums\Typo3AccessTabFieldsEnum;
use Illuminate\Support\Collection;

it('has all expected field cases', function (): void {
    $cases = Typo3AccessTabFieldsEnum::cases();

    expect($cases)->toHaveCount(6);
    expect($cases)->toContain(Typo3AccessTabFieldsEnum::HIDDEN);
    expect($cases)->toContain(Typo3AccessTabFieldsEnum::NAV_HIDE);
    expect($cases)->toContain(Typo3AccessTabFieldsEnum::STARTTIME);
    expect($cases)->toContain(Typo3AccessTabFieldsEnum::ENDTIME);
    expect($cases)->toContain(Typo3AccessTabFieldsEnum::SECTION_VISIBILITY);
    expect($cases)->toContain(Typo3AccessTabFieldsEnum::SECTION_DATES);
});

it('has correct field values', function (): void {
    expect(Typo3AccessTabFieldsEnum::HIDDEN->value)->toBe('hidden');
    expect(Typo3AccessTabFieldsEnum::NAV_HIDE->value)->toBe('nav_hide');
    expect(Typo3AccessTabFieldsEnum::STARTTIME->value)->toBe('starttime');
    expect(Typo3AccessTabFieldsEnum::ENDTIME->value)->toBe('endtime');
    expect(Typo3AccessTabFieldsEnum::SECTION_VISIBILITY->value)->toBe('Visibility');
    expect(Typo3AccessTabFieldsEnum::SECTION_DATES->value)->toBe('Publish Dates and Access Rights');
});

it('can be converted to collection', function (): void {
    $collection = Typo3AccessTabFieldsEnum::collect();

    expect($collection)->toBeInstanceOf(Collection::class);
    expect($collection)->toHaveCount(6);
});

it('can be converted to options', function (): void {
    $options = Typo3AccessTabFieldsEnum::toOptions();

    expect($options)->toBeInstanceOf(Collection::class);
    expect($options)->toHaveCount(6);
    expect($options->keys())->toContain('HIDDEN');
    expect($options->values())->toContain('hidden');
});
