<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScanTypeResource\Pages;
use App\Filament\Resources\ScanTypeResource\RelationManagers;
use App\Models\ScanType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\ViewRecord;
use Filament\Forms\Components\Card;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Infolist;

class ScanTypeResource extends Resource
{
    protected static ?string $model = ScanType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';
    protected static string $relationship = "scans";
    public static function getModelLabel(): string
    {
        return 'Scan Type'; // Shown on tab, breadcrumb, etc.
    }

    public static function getPluralModelLabel(): string
    {
        return 'Scan Type'; // Shown in list view tab title
    }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('name')
                ->label('Scan Type Name')
                ->required(),

            Repeater::make('scans')
                ->label('Scans')
                ->relationship() // auto binds to hasMany('scans')
                ->schema([
                    TextInput::make('name')->required()->label('Scan Name'),
                ])
                ->minItems(1) // ✅ This ensures at least one scan is added
                ->createItemButtonLabel('Add Scan')
                ->required(),
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
    public static function view(Infolist $infolist): Infolist
{
    return $infolist->schema([
        TextEntry::make('name')
            ->label('Scan Type'),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScanTypes::route('/'),
            'create' => Pages\CreateScanType::route('/create'),
            'edit' => Pages\EditScanType::route('/{record}/edit'),
        ];
    }
}
