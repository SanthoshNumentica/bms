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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Livewire\Notifications;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

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
                        Grid::make(4)
                            ->schema([
                                Forms\Components\Select::make('patient_fk_id')
                                    ->label('Patient')
                                    ->placeholder('Select a Patient')
                                    ->relationship('patient', 'name')
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name}-{$record->patient_id} ({$record->mobile_no})")
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('doc_ref_fk_id')
                                    ->label('Referred Doctor')
                                    ->placeholder('Select a Doctor')
                                    ->relationship('doctor', 'name')
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name}-{$record->doctor_id} ({$record->mobile_no})")
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->maxLength(255),

                                Textarea::make('remarks')
                                    ->label('Remarks')
                                    ->maxLength(255),

                                TextInput::make('case_id')
                                    ->label('Case ID')
                                    ->visibleOn('view')
                                    ->disabled(),
                            ]),

                        Repeater::make('items')
                            ->relationship()
                            ->label('Case Report Items')
                            ->schema([
                                Select::make('scan_type_id')
                                    ->label('Scan Type')
                                    ->relationship('scanType', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Select::make('scan_id')
                                    ->label('Scan')
                                    ->relationship('scan', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                FileUpload::make('documents')
                                    ->label('Documents')
                                    ->multiple()
                                    ->reorderable()
                                    ->preserveFilenames()
                                    ->directory('case-report-documents'),
                            ])
                            ->columns(3)
                            ->createItemButtonLabel('Add Scan'),
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
                    'Completed' => 'success',
                    'Pending' => 'danger',
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
                Tables\Actions\Action::make('sendWhatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Send WhatsApp')
                    ->modalDescription('Are you sure you want to send the WhatsApp report message to the patient?')
                    ->modalSubmitActionLabel('Send')
                    ->modalCancelActionLabel('Cancel')
                    ->action(function ($record, $livewire) {
                        $livewire->dispatchBrowserEvent('whatsapp-loading-start');
                        try {
                            Http::post(route('send.whatsapp', $record->id));
                            Notifications::make()
                                ->title('WhatsApp message sent successfully.')
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notifications::make()
                                ->title('Failed to send WhatsApp message.')
                                ->danger()
                                ->send();
                        }
                        $livewire->dispatchBrowserEvent('whatsapp-loading-stop');
                    }),
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
            'view' => Pages\ViewCaseReport::route('/{record}/view'),
        ];
    }
}
