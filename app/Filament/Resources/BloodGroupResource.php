<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloodGroupResource\Pages;
use App\Filament\Resources\BloodGroupResource\RelationManagers;
use App\Models\BloodGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BloodGroupResource extends Resource
{
    protected static ?string $model = BloodGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';
    public static function getModelLabel(): string
    {
        return 'Blood Group'; // Shown on tab, breadcrumb, etc.
    }

    public static function getPluralModelLabel(): string
    {
        return 'Blood Group'; // Shown in list view tab title
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                Forms\Components\TextInput::make('name')
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
                Tables\Columns\TextColumn::make('name')->searchable()
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
            'index' => Pages\ListBloodGroups::route('/'),
            'create' => Pages\CreateBloodGroup::route('/create'),
            'edit' => Pages\EditBloodGroup::route('/{record}/edit'),
        ];
    }
}
