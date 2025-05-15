<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsappLogResource\Pages;
use App\Models\WhatsappLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WhatsappLogResource extends Resource
{
    protected static ?string $model = WhatsappLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]); // No form schema needed
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('from'),
                Tables\Columns\TextColumn::make('to'),
                Tables\Columns\TextColumn::make('message_type')->label('Message Type'),
                Tables\Columns\TextColumn::make('message')->limit(50),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('date')->label('Message Date'),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([]) // No row actions
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]), // No bulk actions
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWhatsappLogs::route('/'),
        ];
    }
}
