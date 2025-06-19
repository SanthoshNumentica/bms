<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getModelLabel(): string
    {
        return 'Payment';  // Shown on tab, breadcrumb, etc.
    }

    public static function getPluralModelLabel(): string
    {
        return 'Payment';  // Shown in list view tab title
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\Select::make('patient_fk_id')
                                ->label('Patient')
                                ->placeholder('Select Patient')
                                ->relationship('patient', 'name')
                                ->searchable()
                                ->required()
                                ->preload(),

                            Forms\Components\Select::make('created_by')
                                ->label('Created By')
                                ->placeholder('Select creater')
                                ->relationship('user', 'name') // assumes "creator()" relationship in model
                                ->searchable()
                                ->required()
                                ->preload(),

                            Forms\Components\DatePicker::make('payment_date')
                                ->label('Payment Date')
                                ->nullable()
                                ->required(),
                            Forms\Components\TextInput::make('amount')
                                ->label('Amount')
                                ->numeric()
                                ->required()
                                ->minValue(0),
                            Forms\Components\Textarea::make('description')
                                ->label('Description')
                                ->rows(3)
                                ->nullable(),
                        ]),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayments::route('/create'),
            'edit' => Pages\EditPayments::route('/{record}/edit'),
        ];
    }
}
