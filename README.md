# TYPO3 features for filament panels

[![Latest Version on Packagist](https://img.shields.io/packagist/v/egg2-code-labs/filament-typo3.svg?style=flat-square)](https://packagist.org/packages/egg2-code-labs/filament-typo3)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/egg2-code-labs/filament-typo3/ci.yml?branch=main&label=tests&style=flat-square)](https://github.com/egg2-code-labs/filament-typo3/actions?query=workflow%3Aci+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/egg2-code-labs/filament-typo3.svg?style=flat-square)](https://packagist.org/packages/egg2-code-labs/filament-typo3)
[![PHP Version Requirement](https://img.shields.io/badge/php-%3E%3D8.1-8892B0.svg?style=flat-square)](https://php.net)

**filament-typo3** bundles some functionality known from TYPO3 into a Filament PHP plugin. Features include the TYPO3
entry access tab, SEO tab and pages tree view.

## 📋 Requirements

- PHP 8.1 or higher
- Laravel 10.x or higher
- Filament 4.x
- Livewire 3.x

## 🚀 Installation

You can install the package via composer:

```bash
composer require egg2-code-labs/filament-typo3
```

## 📖 Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="filament-typo3-config"
```

This will create a `config/filament-typo3.php` file with the following options:

```php
return [
    'migrations' => [
        'keyType' => env('FILAMENT_TYPO3_KEY_TYPE', 'id'),
        'table_prefix' => env('FILAMENT_TYPO3_TABLE_PREFIX', ''),
    ],

    'access' => [
        'default_hidden' => env('FILAMENT_TYPO3_DEFAULT_HIDDEN', true),
        'enable_starttime' => env('FILAMENT_TYPO3_ENABLE_STARTTIME', true),
        'enable_endtime' => env('FILAMENT_TYPO3_ENABLE_ENDTIME', true),
        'enable_nav_hide' => env('FILAMENT_TYPO3_ENABLE_NAV_HIDE', true),
    ],

    'sidebar_width' => [
        'sm' => 12,
        'md' => 3,
        'lg' => 3,
        'xl' => 3,
        '2xl' => 3,
    ],

    'cache' => [
        'schema_check_ttl' => 86400, // 24 hours in seconds
    ],
];
```

## 🎯 Features

### TYPO3 Access Tab

The TYPO3 Access Tab feature consists of multiple parts:

1. **Migration helpers** - Easy way to add TYPO3-style access fields to your migrations
2. **Filament component** - Form component for managing access settings
3. **Query scope** - Automatic filtering of records based on access settings

#### Migration Helpers

Make use of the migration helpers to get all the required fields:

```php
use Egg2CodeLabs\FilamentTypo3\Database\Schema\BlueprintMixin;

public function up(): void
{
    Schema::create('pages', function (Blueprint $table) {
        $table->typo3Sorting();
        $table->typo3Access();
    });
}
```

**Available fields:**
- `hidden` - Whether the element is hidden (default: true)
- `nav_hide` - Whether the element is hidden in navigation (default: false)
- `starttime` - When the element becomes visible (nullable)
- `endtime` - When the element becomes hidden (nullable)
- `sorting` - Sorting order (unsigned integer, nullable)

#### Form Component

Then, in your filament resource add the form component:

```php
use Egg2CodeLabs\FilamentTypo3\Forms\Components\Typo3AccessTab;
use Egg2CodeLabs\FilamentTypo3\Enums\Typo3AccessTabFieldsEnum;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Typo3AccessTab::make()
                        ->exclude([ // disable the nav_hide field, because the resource does not use it
                            Typo3AccessTabFieldsEnum::NAV_HIDE,
                        ])
                ])
        ]);
}
```

#### Query Scope

And finally add the query scope to all the necessary models:

```php
use Egg2CodeLabs\FilamentTypo3\Scopes\Typo3AccessScope;

protected static function booted(): void
{
    static::addGlobalScope(new Typo3AccessScope());
}
```

You can also customize the scope behavior:

```php
// Disable sorting
static::addGlobalScope(new Typo3AccessScope(sorting: false));

