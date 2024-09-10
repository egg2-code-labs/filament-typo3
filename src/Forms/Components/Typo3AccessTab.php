<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use Egg2CodeLabs\FilamentTypo3\Typo3AccessTabFieldsEnum as FieldsEnum;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use BackedEnum;

class Typo3AccessTab extends AbstractCustomTab
{
    /**
     * @param string $label
     *
     * @return static
     */
    public static function make(string $label = 'Access'): static
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
            Section::make(FieldsEnum::SECTION_VISIBILITY->value)
                /**
                 * We need to provide closures here because they need to be evaluated at a later stage when the whole
                 * schema is parsed and rendered into HTML.
                 */
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
                ->hidden(fn () => $this->isFieldHidden(FieldsEnum::SECTION_DATES))
                ->schema([
                    DateTimePicker::make(FieldsEnum::STARTTIME->value)
                        ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::STARTTIME))
                        ->columnSpan(1)
                        ->suffixAction(
                            Action::make('clear')
                                ->icon('heroicon-o-x-mark')
                                ->action(fn (Set $set, mixed $state) => $set('starttime', null))
                        ),
                    DateTimePicker::make(FieldsEnum::ENDTIME->value)
                        ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::ENDTIME))
                        ->columnSpan(1)
                        ->suffixAction(
                            Action::make('clear')
                                ->icon('heroicon-o-x-mark')
                                ->action(fn (Set $set, mixed $state) => $set('endtime', null))
                        ),
                ])
                ->columns(2),
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
