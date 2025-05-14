<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    public static function getModelLabel(): string
    {
        return 'Patient'; // Shown on tab, breadcrumb, etc.
    }

    public static function getPluralModelLabel(): string
    {
        return 'Patient'; // Shown in list view tab title
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(4)->schema([
                            Forms\Components\Select::make('title_fk_id')
                                ->label('Title')
                                ->placeholder('Select a title')
                                ->relationship('title', 'title_name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            Forms\Components\TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('father_name')
                                ->label('Father Name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email_id')
                                ->label('Email')
                                ->required()
                                ->email(),
                        ]),
                        Grid::make(4)->schema([
                            Forms\Components\DatePicker::make('dob')
                                ->label('Date Of Birth')
                                ->required(),
                            Forms\Components\TextInput::make('mobile_no')
                                ->label('Phone Number')
                                ->required()
                                ->maxLength(15),
                            Forms\Components\TextInput::make('whatsapp_no')
                                ->label('Whatsapp Number')
                                ->maxLength(15),
                            Forms\Components\select::make('gender_fk_id')
                                ->label('Gender')
                                ->placeholder('Select Gender')
                                ->relationship('gender', 'gender_name')
                                ->required()
                                ->searchable()
                                ->preload(),
                        ]),
                        Grid::make(4)->schema([

                            Forms\Components\select::make('blood_group_fk_id')
                                ->label('Blood Group')
                                ->placeholder('Select Blood Group')
                                ->relationship('blood_group', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            Forms\Components\TextInput::make('address')
                                ->label('Address')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('street')
                                ->label('Street')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('pincode')
                                ->label('Pin Cdde')
                                ->required()
                                ->maxLength(255),
                        ]),
                        Grid::make(4)->schema([

                            Forms\Components\TextInput::make('city')
                                ->label('City')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('remarks')
                                ->label('Remarks')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('patient_id')
                                ->label('Patient ID')
                                ->visibleOn('view')
                                ->disabled()
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')->label("Name")->formatStateUsing(fn($state, $record) => optional($record->title)->title_name . '. ' . $record->name)->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('patient_id')->label("Patient ID")->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile_no')->label("Mobile No")->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_id')->label("Email ID")->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender.gender_name')->label("Gender")->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dob')->label("DOB")->date('d-m-Y')->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')->label("Address")->formatStateUsing(fn($state, $record) => $record->address . ', ' . $record->city)->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->label("Updated On")->searchable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('dob', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('dob', '<=', $data['until']));
                    }),

            ])
            ->actions([
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make()->icon('heroicon-o-trash'),
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
