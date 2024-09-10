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
     *
     * @return static
     */
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Get the ID of the plugin.
     *
     * @return string
     */
    public function getId(): string
    {
        return 'filament-typo3';
    }

    /**
     * Boot the plugin.
     *
     * @param Panel $panel The Filament panel instance.
     *
     * @return void
     */
    public function boot(Panel $panel): void
    {
    }

    /**
     * Register the plugin.
     *
     * @param Panel $panel The Filament panel instance.
     *
     * @return void
     */
    public function register(Panel $panel): void
    {
    }
}
