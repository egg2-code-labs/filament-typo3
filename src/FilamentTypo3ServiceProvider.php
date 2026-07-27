<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Egg2CodeLabs\FilamentTypo3\Database\Schema\BlueprintMixin;
use Egg2CodeLabs\FilamentTypo3\Livewire\NodeTree\Node;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Schema\Blueprint;
use Livewire\Livewire;
use ReflectionException;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Service provider for the Filament TYPO3 package.
 *
 * Registers package resources, migrations, views, assets, and Livewire components.
 */
class FilamentTypo3ServiceProvider extends PackageServiceProvider
{
    /**
     * The name of the package.
     */
    public static string $name = 'filament-typo3';

    /**
     * Configure the package.
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-typo3')
            ->hasConfigFile()
            ->hasTranslations()
            ->publishesServiceProvider(self::class)
            ->hasMigrations([
                '2024_10_25_create_filament_typo3_expandable_state_table'
            ])
            ->runsMigrations()
            ->hasViews()
            ->hasAssets()
            ->hasViewComponents('filament-typo3');

        FilamentAsset::register(
            assets: [
                Css::make(
                    id: 'filament-typo3',
                    path: __DIR__ . '/../resources/dist/app.css'
                ),
            ],
            package: 'egg2-code-labs/filament-typo3'
        );
    }

    /**
     * Perform any actions after the package has booted.
     *
     * @throws ReflectionException
     */
    public function packageBooted(): void
    {
        Blueprint::mixin(new BlueprintMixin());
    }

    /**
     * Perform actions during package booting.
     */
    public function bootingPackage(): void
    {
        parent::bootingPackage();

        $this->registerLivewireComponents();
    }

    /**
     * Register Livewire components for the package.
     */
    public function registerLivewireComponents(): void
    {
        Livewire::component('filament-typo3::node-tree-node', Node::class);
    }
}
