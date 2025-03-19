<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums;

use Illuminate\Support\Collection;

enum InputTypeEnum: string
{
    use CollectableEnumTrait;

    case CHECKBOX = 'checkbox';
    case COLOR = 'color';
    case DATE = 'date';
    case EMAIL = 'email';
    case FILE = 'file';
    case HIDDEN = 'hidden';
    case MONTH = 'month';
    case NUMBER = 'number';
    case RADIO = 'radio';
//    case RANGE = 'range';
    case TEL = 'tel';
    case TEXT = 'text';
    case TIME = 'time';
    case SELECT = 'select';
    case TEXTAREA = 'textarea';

    /**
     * @return Collection<static>
     */
    public static function choices(): Collection
    {
        return collect([
            self::CHECKBOX,
            self::RADIO,
            self::SELECT
        ]);
    }

    /**
     * @return Collection<static>
     */
    public static function inputs(): Collection
    {
        return collect([
            self::COLOR,
            self::DATE,
            self::EMAIL,
            self::FILE,
            self::HIDDEN,
            self::MONTH,
            self::NUMBER,
            self::TEL,
            self::TEXT,
            self::TIME,
            self::TEXTAREA
        ]);
    }
}
