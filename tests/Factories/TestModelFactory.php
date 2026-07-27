<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Factories;

use Egg2CodeLabs\FilamentTypo3\Database\Factories\Typo3FactoryStatesTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\Egg2CodeLabs\FilamentTypo3\Tests\Models\TestModel>
 */
class TestModelFactory extends Factory
{
    use Typo3FactoryStatesTrait;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Egg2CodeLabs\FilamentTypo3\Tests\Models\TestModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'hidden' => true,
            'nav_hide' => false,
            'starttime' => null,
            'endtime' => null,
            'sorting' => 0,
            'pid' => 0,
        ];
    }
}
