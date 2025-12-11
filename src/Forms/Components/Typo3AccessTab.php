<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use BackedEnum;
use Closure;
use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\Typo3AccessTabFieldsEnum as FieldsEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Contracts\Support\Htmlable;

class Typo3AccessTab extends AbstractCustomTab
{
    public static function make(string|Htmlable|Closure|null $label = 'Access'): static
    {
        return parent::make($label);
    }

    /**
     * Get the schema for the whole tab
     *
     * @return array
     *
     * TODO: try to make this look at the DB schema for the model and hide fields automatically
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
                ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::SECTION_DATES))
                ->schema([
                    DateTimePicker::make(FieldsEnum::STARTTIME->value)
                        ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::STARTTIME))
                        ->columnSpan(1)
                        ->suffixAction(
                            Action::make('clear')
                                ->icon('heroicon-o-x-mark')
                                ->action(fn (Set $set, mixed $state): mixed => $set('starttime', null))
                        ),
                    DateTimePicker::make(FieldsEnum::ENDTIME->value)
                        ->hidden(fn (): bool => $this->isFieldHidden(FieldsEnum::ENDTIME))
                        ->columnSpan(1)
                        ->suffixAction(
                            Action::make('clear')
                                ->icon('heroicon-o-x-mark')
                                ->action(fn (Set $set, mixed $state): mixed => $set('endtime', null))
                        ),
                ])
                ->columns(2),
        ];
    }

    protected function evaluateEnum(BackedEnum|string $fieldName): BackedEnum
    {
        if (!$fieldName instanceof FieldsEnum) {
            return FieldsEnum::from($fieldName);
        }

        return $fieldName;
    }
}
