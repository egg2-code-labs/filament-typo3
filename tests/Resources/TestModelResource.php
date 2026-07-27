<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Resources;

use Egg2CodeLabs\FilamentTypo3\Tests\Models\TestModel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestModelResource extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = TestModel::class;

    /**
     * The navigation icon for the resource.
     */
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    /**
     * The navigation label for the resource.
     */
    protected static ?string $navigationLabel = 'Test Models';

    /**
     * Get the table for the resource.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    /**
     * Get the pages for the resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => \Egg2CodeLabs\FilamentTypo3\Tests\Resources\Pages\ListTestModels::route('/'),
            'create' => \Egg2CodeLabs\FilamentTypo3\Tests\Resources\Pages\CreateTestModel::route('/create'),
            'edit' => \Egg2CodeLabs\FilamentTypo3\Tests\Resources\Pages\EditTestModel::route('/{record}/edit'),
        ];
    }
}
