<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests;

use Egg2CodeLabs\FilamentTypo3\FilamentTypo3ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        // Set up test database
        Schema::defaultStringLength(191);

        // Create test tables
        Schema::create('test_models', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->boolean('hidden')->default(true);
            $table->boolean('nav_hide')->default(false);
            $table->dateTime('starttime')->nullable();
            $table->dateTime('endtime')->nullable();
            $table->unsignedInteger('sorting')->nullable();
            $table->unsignedInteger('pid')->default(0);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        // Create the expandable state table
        Schema::create('filament_typo3_expandable_state', function (Blueprint $table): void {
            $table->id();
            $table->string('user_id');
            $table->morphs('expandable');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Set up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Egg2CodeLabs\\FilamentTypo3\\Tests\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    /**
     * Get the package providers.
     *
     * @return array<string> Array of provider classes
     */
    protected function getPackageProviders($app): array
    {
        return [
            FilamentTypo3ServiceProvider::class,
        ];
    }
}
