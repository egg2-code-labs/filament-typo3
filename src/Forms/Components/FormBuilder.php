<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use Closure;
use Egg2CodeLabs\FilamentTypo3\Enums\InputTypeEnum;
use Exception;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

/**
 * Form builder component for creating dynamic forms.
 *
 * Provides a builder interface for creating form fields with type-specific configurations,
 * similar to TYPO3 CMS form builder functionality.
 */
class FormBuilder extends Builder
{
    /**
     * Build the block schema for common and type-specific fields.
     *
     * @param array|Closure $fields Type-specific fields to include
     * @return array<mixed> The fieldset schema
     */
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
     * Set up the component with child components for different field types.
     *
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
     * Override schema method to prevent usage.
     *
     * @param array|Closure $components The components to add
     * @return $this
     * @throws Exception Always throws an exception as this method is not supported
     */
    public function schema(array|Closure $components): static
    {
        throw new Exception('This method is not supported on the FormBuilder');
    }

    /**
     * Override blocks method to prevent usage.
     *
     * @param array|Closure $blocks The blocks to add
     * @return $this
     * @throws Exception Always throws an exception as this method is not supported
     */
    public function blocks(array|Closure $blocks): static
    {
        throw new Exception('This method is not supported on the FormBuilder');
    }
}
