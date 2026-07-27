<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

/**
 * Slug input component with automatic generation from source field.
 *
 * Provides functionality to generate and refresh slugs based on a source column,
 * similar to TYPO3 CMS slug handling.
 */
class SlugInput extends TextInput
{
    /**
     * @var string Table to generate the slug on
     */
    protected string $table;

    /**
     * @var string Column to generate the slug from
     */
    protected string $sourceColumn = 'title';

    public static function make(string|null $name = 'slug'): static
    {
        return parent::make($name);
    }

    /**
     * Set the table for slug uniqueness.
     *
     * @param string $table The table name
     * @return $this
     */
    public function table(string $table): static
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Set the source column for slug generation.
     *
     * @param string $sourceColumn The source column name, defaults to 'title'
     * @return $this
     */
    public function sourceColumn(string $sourceColumn = 'title'): static
    {
        $this->sourceColumn = $sourceColumn;

        return $this;
    }

    /**
     * Set up the component with default configuration.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->unique(ignoreRecord: true)
            ->prefix(config('app.url') . '/')
            ->suffixAction(
                Action::make('refreshSlugs')
                    ->icon('heroicon-o-arrow-path')
                    ->tooltip(__('Refresh slug'))
                    ->requiresConfirmation()
                    ->action(
                        fn (Set $set, Get $get): mixed => $set(
                            path: $this->name,
                            state: Str::slug($get($this->sourceColumn))
                        )
                    )
            )
            ->required()
            ->unique(
                table: fn (): string => $this->table,
                ignoreRecord: true
            )
            ->string()
            ->alphaDash();
    }

    /**
     * Generate slug when state of sourceField changed.
     *
     * @return Closure The handler function for slug generation
     */
    public static function getSlugHandlerFunction(): Closure
    {
        return function (Get $get, Set $set, null|string $state, string $column = 'slug'): string {
            if (empty($get($column))) {
                $set($column, Str::slug($state));
            }

            return $get($column);
        };
    }
}
