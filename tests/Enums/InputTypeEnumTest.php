<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Enums;

use Egg2CodeLabs\FilamentTypo3\Enums\InputTypeEnum;
use Illuminate\Support\Collection;

it('has all expected input type cases', function (): void {
    $cases = InputTypeEnum::cases();

    expect($cases)->toHaveCount(14);
    expect($cases)->toContain(InputTypeEnum::CHECKBOX);
    expect($cases)->toContain(InputTypeEnum::COLOR);
    expect($cases)->toContain(InputTypeEnum::DATE);
    expect($cases)->toContain(InputTypeEnum::EMAIL);
    expect($cases)->toContain(InputTypeEnum::FILE);
    expect($cases)->toContain(InputTypeEnum::HIDDEN);
    expect($cases)->toContain(InputTypeEnum::MONTH);
    expect($cases)->toContain(InputTypeEnum::NUMBER);
    expect($cases)->toContain(InputTypeEnum::RADIO);
    expect($cases)->toContain(InputTypeEnum::TEL);
    expect($cases)->toContain(InputTypeEnum::TEXT);
    expect($cases)->toContain(InputTypeEnum::TIME);
    expect($cases)->toContain(InputTypeEnum::SELECT);
    expect($cases)->toContain(InputTypeEnum::TEXTAREA);
});

it('has correct input type values', function (): void {
    expect(InputTypeEnum::CHECKBOX->value)->toBe('checkbox');
    expect(InputTypeEnum::COLOR->value)->toBe('color');
    expect(InputTypeEnum::DATE->value)->toBe('date');
    expect(InputTypeEnum::EMAIL->value)->toBe('email');
    expect(InputTypeEnum::FILE->value)->toBe('file');
    expect(InputTypeEnum::HIDDEN->value)->toBe('hidden');
    expect(InputTypeEnum::MONTH->value)->toBe('month');
    expect(InputTypeEnum::NUMBER->value)->toBe('number');
    expect(InputTypeEnum::RADIO->value)->toBe('radio');
    expect(InputTypeEnum::TEL->value)->toBe('tel');
    expect(InputTypeEnum::TEXT->value)->toBe('text');
    expect(InputTypeEnum::TIME->value)->toBe('time');
    expect(InputTypeEnum::SELECT->value)->toBe('select');
    expect(InputTypeEnum::TEXTAREA->value)->toBe('textarea');
});

it('can be converted to collection', function (): void {
    $collection = InputTypeEnum::collect();

    expect($collection)->toBeInstanceOf(Collection::class);
    expect($collection)->toHaveCount(14);
});

it('can get choice types', function (): void {
    $choices = InputTypeEnum::choices();

    expect($choices)->toBeInstanceOf(Collection::class);
    expect($choices)->toHaveCount(3);
    expect($choices)->toContain(InputTypeEnum::CHECKBOX);
    expect($choices)->toContain(InputTypeEnum::RADIO);
    expect($choices)->toContain(InputTypeEnum::SELECT);
});

it('can get input types', function (): void {
    $inputs = InputTypeEnum::inputs();

    expect($inputs)->toBeInstanceOf(Collection::class);
    expect($inputs)->toHaveCount(11);
    expect($inputs)->toContain(InputTypeEnum::COLOR);
    expect($inputs)->toContain(InputTypeEnum::DATE);
    expect($inputs)->toContain(InputTypeEnum::EMAIL);
    expect($inputs)->not->toContain(InputTypeEnum::CHECKBOX);
    expect($inputs)->not->toContain(InputTypeEnum::RADIO);
    expect($inputs)->not->toContain(InputTypeEnum::SELECT);
});
