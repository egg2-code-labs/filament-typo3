<?php

namespace Egg2CodeLabs\FilamentTypo3\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

trait Typo3FactoryStatesTrait
{
    public function published(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes) => [
            'hidden' => false,
            'starttime' => null,
            'endtime' => null,
        ]);
    }

    public function hiddenInNav(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes) => [
            'nav_hide' => true,
        ]);
    }

    public function expired(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes) => [
            'hidden' => false,
            'starttime' => null,
            'endtime' => Carbon::now()->subWeek()
        ]);
    }

    public function scheduled(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes) => [
            'hidden' => false,
            'starttime' => Carbon::now()->addWeek(),
            'endtime' => null
        ]);
    }
}
