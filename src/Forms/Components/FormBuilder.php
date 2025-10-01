<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use Closure;
use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\InputTypeEnum;
use Exception;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

/**
 * TODO: it would be really handy to define an object that represents an input
 *       field with a well-defined public API so that we can type-hint everything
 *       properly and are sure that wherever we use the values of those fields
 *       we have a standardized API and validation.
 */
class FormBuilder extends Builder
{
    private function buildBlockSchema(array|Closure $fields): array
    {
        return [
            Fieldset::make(__('Common fields'))
                ->columns(2)
                ->columnSpan(1)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->alphaDash()
                        ->columnSpan(1),
                    Checkbox::make('required')
                        ->inline(false)
                        ->columnSpan(1),
                    TextInput::make('label')
                        ->label(__('Label'))
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('hint')
                        ->label(__('Hint / Description'))
                        ->columnSpan(2),
                ]),
            Fieldset::make(__('Type specific fields'))
                ->columns(1)
                ->columnSpan(1)
                ->schema($fields)
        ];
    }

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->childComponents([
            /**
             * TEXT BASED INPUTS, e.g. text, email, tel, ...
             */
            Block::make(__('Input'))
                ->schema(
                    $this->buildBlockSchema([
                        Select::make('type')
                            ->required()
                            ->options(
                                InputTypeEnum::inputs()->mapWithKeys(
                                    fn (InputTypeEnum $type): array => [
                                        $type->name => $type->value
                                    ]
                                )
                            ),
                        TextInput::make('placeholder'),
                        TextInput::make('value'),
                    ])
                )
                ->label(fn (null|array $state): null|string => $state['name'] ?? null)
                ->columns(2),
            /**
             * SELECT, CHECKBOX, RADIO
             */
            Block::make(__('Selection'))
                ->schema(
                    $this->buildBlockSchema([
                        Select::make('type')
                            ->required()
                            ->options(
                                InputTypeEnum::choices()->mapWithKeys(
                                    fn (InputTypeEnum $type): array => [
                                        $type->name => $type->value
                                    ]
                                )
                            ),
                        Textarea::make('options')
                            ->rows(5)
                            ->hint(__('One option per line in the format: value|Label'))
                            ->placeholder(__("Example:\nvalue1|Option 1\nvalue2|Option 2"))
                    ])
                )
                ->label(fn (null|array $state): null|string => $state['name'] ?? null)
                ->columns(2),
        ]);
    }

    /**
     * @throws Exception
     */
    public function schema(array|Closure $components): static
    {
        throw new Exception('This method is not supported on the FormBuilder');
    }

    /**
     * @throws Exception
     */
    public function blocks(array|Closure $blocks): static
    {
        throw new Exception('This method is not supported on the FormBuilder');
    }
}
