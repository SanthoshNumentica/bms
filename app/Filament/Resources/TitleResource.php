<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TitleResource\Pages;
use App\Filament\Resources\TitleResource\RelationManagers;
use App\Models\Title;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TitleResource extends Resource
{
    protected static ?string $model = Title::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';
    public static function getModelLabel(): string
    {
        return 'Title'; // Shown on tab, breadcrumb, etc.
    }

    public static function getPluralModelLabel(): string
    {
        return 'Title'; // Shown in list view tab title
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                Forms\Components\TextInput::make('title_name')
                    ->required()
                    ->maxLength(255),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title_name')->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label("Created On")->searchable()
                    ->searchable(),
                 Tables\Columns\TextColumn::make('updated_at')->label("Updated On")->searchable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTitles::route('/'),
            'create' => Pages\CreateTitle::route('/create'),
            'edit' => Pages\EditTitle::route('/{record}/edit'),
        ];
    }
}
