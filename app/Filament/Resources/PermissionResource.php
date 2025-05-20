<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;

use Filament\Tables\Table;
use Filament\Resources\Resource;

use Filament\Navigation\MenuItem;
use Filament\Forms\Components\Card;
use Filament\Navigation\NavigationItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use Filament\Forms\Components\Section;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
     public static function shouldRegisterNavigation(): bool
{
    return false;
}
    // Navication Order
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Settings';




    // protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form

        ->schema([

                // Card Design
                Section::make()
                ->schema([
                            TextInput::make('name')
                            ->minLength(2)
                            ->maxLength(255)
                            ->required()
                            ->unique(ignoreRecord: true)
                        ])
                    // Card Design End
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d-m-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime('d-m-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->visible(auth()->user()->can('Permission Read')),
                Tables\Actions\EditAction::make()
                ->visible(auth()->user()->can('Permission Edit')),
                Tables\Actions\DeleteAction::make()
                ->visible(auth()->user()->can('Permission Delete')),
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        if(!auth()->user()->can('Permission Create'))
        {
            return false;
        }
        else
        {
            return TRUE;
        }

    }


}
