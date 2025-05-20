<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenderResource\Pages;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Navigation\NavigationItem;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\Gender;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GenderResource extends Resource
{
    protected static ?string $model = Gender::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';
    public static function getModelLabel(): string
    {
        return 'Gender'; // Shown on tab, breadcrumb, etc.
    }
    public static function shouldRegisterNavigation(): bool
{
    return false;
}

    public static function getPluralModelLabel(): string
    {
        return 'Gender'; // Shown in list view tab title
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                  Section::make()->schema([
                Forms\Components\TextInput::make('gender_name')
                    ->required()
                    ->maxLength(255),
                  ])
            ]
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender_name')->searchable()
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListGenders::route('/'),
            'create' => Pages\CreateGender::route('/create'),
            'edit' => Pages\EditGender::route('/{record}/edit'),
        ];
    }
}
