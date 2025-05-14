<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CaseReportResource\Pages;
use App\Models\CaseReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;

class CaseReportResource extends Resource
{
    protected static ?string $model = CaseReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(4)->schema([
                            Forms\Components\Select::make('patient_fk_id')
                                ->label('Select a Patient')
                                ->placeholder('Select a Patient')
                                ->relationship('patient', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),

                            Forms\Components\Select::make('doc_ref_fk_id')
                                ->label('Select a Doctor')
                                ->placeholder('Select a Doctor')
                                ->relationship('doctor', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),

                            Textarea::make('description')
                                ->label('Description')
                                ->required()
                                ->maxLength(255),

                            Textarea::make('remarks')
                                ->label('Remarks')
                                ->required()
                                ->maxLength(255),

                            FileUpload::make('documents')
                                ->label('Documents')
                                ->multiple()
                                ->directory('documents')
                                ->preserveFilenames()
                                ->nullable()
                                ->disk('public')
                                ->helperText('Upload your documents (optional)')
                                ->columnSpan('full')
                                ->enableOpen(),

                            Forms\Components\TextInput::make('case_id')
                                ->label('Case ID')
                                ->visibleOn('view')
                                ->disabled(),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Id'),
                Tables\Columns\TextColumn::make('case_id')->label('Case ID'),
                Tables\Columns\TextColumn::make('patient.name')->label('Patient'),
                Tables\Columns\TextColumn::make('doctor.name')->label('Doctor'),
                Tables\Columns\TextColumn::make('description')->limit(30),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'success' => 'closed',
                    'danger' => 'pending',
                ]),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCaseReports::route('/'),
            'create' => Pages\CreateCaseReport::route('/create'),
            'edit' => Pages\EditCaseReport::route('/{record}/edit'),
        ];
    }
    
}
