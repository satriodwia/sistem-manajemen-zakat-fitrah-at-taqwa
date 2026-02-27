<?php

namespace App\Filament\Widgets;

use App\Models\ZakatPayment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPayments extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Transaksi Zakat Terbaru')
            ->query(function () {
                return ZakatPayment::query()
                    ->with(['muzakki', 'paymentMethod'])
                    ->latest()
                    ->limit(10);
            })
            ->columns([
                Tables\Columns\TextColumn::make('kode_transaksi')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('muzakki.nama_lengkap')
                    ->label('Nama Muzakki')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('jumlah_jiwa')
                    ->label('Jiwa')
                    ->suffix(' jiwa')
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('total_bayar')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'waiting',
                        'success' => 'lunas',
                        'danger' => 'expired',
                        'secondary' => 'batal',
                    ]),
                
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (ZakatPayment $record): string => route('filament.admin.resources.zakat-payments.edit', $record)),
            ]);
    }
}