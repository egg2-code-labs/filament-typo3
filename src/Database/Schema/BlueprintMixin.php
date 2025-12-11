<?php

namespace Egg2CodeLabs\FilamentTypo3\Database\Schema;

use Closure;
use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\Typo3AccessTabFieldsEnum;

class BlueprintMixin
{
    public function typo3Access(): Closure
    {
        /**
         * @param array<string|Typo3AccessTabFieldsEnum> $exclude
         */
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
                $this->boolean('hidden')->default(1);
            }
        };
    }

    public function typo3Sorting(): Closure
    {
        return function (): void {
            $this->unsignedInteger('sorting')->nullable();
        };
    }
}
