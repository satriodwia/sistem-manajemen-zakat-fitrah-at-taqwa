<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SedekahPaymentResource\Pages;
use App\Models\SedekahPayment;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SedekahPaymentResource extends Resource
{
    protected static ?string $model = SedekahPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    
    protected static ?string $navigationLabel = 'Sedekah';
    
    protected static ?string $modelLabel = 'Sedekah';
    
    protected static ?string $pluralModelLabel = 'Sedekah';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Donatur')
                    ->schema([
                        Forms\Components\Toggle::make('is_anonim')
                            ->label('Donasi Anonim?')
                            ->live()
                            ->helperText('Jika diaktifkan, nama donatur akan disembunyikan')
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('nama_donatur')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Donatur')
                            ->hidden(fn (Forms\Get $get) => $get('is_anonim'))
                            ->helperText('Kosongkan jika anonim'),
                        
                        Forms\Components\TextInput::make('no_telepon')
                            ->tel()
                            ->maxLength(15)
                            ->label('No. Telepon')
                            ->hidden(fn (Forms\Get $get) => $get('is_anonim')),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->label('Email')
                            ->hidden(fn (Forms\Get $get) => $get('is_anonim')),
                    ])->columns(2),
                
                Forms\Components\Section::make('Detail Donasi')
                    ->schema([
                        Forms\Components\TextInput::make('nominal')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(1000)
                            ->label('Nominal Donasi')
                            ->helperText('Minimal Rp 1.000'),
                        
                        Forms\Components\Select::make('payment_method_id')
                            ->label('Metode Pembayaran')
                            ->relationship('paymentMethod', 'nama_metode')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->helperText('Pilih metode pembayaran'),
                        
                        Forms\Components\DatePicker::make('tanggal_donasi')
                            ->required()
                            ->default(now())
                            ->label('Tanggal Donasi')
                            ->native(false),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending (Menunggu Pembayaran)',
                                'diterima' => 'Diterima (Sudah Dibayar)',
                                'expired' => 'Expired (Kadaluarsa)',
                                'batal' => 'Batal (Dibatalkan)',
                            ])
                            ->default('pending')
                            ->required()
                            ->label('Status Donasi'),
                        
                        Forms\Components\Textarea::make('catatan')
                            ->rows(3)
                            ->label('Catatan / Pesan')
                            ->columnSpanFull()
                            ->helperText('Pesan atau doa dari donatur'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Info Midtrans (Opsional)')
                    ->schema([
                        Forms\Components\TextInput::make('midtrans_order_id')
                            ->label('Midtrans Order ID')
                            ->readOnly()
                            ->helperText('Diisi otomatis saat pembayaran via Midtrans'),
                        
                        Forms\Components\TextInput::make('payment_type')
                            ->label('Tipe Pembayaran')
                            ->readOnly()
                            ->helperText('Contoh: gopay, bank_transfer, credit_card'),
                    ])->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_transaksi')
                    ->searchable()
                    ->sortable()
                    ->label('Kode Transaksi')
                    ->copyable()
                    ->copyMessage('Kode transaksi disalin!'),
                
                Tables\Columns\TextColumn::make('nama_donatur')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Donatur')
                    ->formatStateUsing(fn ($record) => $record->display_name),
                
                Tables\Columns\TextColumn::make('nominal')
                    ->money('IDR')
                    ->sortable()
                    ->label('Nominal'),
                
                Tables\Columns\IconColumn::make('is_anonim')
                    ->boolean()
                    ->label('Anonim')
                    ->alignCenter(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'diterima',
                        'danger' => 'expired',
                        'secondary' => 'batal',
                    ])
                    ->label('Status'),
                
                Tables\Columns\TextColumn::make('tanggal_donasi')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal'),
                
                Tables\Columns\TextColumn::make('paymentMethod.nama_metode')
                    ->label('Metode')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'expired' => 'Expired',
                        'batal' => 'Batal',
                    ])
                    ->default('pending'),
                
                Tables\Filters\TernaryFilter::make('is_anonim')
                    ->label('Anonim')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Anonim')
                    ->falseLabel('Hanya Non-Anonim'),
                
                Tables\Filters\Filter::make('tanggal_donasi')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari_tanggal'], fn ($q) => $q->whereDate('tanggal_donasi', '>=', $data['dari_tanggal']))
                            ->when($data['sampai_tanggal'], fn ($q) => $q->whereDate('tanggal_donasi', '<=', $data['sampai_tanggal']));
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('start_date')
                            ->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('end_date')
                            ->label('Sampai Tanggal'),
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'diterima' => 'Diterima',
                                'expired' => 'Expired',
                                'batal' => 'Batal',
                            ])
                            ->placeholder('Semua Status'),
                    ])
                    ->action(function (array $data) {
                        return redirect()->route('laporan.sedekah.excel', $data);
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak Bukti')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status == 'diterima')
                    ->url(fn ($record) => route('print.sedekah', $record->id))
                    ->openUrlInNewTab(),
                
                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status == 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Penerimaan Sedekah')
                    ->modalDescription(fn ($record) => "Konfirmasi sedekah dari {$record->nama_donatur} sebesar Rp " . number_format($record->nominal, 0, ',', '.') . "?")
                    ->modalSubmitActionLabel('Ya, Konfirmasi')
                    ->action(function ($record) {
                        try {
                            $record->update([
                                'status' => 'diterima',
                                'paid_at' => now(),
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Sedekah Dikonfirmasi!')
                                ->body("Sedekah {$record->kode_transaksi} telah dikonfirmasi sebagai DITERIMA")
                                ->success()
                                ->send();
                                
                            return redirect()->back();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('Terjadi kesalahan: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                
                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status == 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Sedekah')
                    ->modalDescription('Apakah Anda yakin ingin menolak sedekah ini?')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->action(function ($record) {
                        try {
                            $record->update([
                                'status' => 'batal',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Sedekah Ditolak')
                                ->body("Sedekah {$record->kode_transaksi} telah ditolak")
                                ->warning()
                                ->send();
                                
                            return redirect()->back();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('Terjadi kesalahan: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                
                Tables\Actions\Action::make('syncStatus')
                    ->label('Sync Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn ($record) => $record->midtrans_order_id && in_array($record->status, ['pending', 'waiting']))
                    ->requiresConfirmation()
                    ->modalHeading('Sync Status Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin memeriksa status pembayaran ini dari Midtrans?')
                    ->action(function ($record) {
                        try {
                            $midtransService = app(\App\Services\MidtransService::class);
                            
                            $result = $midtransService->checkTransactionStatus($record->midtrans_order_id);
                            
                            if ($result['success']) {
                                $newStatus = $midtransService->determineStatus(
                                    $result['transaction_status'],
                                    $result['fraud_status'] ?? null
                                );
                                
                                // Convert untuk sedekah (lunas -> diterima)
                                if ($newStatus == 'lunas') {
                                    $newStatus = 'diterima';
                                }
                                
                                $record->update([
                                    'status' => $newStatus,
                                    'midtrans_transaction_id' => $result['transaction_id'],
                                    'payment_type' => $result['payment_type'],
                                    'paid_at' => in_array($newStatus, ['diterima']) ? now() : null,
                                ]);
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Status berhasil disinkronkan!')
                                    ->body("Status pembayaran diubah menjadi: " . strtoupper($newStatus))
                                    ->success()
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->title('Gagal sync status')
                                    ->body($result['message'] ?? 'Terjadi kesalahan saat mengecek status')
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('Terjadi kesalahan: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListSedekahPayments::route('/'),
            'create' => Pages\CreateSedekahPayment::route('/create'),
            'edit' => Pages\EditSedekahPayment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view sedekah payments');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create sedekah payments');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit sedekah payments');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete sedekah payments');
    }
}