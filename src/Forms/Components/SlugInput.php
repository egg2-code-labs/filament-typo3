<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;

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

    public static function make(string $name = 'slug'): static
    {
        return parent::make($name);
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function table(string $table): static
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param string $sourceColumn
     *
     * @return SlugInput
     */
    public function sourceColumn(string $sourceColumn = 'title'): static
    {
        $this->sourceColumn = $sourceColumn;

        return $this;
    }

    /**
     * @return void
     *
     * TODO: Slug generation function should also take page path into account
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->unique(ignoreRecord: true)
            ->prefix(config('app.url').'/')
            ->suffixAction(
                Action::make('refreshSlugs')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(fn (Set $set, Get $get, mixed $state) => $set('slug', Str::slug($get($this->sourceColumn))))
            )
            ->required()
            ->unique(
                table: fn () => $this->table,
                ignoreRecord: true
            )
            ->string()
            ->alphaDash();
    }

    /**
     * Generate slug when state of sourceField changed
     *
     * @return Closure
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
