<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\InputTypeEnum;
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

class FormField
{
    public InputTypeEnum $type;

    public null|string $name;

    public null|string $label;

    public null|string $hint;

    public bool $required;

    public null|string $placeholder;

    public null|string $value;

    public null|Collection $options = null;

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

    private function setOptions(null|string $options): void
    {
        if (empty($options)) {
            return;
        }

        $this->options = collect(explode("\n", $options))
            ->mapWithKeys(function (string $item, int $key): array {
                $item = explode('|', $item);

                return [$item[0] => $item[1]];
            });
    }

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
                // TODO: Does this make sense?
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
