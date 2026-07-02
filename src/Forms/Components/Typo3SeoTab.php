<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use BackedEnum;
use Egg2CodeLabs\FilamentTypo3\Enums\Typo3SeoTabFieldsEnum as FieldsEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/**
 * TYPO3 SEO Tab component for Filament forms.
 *
 * Provides fields for SEO metadata, similar to TYPO3 CMS functionality.
 */
class Typo3SeoTab extends AbstractCustomTab
{
    /**
     * Number of columns for the tab layout.
     */
    protected int $_columns = 1;

    public static function make(string $label = 'SEO'): static
    {
        return parent::make($label);
    }

    /**
     * Get the schema for the SEO tab.
     *
     * @return array<mixed> The form schema components
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
     * Evaluate and convert field name to enum.
     *
     * @param BackedEnum|string $fieldName The field name to evaluate
     * @return BackedEnum The corresponding enum value
     */
    protected function evaluateEnum(BackedEnum|string $fieldName): BackedEnum
    {
        if (!$fieldName instanceof FieldsEnum) {
            return FieldsEnum::from($fieldName);
        }

        return $fieldName;
    }
}
