<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

/**
 * Class FilamentTypo3Plugin
 *
 * This class represents the Filament Typo3 plugin.
 * It implements the Plugin interface and provides methods for booting and registering the plugin.
 */
class FilamentTypo3Plugin implements Plugin
{
    /**
     * Whether the top-bar bookmarks feature is enabled.
     */
    protected bool $bookmarksEnabled = false;

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
     * Enable or disable the top-bar bookmarks feature.
     *
     * When enabled a bookmark dropdown is injected into the Filament top bar via a render hook.
     * The authenticated user's model must use {@see \Egg2CodeLabs\FilamentTypo3\Traits\HasBookmarksTrait}
     * and the `bookmarks` column must exist on the users table.
     */
    public function bookmarks(bool $enabled = true): static
    {
        $this->bookmarksEnabled = $enabled;

        return $this;
    }

    /**
     * Determine whether the bookmarks feature is enabled.
     */
    public function isBookmarksEnabled(): bool
    {
        return $this->bookmarksEnabled;
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
        if ($this->bookmarksEnabled) {
            $panel->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): string => Blade::render('<livewire:filament-typo3::bookmarks-button />')
            );
        }
    }
}
