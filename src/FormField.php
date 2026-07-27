<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Egg2CodeLabs\FilamentTypo3\Enums\InputTypeEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Form field data object for representing and generating Filament form fields.
 *
 * Provides a standardized way to define form fields with a well-defined public API
 * for type-hinting and validation, similar to TYPO3 CMS form field definitions.
 */
class FormField
{
    /**
     * The type of the form field.
     */
    public InputTypeEnum $type;

    /**
     * The name of the form field.
     */
    public null|string $name;

    /**
     * The label of the form field.
     */
    public null|string $label;

    /**
     * The hint text for the form field.
     */
    public null|string $hint;

    /**
     * Whether the form field is required.
     */
    public bool $required;

    /**
     * The placeholder text for the form field.
     */
    public null|string $placeholder;

    /**
     * The default value for the form field.
     */
    public null|string $value;

    /**
     * The options for select/radio fields.
     */
    public null|Collection $options = null;

    /**
     * Create a new FormField instance from form builder data.
     *
     * @param array $formBuilderData The form builder data array
     */
    public function __construct(array $formBuilderData)
    {
        $this->type = InputTypeEnum::from(
            Str::lower(
                Arr::get($formBuilderData, 'data.type')
            )
        );
        $this->name = Arr::get($formBuilderData, 'data.name');
        $this->label = Arr::get($formBuilderData, 'data.label');
        $this->hint = Arr::get($formBuilderData, 'data.hint');
        $this->required = Arr::get($formBuilderData, 'data.required');
        $this->placeholder = Arr::get($formBuilderData, 'data.placeholder');
        $this->value = Arr::get($formBuilderData, 'data.value');

        $this->setOptions(Arr::get($formBuilderData, 'data.options'));
    }

    /**
     * Set the options for select/radio fields.
     *
     * @param null|string $options The options string (format: "value1|Label 1\nvalue2|Label 2")
     */
    private function setOptions(null|string $options): void
    {
        if (empty($options)) {
            return;
        }

        $this->options = collect(explode("\n", $options))
            ->mapWithKeys(function (string $item): array {
                $item = explode('|', $item);

                return [$item[0] => $item[1]];
            });
    }

    /**
     * Get the corresponding Filament form field component.
     *
     * @return Field The Filament form field component
     */
    public function getFilamentField(): Field
    {
        $field = match ($this->type) {
            InputTypeEnum::CHECKBOX => Checkbox::make($this->name),
            InputTypeEnum::COLOR => ColorPicker::make($this->name),
            InputTypeEnum::DATE => DatePicker::make($this->name),
            InputTypeEnum::EMAIL => TextInput::make($this->name)
                ->email(),
            InputTypeEnum::FILE => FileUpload::make($this->name),
            InputTypeEnum::HIDDEN => Hidden::make($this->name),
            InputTypeEnum::MONTH => Select::make($this->name)
                ->options([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]),
            InputTypeEnum::NUMBER => TextInput::make($this->name)
                ->numeric(),
            InputTypeEnum::RADIO => Radio::make($this->name)
                ->options($this->options),
            InputTypeEnum::TEL => TextInput::make($this->name)
                ->tel(),
            InputTypeEnum::TEXT => TextInput::make($this->name),
            InputTypeEnum::TIME => TimePicker::make($this->name),
            InputTypeEnum::SELECT => Select::make($this->name)
                ->options($this->options),
            InputTypeEnum::TEXTAREA => Textarea::make($this->name),
        };

        $field
            ->label(__($this->label))
            ->hint(__($this->hint))
            ->required($this->required);

        if (method_exists($field, 'placeholder')) {
            $field->placeholder(__($this->placeholder));
        }

        return $field;
    }
}
