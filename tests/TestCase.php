<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests;

use Egg2CodeLabs\FilamentTypo3\FilamentTypo3ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_skeleton_table.php.stub';
        $migration->up();
        */
    }

    protected function setUp(): void
    {
        parent::setUp();

        //        Factory::guessFactoryNamesUsing(
        //            fn (string $modelName) => 'VendorName\\Skeleton\\Database\\Factories\\'.class_basename($modelName).'Factory'
        //        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentTypo3ServiceProvider::class,
        ];
    }
}
