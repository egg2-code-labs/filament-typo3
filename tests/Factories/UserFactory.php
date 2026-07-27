<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\Illuminate\Foundation\Auth\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Illuminate\Foundation\Auth\User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
