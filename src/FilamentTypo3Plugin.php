<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * Class FilamentTypo3Plugin
 *
 * This class represents the Filament Typo3 plugin.
 * It implements the Plugin interface and provides methods for booting and registering the plugin.
 */
class FilamentTypo3Plugin implements Plugin
{
    /**
     * Create a new instance of the plugin.
     */
    public static function make(): static
    {
        return resolve(static::class);
    }

    /**
     * Get the ID of the plugin.
     */
    public function getId(): string
    {
        return 'filament-typo3';
    }

    /**
     * Boot the plugin.
     *
     * @param Panel $panel The Filament panel instance.
     */
    public function boot(Panel $panel): void
    {
    }

    /**
     * Register the plugin.
     *
     * @param Panel $panel The Filament panel instance.
     */
    public function register(Panel $panel): void
    {
    }
}
