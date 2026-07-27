<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Enums;

use Egg2CodeLabs\FilamentTypo3\Enums\Typo3SeoTabFieldsEnum;
use Illuminate\Support\Collection;

it('has all expected field cases', function (): void {
    $cases = Typo3SeoTabFieldsEnum::cases();

    expect($cases)->toHaveCount(5);
    expect($cases)->toContain(Typo3SeoTabFieldsEnum::CANONICAL_LINK);
    expect($cases)->toContain(Typo3SeoTabFieldsEnum::HTML_TITLE);
    expect($cases)->toContain(Typo3SeoTabFieldsEnum::META_ABSTRACT);
    expect($cases)->toContain(Typo3SeoTabFieldsEnum::META_DESCRIPTION);
    expect($cases)->toContain(Typo3SeoTabFieldsEnum::META_KEYWORDS);
});

it('has correct field values', function (): void {
    expect(Typo3SeoTabFieldsEnum::CANONICAL_LINK->value)->toBe('canonical_link');
    expect(Typo3SeoTabFieldsEnum::HTML_TITLE->value)->toBe('html_title');
    expect(Typo3SeoTabFieldsEnum::META_ABSTRACT->value)->toBe('meta_abstract');
    expect(Typo3SeoTabFieldsEnum::META_DESCRIPTION->value)->toBe('meta_description');
    expect(Typo3SeoTabFieldsEnum::META_KEYWORDS->value)->toBe('meta_keywords');
});

it('can be converted to collection', function (): void {
    $collection = Typo3SeoTabFieldsEnum::collect();

    expect($collection)->toBeInstanceOf(Collection::class);
    expect($collection)->toHaveCount(5);
});

it('can be converted to options', function (): void {
    $options = Typo3SeoTabFieldsEnum::toOptions();

    expect($options)->toBeInstanceOf(Collection::class);
    expect($options)->toHaveCount(5);
    expect($options->keys())->toContain('CANONICAL_LINK');
    expect($options->values())->toContain('canonical_link');
});
