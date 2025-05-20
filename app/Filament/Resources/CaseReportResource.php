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
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Label;

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
                            ->label('Patient')
                            ->relationship('patient', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}-{$record->patient_id} ({$record->mobile_no})")
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('doc_ref_fk_id')
                            ->label('Referred Doctor')
                            ->relationship('doctor', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}-{$record->doctor_id} ({$record->mobile_no})")
                            ->required()
                            ->searchable()
                            ->preload(),

                        Textarea::make('description')->maxLength(255),
                        Textarea::make('remarks')->maxLength(255),
                        TextInput::make('case_id')->visibleOn('view')->disabled(),
                        Forms\Components\Hidden::make('status')->default('pending'),
                    ]),

                    Repeater::make('items')
                        ->relationship('items')
                        ->label('Scan Reports')
                        ->schema([
                            Select::make('scan_type_id')->relationship('scanType', 'name')->required()->searchable()->preload(),
                            Select::make('scan_id')->relationship('scan', 'name')->required()->searchable()->preload(),
                            Textarea::make('remarks')->maxLength(255),
                            FileUpload::make('documents')->multiple()->reorderable()->Label('Reports')->preserveFilenames()->directory('case-report-documents'),
                        ])
                        ->columns(3)
                        ->createItemButtonLabel('Add Scan')
                        ->saveRelationshipsUsing(function ($state, $record) {
                            $existingItemIds = $record->items()->pluck('id')->toArray();
                            $incomingItemIds = [];
                            $hasDocuments = false;

                            foreach ($state as $itemData) {
                                if (!empty($itemData['id'])) {
                                    // Update existing item
                                    $item = $record->items()->find($itemData['id']);
                                    if ($item) {
                                        $item->update($itemData);
                                        $incomingItemIds[] = $item->id;
                                    }
                                } else {
                                    // Create new item
                                    $item = $record->items()->create($itemData);
                                    $incomingItemIds[] = $item->id;
                                }

                                // Check if this item has documents
                                if (!empty($itemData['documents']) && is_array($itemData['documents']) && count(array_filter($itemData['documents'])) > 0) {
                                    $hasDocuments = true;
                                }

                                Log::info('Scan Report Item Synced', [
                                    'case_report_id' => $record->id,
                                    'item' => $itemData,
                                    'user_id' => auth()->id(),
                                ]);
                            }

                            // Delete items that were removed in the form
                            $itemsToDelete = array_diff($existingItemIds, $incomingItemIds);
                            if (!empty($itemsToDelete)) {
                                $record->items()->whereIn('id', $itemsToDelete)->delete();
                            }

                            // Update case report status based on documents presence
                            $record->status = $hasDocuments ? 'closed' : 'pending';
                            $record->save();

                            \Log::info('Updating Scan Report status', [
                                'case_report_id' => $record->id,
                                'status_to_set' => $record->status,
                            ]);
                        }),
                ]),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Id'),
                Tables\Columns\TextColumn::make('case_id')->label('Scan Report ID'),
                Tables\Columns\TextColumn::make('patient.name')->label('Patient'),
                Tables\Columns\TextColumn::make('patient.mobile_no')->label('Mobile No'),
                Tables\Columns\TextColumn::make('doctor.name')->label('Doctor'),
                Tables\Columns\TextColumn::make('description')->limit(30),
                Tables\Columns\TextColumn::make('status')
    ->label('Status')
    ->badge()
    ->color(fn ($state) => match ($state) {
        'closed' => 'success',   // Green
        'pending' => 'danger',   // Red
        default => 'secondary', // Default gray
    })
    ->formatStateUsing(fn ($state) => $state === 'closed' ? 'Completed' : ucfirst($state)),

                Tables\Columns\TextColumn::make('created_at')->label('Created At')->date(),
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
                        return null;
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
