<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Egg2CodeLabs\FilamentTypo3\Database\Schema\BlueprintMixin;
use Egg2CodeLabs\FilamentTypo3\Livewire\PageTree\Page;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Schema\Blueprint;
use Livewire\Livewire;
use ReflectionException;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Class FilamentGazeServiceProvider
 *
 * @package Egg2CodeLabs\FilamentTypo3
 */
class FilamentTypo3ServiceProvider extends PackageServiceProvider
{
    /**
     * The name of the package.
     *
     * @var string
     */
    public static string $name = 'filament-typo3';

    /**
     * Configure the package.
     *
     * @param Package $package
     *
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        /**
         * TODO: for later versions we could create a single migration for a table that will hold access and timestamp
         *       data through a polymorphic mapping. Right now we will just provide a simple helper to add the required
         *       fields to each migration manually.
         */
        $package
            ->name('filament-typo3')
            ->hasTranslations()
            ->publishesServiceProvider(self::class)
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
     * @return void
     * @throws ReflectionException
     */
    public function packageBooted(): void
    {
        Blueprint::mixin(new BlueprintMixin());
    }

    public function bootingPackage(): void
    {
        parent::bootingPackage();

        $this->registerLivewireComponents();
    }

    public function registerLivewireComponents(): void
    {
        Livewire::component('filament-typo3::page-tree-page', Page::class);
    }
}
