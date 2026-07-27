<?php

namespace Egg2CodeLabs\FilamentTypo3\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

/**
 * Trait providing factory states for TYPO3-style functionality.
 *
 * Provides common states for testing TYPO3 access and visibility features.
 */
trait Typo3FactoryStatesTrait
{
    /**
     * Create a published (visible and active) state.
     *
     * @return self The factory instance with published state
     */
    public function published(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes): array => [
            'hidden' => false,
            'starttime' => null,
            'endtime' => null,
        ]);
    }

    /**
     * Create a state where the element is hidden in navigation.
     *
     * @return self The factory instance with hidden in nav state
     */
    public function hiddenInNav(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes): array => [
            'nav_hide' => true,
        ]);
    }

    /**
     * Create an expired state (endtime in the past).
     *
     * @return self The factory instance with expired state
     */
    public function expired(): self
    {
        /** @var Factory $this */
        return $this->state(fn (array $attributes): array => [
            'hidden' => false,
            'starttime' => null,
            'endtime' => Date::now()->subWeek()
        ]);
    }

    /**
     * Create a scheduled state (starttime in the future).
     *
     * @return self The factory instance with scheduled state
     */
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
