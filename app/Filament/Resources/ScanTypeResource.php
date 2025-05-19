<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScanTypeResource\Pages;
use App\Models\ScanType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ScanTypeResource extends Resource
{
    protected static ?string $model = ScanType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';

    public static function getModelLabel(): string
    {
        return 'Scan Type';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Scan Type';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                TextInput::make('name')
                    ->label('Scan Type Name')
                    ->required(),

                Repeater::make('scans')
                    ->label('Scans')
                    ->relationship() // This auto-connects with the `scans()` relationship in the model
                    ->schema([
                        TextInput::make('name')
                            ->label('Scan Name')
                            ->required(),
                    ])
                    ->minItems(1)
                    ->createItemButtonLabel('Add Scan')
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Scan Type Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label("Created On")->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label("Updated On")->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function view(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('name')
                ->label('Scan Type Name'),

            RepeatableEntry::make('scans')
                ->label('Scans')
                ->relationship('scans')
                ->schema([
                    TextEntry::make('name')->label('Scan Name'),
                ])
                ->columns(1),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            // You can add relation managers here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScanTypes::route('/'),
            'create' => Pages\CreateScanType::route('/create'),
            'edit' => Pages\EditScanType::route('/{record}/edit'),
            'view' => Pages\ViewScanType::route('/{record}'),
        ];
    }
}
