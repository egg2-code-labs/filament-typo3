# TYPO3 features for filament panels

[![Latest Version on Packagist](https://img.shields.io/packagist/v/egg2-code-labs/filament-typo3.svg?style=flat-square)](https://packagist.org/packages/egg2-code-labs/filament-typo3)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/:vendor_slug/:package_slug/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/:vendor_slug/:package_slug/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/egg2-code-labs/filament-typo3.svg?style=flat-square)](https://packagist.org/packages/egg2-code-labs/filament-typo3)

**filament-typo3** bundles some functionality known from TYPO3 into a Filament PHP plugin. Features include the TYPO3
entry access tab, SEO tab and pages tree view.

## Installation

You can install the package via composer:

```bash
composer require egg2-code-labs/filament-typo3
```

## TYPO3 Access Tab

The TYPO3 Access Tab feature consists of multiple parts:

1. Migration helpers
2. Filament component
3. Query scope

Make use of the migration helpers to get all the required fields:

```php
public function up(): void
{
    Schema::create('pages', function (Blueprint $table) {
        //
        $table->typo3Sorting();
        $table->typo3Access();
        //
    });
}
```

Then, in your filament resource add the form component:

```php
use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\Typo3AccessTabFieldsEnum;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Typo3AccessTab::make()
                        ->exclude([ // disable the nav_hide field, because the resources does not use it
                            Typo3AccessTabFieldsEnum::NAV_HIDE,
                        ])
                ])
        ]);
}
```

And finally add the query scope to all the necessary models:

```php
use Egg2CodeLabs\FilamentTypo3\Scopes\Typo3AccessScope;

protected static function booted(): void
{
    static::addGlobalScope(new Typo3AccessScope());
}
```

## Factories

Laravel factories support state manipulation methods to allow for discrete modifications. The `Typo3FactoryStatesTrait`
provides an easy way to get a few simple modifications going. Add this trait to your factory classes.

### Available methods

#### `Typo3FactoryStatesTrait::published()`

This method creates a non-hidden, non-time-constrained element.

#### `Typo3FactoryStatesTrait::hiddenInNav()`

This method creates an element that is hidden in the navigation.

#### `Typo3FactoryStatesTrait::expired()`

This method creates non-hidden, time-constrained element, which has an `endtime` one week before `now()`.

#### `Typo3FactoryStatesTrait::scheduled()`

This method creates non-hidden, time-constrained element, which has a `starttime` one week after `now()`.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Feel free to contribute by writing tests, fixing bugs, reporting bugs or whatever other way of contribution you come up
with. Just create a pull request or issue and I'll do my best to reply fast.

## Credits

- [derHofbauer](https://github.com/derHofbauer)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Attribution

Some components of this package are based on one or more of the following pieces of software:

+ https://github.com/aymanalhattami/filament-page-with-sidebar
