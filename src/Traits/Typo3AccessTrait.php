<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\Typo3AccessTabFieldsEnum;

trait Typo3AccessTrait
{
    public function isDisabledSchedule(): bool
    {
        return !$this->isEnabledSchedule();
    }

    public function isEnabledSchedule(): bool
    {
        if (
            $this->disabledFields->doesntContain(Typo3AccessTabFieldsEnum::STARTTIME)
            || $this->disabledFields->doesntContain(Typo3AccessTabFieldsEnum::ENDTIME)
        ) {
            return false;
        }

        return now()->betweenIncluded(
            date1: $this->starttime,
            date2: $this->endtime,
        );
    }

    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    public function isEnabled(): bool
    {
        if ($this->isHidden()) {
            return false;
        }

        if ($this->isEnabledSchedule()) {
            return false;
        }

        return true;
    }

    public function isHidden(): bool
    {
        if ($this->disabledFields->contains(Typo3AccessTabFieldsEnum::HIDDEN)) {
            return false;
        }

        return (bool)$this->hidden;
    }
}
