<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use BackedEnum;
use Egg2CodeLabs\FilamentTypo3\Typo3SeoTabFieldsEnum as FieldsEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class Typo3SeoTab extends AbstractCustomTab
{
    protected int $_columns = 1;

    /**
     * @param string $label
     *
     * @return static
     */
    public static function make(string $label = 'SEO'): static
    {
        return parent::make($label);
    }

    /**
     * Get the schema for the whole tab
     *
     * @return array
     */
    protected function getSchema(): array
    {
        return [
            TextInput::make(FieldsEnum::CANONICAL_LINK->value)
                ->url()
                ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::CANONICAL_LINK)),
            TextInput::make(FieldsEnum::HTML_TITLE->value)
                ->string()
                ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::HTML_TITLE)),
            Textarea::make(FieldsEnum::META_ABSTRACT->value)
                ->string()
                ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::META_ABSTRACT)),
            Textarea::make(FieldsEnum::META_DESCRIPTION->value)
                ->string()
                ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::META_DESCRIPTION)),
            TextInput::make(FieldsEnum::META_KEYWORDS->value)
                ->string()
                ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::META_KEYWORDS))
        ];
    }

    /**
     * @param BackedEnum|string $fieldName
     *
     * @return BackedEnum
     */
    protected function evaluateEnum(BackedEnum|string $fieldName): BackedEnum
    {
        if (!$fieldName instanceof FieldsEnum) {
            return FieldsEnum::from($fieldName);
        }

        return $fieldName;
    }
}
