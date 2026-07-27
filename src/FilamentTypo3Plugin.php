<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * Filament plugin for TYPO3 functionality.
 *
 * This class represents the Filament Typo3 plugin and provides methods
 * for booting and registering the plugin with Filament panels.
 */
class FilamentTypo3Plugin implements Plugin
{
    /**
     * Create a new instance of the plugin.
     *
     * @return static The plugin instance
     */
    public static function make(): static
    {
        return resolve(static::class);
    }

    /**
     * Get the ID of the plugin.
     *
     * @return string The plugin ID
     */
    public function getId(): string
    {
        return 'filament-typo3';
    }

    /**
     * Boot the plugin.
     *
     * @param Panel $panel The Filament panel instance
     */
    public function boot(Panel $panel): void
    {
        // Plugin boot logic can be added here
    }

    /**
     * Register the plugin.
     *
     * @param Panel $panel The Filament panel instance
     */
    public function register(Panel $panel): void
    {
        // Plugin registration logic can be added here
    }
}
