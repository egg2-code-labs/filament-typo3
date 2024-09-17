<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Egg2CodeLabs\FilamentTypo3\Database\Schema\BlueprintMixin;
use Illuminate\Database\Schema\Blueprint;
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
            ->hasViewComponents('filament-typo3');
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
}
