# Gaze Integration

This package provides integration with [filament-gaze](https://github.com/discoverydesign/filament-gaze) to show whether records are currently being viewed by other users.

## Installation

First, install the filament-gaze package:

```bash
composer require discoverydesign/filament-gaze
```

Then add the plugin to your Filament panel provider:

```php
$panel->plugins([
    \DiscoveryDesign\FilamentGaze\FilamentGazePlugin::make()
]);
```

## Usage

### Helper Methods

The `Gaze` helper class provides several methods to check viewing status:

#### `Gaze::isOpened($record, $excludeCurrentUser = true)`

Check if a record is currently being viewed by someone.

```php
use Egg2CodeLabs\FilamentTypo3\Gaze;

// In your resource or controller
if (Gaze::isOpened($record)) {
    // Someone is viewing this record
}

// Exclude current user from the check (default: true)
if (Gaze::isOpened($record, false)) {
    // Someone (including possibly current user) is viewing this record
}
```

#### `Gaze::getViewerCount($record, $excludeCurrentUser = true)`

Get the number of users currently viewing a record.

```php
$count = Gaze::getViewerCount($record);
// Returns: 2 (if 2 other users are viewing)
```

#### `Gaze::isLockedByOther($record)`

Check if a record is currently locked by someone else (when using filament-gaze's lock feature).

```php
if (Gaze::isLockedByOther($record)) {
    // Record is locked by another user
}
```

#### `Gaze::getViewers($record, $excludeCurrentUser = true)`

Get the list of users currently viewing a record.

```php
$viewers = Gaze::getViewers($record);
// Returns: array of viewer information
```

### GazeColumn

Use the `GazeColumn` in your Filament resource tables to display viewing status:

```php
use Egg2CodeLabs\FilamentTypo3\Tables\Columns\GazeColumn;

GazeColumn::make(),
```

This will:
- Show an eye icon that's highlighted when someone is viewing the record
- Display the number of viewers next to the icon
- Be toggleable, but not sortable or searchable (as it's computed)

#### Customizing the Column

```php
GazeColumn::make()
    ->label('Currently Viewing')
    ->excludeCurrentUser(true) // Default: true
    ->toggleable(false),
```

### Example: Hide Edit Button When Record is Being Viewed

```php
use Egg2CodeLabs\FilamentTypo3\Gaze;
use Filament\Tables\Actions\EditAction;

EditAction::make()
    ->hidden(fn ($record) => Gaze::isOpened($record)),
```

### Example: Show Viewer Count in a Badge

```php
use Egg2CodeLabs\FilamentTypo3\Gaze;

// In your resource table
TextColumn::make('viewer_count')
    ->state(fn ($record) => Gaze::getViewerCount($record))
    ->badge()
    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),
```

## How It Works

The integration works by reading from the same cache that filament-gaze uses to track viewers. The cache key format is `filament-gaze-{class}-{id}`, which matches what filament-gaze uses internally.

The helper methods check this cache and return information about who is currently viewing the record, with options to exclude the current user from the results.

## Notes

- This integration requires filament-gaze to be installed and configured
- The cache entries are automatically managed by filament-gaze
- Viewer information expires based on filament-gaze's poll timer settings
- The current user is excluded by default from all checks to avoid showing "you are viewing this record"
