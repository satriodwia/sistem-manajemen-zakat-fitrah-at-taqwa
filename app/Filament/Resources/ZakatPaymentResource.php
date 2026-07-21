<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZakatPaymentResource\Pages;
use App\Models\ZakatPayment;
use App\Models\Muzakki;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ZakatPaymentResource extends Resource
{
    protected static ?string $model = ZakatPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Pembayaran Zakat';
    
    protected static ?string $modelLabel = 'Pembayaran Zakat';
    
    protected static ?string $pluralModelLabel = 'Pembayaran Zakat';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Muzakki')
                    ->schema([
                        Forms\Components\Select::make('muzakki_id')
                            ->label('Pilih Muzakki')
                            ->relationship('muzakki', 'nama_lengkap')
                            ->searchable(['nama_lengkap', 'nik'])
                            ->required()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_lengkap')
                                    ->required()
                                    ->label('Nama Lengkap'),
                                Forms\Components\TextInput::make('nik')
                                    ->required()
                                    ->label('NIK')
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('no_telepon')
                                    ->required()
                                    ->label('No. Telepon'),
                                Forms\Components\Textarea::make('alamat')
                                    ->required()
                                    ->label('Alamat'),
                                Forms\Components\TextInput::make('jumlah_tanggungan')
                                    ->numeric()
                                    ->default(1)
                                    ->label('Jumlah Tanggungan'),
                            ])
                            ->helperText('Pilih muzakki atau tambah baru'),
                    ]),
                
                Forms\Components\Section::make('Detail Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('jenis_bayar')
                            ->options([
                                'uang' => 'Uang (Tunai/Transfer)',
                                'beras' => 'Beras',
                            ])
                            ->default('uang')
                            ->required()
                            ->live()
                            ->label('Jenis Pembayaran'),
                        
                        Forms\Components\TextInput::make('jumlah_jiwa')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $jumlahJiwa = $get('jumlah_jiwa') ?? 1;
                                $nominalPerJiwa = $get('nominal_per_jiwa') ?? 0;
                                $set('total_bayar', $jumlahJiwa * $nominalPerJiwa);
                            })
                            ->label('Jumlah Jiwa')
                            ->helperText('Jumlah jiwa yang dibayarkan zakatnya'),
                        
                        Forms\Components\TextInput::make('nominal_per_jiwa')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->default(35000)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $jumlahJiwa = $get('jumlah_jiwa') ?? 1;
                                $nominalPerJiwa = $get('nominal_per_jiwa') ?? 0;
                                $set('total_bayar', $jumlahJiwa * $nominalPerJiwa);
                            })
                            ->label('Nominal Per Jiwa')
                            ->helperText('Harga beras/uang per jiwa (standar: Rp 35.000)'),
                        
                        Forms\Components\TextInput::make('total_bayar')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->label('Total Bayar')
                            ->helperText('Otomatis dihitung dari jumlah jiwa x nominal per jiwa'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Metode & Status')
                    ->schema([
                        Forms\Components\Select::make('payment_method_id')
                            ->label('Metode Pembayaran')
                            ->relationship('paymentMethod', 'nama_metode')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->helperText('Pilih metode pembayaran'),
                        
                        Forms\Components\DatePicker::make('tanggal_bayar')
                            ->required()
                            ->default(now())
                            ->label('Tanggal Bayar')
                            ->native(false),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending (Menunggu Pembayaran)',
                                'waiting' => 'Waiting (Sedang Diproses)',
                                'lunas' => 'Lunas (Sudah Dibayar)',
                                'expired' => 'Expired (Kadaluarsa)',
                                'batal' => 'Batal (Dibatalkan)',
                            ])
                            ->default('pending')
                            ->required()
                            ->label('Status Pembayaran'),
                        
                        Forms\Components\Textarea::make('catatan')
                            ->rows(3)
                            ->label('Catatan')
                            ->columnSpanFull(),
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
                
                Tables\Columns\TextColumn::make('muzakki.nama_lengkap')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Muzakki'),
                
                Tables\Columns\TextColumn::make('jumlah_jiwa')
                    ->numeric()
                    ->label('Jiwa')
                    ->suffix(' jiwa')
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('total_bayar')
                    ->money('IDR')
                    ->sortable()
                    ->label('Total Bayar'),
                
                Tables\Columns\BadgeColumn::make('jenis_bayar')
                    ->colors([
                        'success' => 'uang',
                        'warning' => 'beras',
                    ])
                    ->label('Jenis'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'waiting',
                        'success' => 'lunas',
                        'danger' => 'expired',
                        'secondary' => 'batal',
                    ])
                    ->label('Status'),
                
                Tables\Columns\TextColumn::make('tanggal_bayar')
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
                        'waiting' => 'Waiting',
                        'lunas' => 'Lunas',
                        'expired' => 'Expired',
                        'batal' => 'Batal',
                    ]),
                
                Tables\Filters\SelectFilter::make('jenis_bayar')
                    ->options([
                        'uang' => 'Uang',
                        'beras' => 'Beras',
                    ]),
                
                Tables\Filters\Filter::make('tanggal_bayar')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari_tanggal'], fn ($q) => $q->whereDate('tanggal_bayar', '>=', $data['dari_tanggal']))
                            ->when($data['sampai_tanggal'], fn ($q) => $q->whereDate('tanggal_bayar', '<=', $data['sampai_tanggal']));
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
                                'waiting' => 'Waiting',
                                'lunas' => 'Lunas',
                                'expired' => 'Expired',
                                'batal' => 'Batal',
                            ])
                            ->placeholder('Semua Status'),
                    ])
                    ->action(function (array $data) {
                        return redirect()->route('laporan.zakat.excel', $data);
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak Bukti')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status == 'lunas')
                    ->url(fn ($record) => route('print.zakat', $record->id))
                    ->openUrlInNewTab(),
                
                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'waiting']))
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran')
                    ->modalDescription(fn ($record) => "Konfirmasi pembayaran dari {$record->muzakki->nama_lengkap} sebesar Rp " . number_format($record->total_bayar, 0, ',', '.') . "?")
                    ->modalSubmitActionLabel('Ya, Konfirmasi')
                    ->action(function ($record) {
                        try {
                            $record->update([
                                'status' => 'lunas',
                                'paid_at' => now(),
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Pembayaran Dikonfirmasi!')
                                ->body("Pembayaran {$record->kode_transaksi} telah dikonfirmasi sebagai LUNAS")
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
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'waiting']))
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin menolak pembayaran ini?')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->action(function ($record) {
                        try {
                            $record->update([
                                'status' => 'batal',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Pembayaran Ditolak')
                                ->body("Pembayaran {$record->kode_transaksi} telah ditolak")
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
                                
                                $record->update([
                                    'status' => $newStatus,
                                    'midtrans_transaction_id' => $result['transaction_id'],
                                    'payment_type' => $result['payment_type'],
                                    'paid_at' => in_array($newStatus, ['lunas']) ? now() : null,
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
            'index' => Pages\ListZakatPayments::route('/'),
            'create' => Pages\CreateZakatPayment::route('/create'),
            'edit' => Pages\EditZakatPayment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view zakat payments');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create zakat payments');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('edit zakat payments');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete zakat payments');
    }
}