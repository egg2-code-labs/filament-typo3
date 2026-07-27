<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use BackedEnum;
use Closure;
use Egg2CodeLabs\FilamentTypo3\Enums\Typo3AccessTabFieldsEnum as FieldsEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Contracts\Support\Htmlable;

/**
 * TYPO3 Access Tab component for Filament forms.
 *
 * Provides fields for controlling visibility and publish dates of records,
 * similar to TYPO3 CMS functionality.
 */
class Typo3AccessTab extends AbstractCustomTab
{
    /**
     * Number of columns for the tab layout.
     */
    protected int $_columns = 2;

    public static function make(string|Htmlable|Closure|null $label = 'Access'): static
    {
        return parent::make($label);
    }

    /**
     * Get the schema for the access tab.
     *
     * @return array<mixed> The form schema components
     */
    protected function getSchema(): array
    {
        return [
            Section::make(FieldsEnum::SECTION_VISIBILITY->value)
                ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::SECTION_VISIBILITY))
                ->schema([
                    Toggle::make(FieldsEnum::HIDDEN->value)
                        ->inline(false)
                        ->helperText('When enabled, the element will not be displayed in the frontend.')
                        ->default(true)
                        ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::HIDDEN))
                        ->columnSpan(fn (): int => $this->isFieldHidden(FieldsEnum::NAV_HIDE) ? 2 : 1),
                    Toggle::make(FieldsEnum::NAV_HIDE->value)
                        ->inline(false)
                        ->helperText('When enabled, the element will not be displayed in navigations.')
                        ->default(false)
                        ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::NAV_HIDE))
                        ->columnSpan(1)
                ])
                ->columns(2),
            Section::make(FieldsEnum::SECTION_DATES->value)
                ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::SECTION_DATES))
                ->schema([
                    DateTimePicker::make(FieldsEnum::STARTTIME->value)
                        ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::STARTTIME))
                        ->columnSpan(1)
                        ->suffixAction(
                            Action::make('clear')
                                ->icon('heroicon-o-x-mark')
                                ->action(fn (Set $set): mixed => $set('starttime', null))
                        ),
                    DateTimePicker::make(FieldsEnum::ENDTIME->value)
                        ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::ENDTIME))
                        ->columnSpan(1)
                        ->suffixAction(
                            Action::make('clear')
                                ->icon('heroicon-o-x-mark')
                                ->action(fn (Set $set): mixed => $set('endtime', null))
                        ),
                ])
                ->columns(2),
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
