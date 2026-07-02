<?php

namespace Egg2CodeLabs\FilamentTypo3\Database\Schema;

use Closure;
use Egg2CodeLabs\FilamentTypo3\Enums\Typo3AccessTabFieldsEnum;

class BlueprintMixin
{
    /**
     * Add TYPO3 access fields to the table schema.
     *
     * @param array<string|Typo3AccessTabFieldsEnum> $exclude Fields to exclude from adding
     */
    public function typo3Access(): Closure
    {
        return function (array $exclude = []): void {
            $exclude = collect($exclude)
                ->map(
                    fn (string|Typo3AccessTabFieldsEnum $field) => $field instanceof Typo3AccessTabFieldsEnum
                        ? $field
                        : Typo3AccessTabFieldsEnum::from($field)
                );

            if ($exclude->doesntContain(Typo3AccessTabFieldsEnum::NAV_HIDE)) {
                $this->boolean('nav_hide')->default(false);
            }

            if ($exclude->doesntContain(Typo3AccessTabFieldsEnum::STARTTIME)) {
                $this->dateTime('starttime')->nullable();
            }

            if ($exclude->doesntContain(Typo3AccessTabFieldsEnum::ENDTIME)) {
                $this->dateTime('endtime')->nullable();
            }

            if ($exclude->doesntContain(Typo3AccessTabFieldsEnum::HIDDEN)) {
                $this->boolean('hidden')->default(true);
            }
        };
    }

    /**
     * Add TYPO3 sorting field to the table schema.
     */
    public function typo3Sorting(): Closure
    {
        return function (): void {
            $this->unsignedInteger('sorting')->nullable();
        };
    }
}