// Disable specific fields
static::addGlobalScope(new Typo3AccessScope(
    disabledFields: [Typo3AccessTabFieldsEnum::STARTTIME, Typo3AccessTabFieldsEnum::ENDTIME]
));
```

#### Trait for Models

For easier access to access-related methods, use the `Typo3AccessTrait`:

```php
use Egg2CodeLabs\FilamentTypo3\Traits\Typo3AccessTrait;

class Page extends Model
{
    use Typo3AccessTrait;

    // Now you can use:
    // $page->isEnabled() - Check if page is enabled (not hidden and in schedule)
    // $page->isDisabled() - Check if page is disabled
    // $page->isHidden() - Check if page is hidden
    // $page->isEnabledSchedule() - Check if page is in schedule
    // $page->isDisabledSchedule() - Check if page is not in schedule
}
```

### TYPO3 SEO Tab

Similar to the Access Tab, the SEO Tab provides fields for SEO metadata:

```php
use Egg2CodeLabs\FilamentTypo3\Forms\Components\Typo3SeoTab;
use Egg2CodeLabs\FilamentTypo3\Enums\Typo3SeoTabFieldsEnum;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Tabs::make('Tabs')
                ->tabs([
                    Typo3SeoTab::make()
                        ->exclude([ // disable fields you don't need
                            Typo3SeoTabFieldsEnum::META_KEYWORDS,
                        ])
                ])
        ]);
}
```

**Available fields:**
- `canonical_link` - Canonical URL
- `html_title` - HTML title tag
- `meta_abstract` - Meta abstract
- `meta_description` - Meta description
- `meta_keywords` - Meta keywords

### Page Tree / Node Tree

The package provides a TYPO3-style page tree with expandable nodes:

```php
use Egg2CodeLabs\FilamentTypo3\NodeTree;
use Egg2CodeLabs\FilamentTypo3\Traits\HasPageTree;

class PageResource extends Resource
{
    use HasPageTree;

    // This will automatically set up the sidebar with page tree
}
```

For custom implementations:

```php
$nodeTree = NodeTree::make(Page::class)
    ->setTitle('My Pages')
    ->setDescription('Custom page tree');

$nodes = $nodeTree->getNodes(); // Get root nodes
```

#### Model Requirements

For the page tree to work, your model must implement `HasExpandablesInterface`:

```php
use Egg2CodeLabs\FilamentTypo3\Interfaces\HasExpandablesInterface;
use Egg2CodeLabs\FilamentTypo3\Traits\HasExpandablesTrait;

class Page extends Model implements HasExpandablesInterface
{
    use HasExpandablesTrait;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'pid');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'pid');
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public static function getFilamentResource(): string
    {
        return PageResource::class;
    }
}
```

### Bulk Actions

The package provides bulk actions for showing and hiding records:

```php
use Egg2CodeLabs\FilamentTypo3\Tables\Actions\ShowHideBulkActionGroup;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // your columns
        ])
        ->bulkActions([
            ShowHideBulkActionGroup::make(),
        ]);
}
```

You can also use individual actions:

```php
use Egg2CodeLabs\FilamentTypo3\Tables\Actions\ShowHideBulkAction;

ShowHideBulkAction::make('hide-all')->hide();
ShowHideBulkAction::make('show-all')->show();
```

### Active Toggle Column

A special toggle column that inverts the logic for better UX:

```php
use Egg2CodeLabs\FilamentTypo3\Tables\Columns\ActiveToggleColumn;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            ActiveToggleColumn::make('hidden'),
        ]);
}
```

### Slug Input

A slug input with automatic generation from a source field:

```php
use Egg2CodeLabs\FilamentTypo3\Forms\Components\SlugInput;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('title'),
            SlugInput::make('slug')
                ->table('pages')
                ->sourceColumn('title'),
        ]);
}

