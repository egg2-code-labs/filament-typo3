<?php

namespace Egg2CodeLabs\FilamentTypo3\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

trait Typo3FactoryStatesTrait
{
    public function published(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes): array => [
            'hidden' => false,
            'starttime' => null,
            'endtime' => null,
        ]);
    }

    public function hiddenInNav(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes): array => [
            'nav_hide' => true,
        ]);
    }

    public function expired(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes): array => [
            'hidden' => false,
            'starttime' => null,
            'endtime' => Date::now()->subWeek()
        ]);
    }

    public function scheduled(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes): array => [
            'hidden' => false,
            'starttime' => Date::now()->addWeek(),
            'endtime' => null
        ]);
    }
}
