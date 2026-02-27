<?php

namespace App\Filament\Widgets;

use App\Models\Muzakki;
use App\Models\Mustahik;
use App\Models\ZakatPayment;
use App\Models\SedekahPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Zakat Terkumpul (hanya yang lunas)
        $totalZakat = ZakatPayment::where('status', 'lunas')
            ->sum('total_bayar');
        
        // Total Sedekah Terkumpul (hanya yang diterima)
        $totalSedekah = SedekahPayment::where('status', 'diterima')
            ->sum('nominal');
        
        // Total Keseluruhan
        $totalKeseluruhan = $totalZakat + $totalSedekah;
        
        // Jumlah Muzakki Aktif
        $jumlahMuzakki = Muzakki::where('status', 'aktif')->count();
        
        // Jumlah Mustahik Aktif
        $jumlahMustahik = Mustahik::where('status', 'aktif')->count();
        
        // Transaksi Bulan Ini
        $transaksiBulanIni = ZakatPayment::whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->where('status', 'lunas')
            ->count();

        return [
            Stat::make('Total Dana Terkumpul', 'Rp ' . number_format($totalKeseluruhan, 0, ',', '.'))
                ->description('Zakat + Sedekah')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            
            Stat::make('Total Zakat Fitrah', 'Rp ' . number_format($totalZakat, 0, ',', '.'))
                ->description('Dana zakat fitrah terkumpul')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
            
            Stat::make('Total Sedekah', 'Rp ' . number_format($totalSedekah, 0, ',', '.'))
                ->description('Dana sedekah terkumpul')
                ->descriptionIcon('heroicon-m-heart')
                ->color('warning'),
            
            Stat::make('Jumlah Muzakki', $jumlahMuzakki)
                ->description('Pembayar zakat terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make('Jumlah Mustahik', $jumlahMustahik)
                ->description('Penerima zakat terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            
            Stat::make('Transaksi Bulan Ini', $transaksiBulanIni)
                ->description('Total pembayaran bulan ' . now()->format('F'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}