// Use the slug handler to automatically generate slug when title changes
SlugInput::getSlugHandlerFunction()
```

### Default Action Group

A default action group with common actions (edit, view, delete, restore):

```php
use Egg2CodeLabs\FilamentTypo3\Tables\Actions\DefaultActionGroup;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // your columns
        ])
        ->actions([
            DefaultActionGroup::make(),
        ]);
}
```

You can override default actions by providing actions with the same name:

```php
DefaultActionGroup::make([
    EditAction::make()->icon('heroicon-o-pencil-square'),
    // This will override the default edit action
]);
```

## 🏭 Factories

Laravel factories support state manipulation methods to allow for discrete modifications. The `Typo3FactoryStatesTrait`
provides an easy way to get a few simple modifications going. Add this trait to your factory classes.

### Available methods

#### `Typo3FactoryStatesTrait::published()`

This method creates a non-hidden, non-time-constrained element.

```php
Page::factory()->published()->create();
```

#### `Typo3FactoryStatesTrait::hiddenInNav()`

This method creates an element that is hidden in the navigation.

```php
Page::factory()->hiddenInNav()->create();
```

#### `Typo3FactoryStatesTrait::expired()`

This method creates non-hidden, time-constrained element, which has an `endtime` one week before `now()`.

```php
Page::factory()->expired()->create();
```

#### `Typo3FactoryStatesTrait::scheduled()`

This method creates non-hidden, time-constrained element, which has a `starttime` one week after `now()`.

```php
Page::factory()->scheduled()->create();
```

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Run static analysis:

```bash
composer analyse
```

Check code style:

```bash
composer lint
```

Fix code style issues:

```bash
composer fix
```

## 📚 API Documentation

### Enums

#### Typo3AccessTabFieldsEnum

Fields for the TYPO3 access tab:
- `HIDDEN` - 'hidden'
- `NAV_HIDE` - 'nav_hide'
- `STARTTIME` - 'starttime'
- `ENDTIME` - 'endtime'
- `SECTION_VISIBILITY` - 'Visibility'
- `SECTION_DATES` - 'Publish Dates and Access Rights'

#### Typo3SeoTabFieldsEnum

Fields for the TYPO3 SEO tab:
- `CANONICAL_LINK` - 'canonical_link'
- `HTML_TITLE` - 'html_title'
- `META_ABSTRACT` - 'meta_abstract'
- `META_DESCRIPTION` - 'meta_description'
- `META_KEYWORDS` - 'meta_keywords'

#### InputTypeEnum

Input types for form builder:
- `CHECKBOX`, `COLOR`, `DATE`, `EMAIL`, `FILE`, `HIDDEN`, `MONTH`, `NUMBER`, `RADIO`, `TEL`, `TEXT`, `TIME`, `SELECT`, `TEXTAREA`

### Scopes

#### Typo3AccessScope

Filters records based on TYPO3 access settings:
- Filters hidden records (unless HIDDEN field is disabled)
- Filters by starttime (unless STARTTIME field is disabled)
- Filters by endtime (unless ENDTIME field is disabled)
- Sorts by sorting field (unless sorting is disabled)

### Traits

#### Typo3AccessTrait

Provides methods for checking access status:
- `isEnabled()` - Check if record is enabled
- `isDisabled()` - Check if record is disabled
- `isHidden()` - Check if record is hidden
- `isEnabledSchedule()` - Check if record is in schedule
- `isDisabledSchedule()` - Check if record is not in schedule

#### HasExpandablesTrait

Provides expandable state tracking:
- `expandables()` - Get expandable states relationship

#### HasPageTree

Provides page tree functionality:
- `getSidebar()` - Get the sidebar node tree
- `getModel()` - Get the model class
- `getIncludedSidebarView()` - Get the view to include in sidebar
- `getSidebarWidths()` - Get sidebar widths for different breakpoints

## 🤝 Contributing

Feel free to contribute by writing tests, fixing bugs, reporting bugs or whatever other way of contribution you come up
with. Just create a pull request or issue and I'll do my best to reply fast.

### Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`
4. Run static analysis: `composer analyse`
5. Fix code style: `composer fix`

## 👤 Credits

- [derHofbauer](https://github.com/derHofbauer)

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🔗 Attribution

Some components of this package are based on one or more of the following pieces of software:

+ [filament-page-with-sidebar](https://github.com/aymanalhattami/filament-page-with-sidebar)
