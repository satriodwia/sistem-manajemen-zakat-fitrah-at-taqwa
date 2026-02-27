<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Models\ZakatPayment;
use App\Models\SedekahPayment;
use Illuminate\Support\Collection;

class LaporanKeuangan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.laporan-keuangan';
    
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    
    protected static ?string $title = 'Laporan Keuangan (Buku Besar)';
    
    protected static ?int $navigationSort = 6;
    
    protected static ?string $navigationGroup = 'Laporan';

    public ?string $startDate = null;
    public ?string $endDate = null;
    public $transactions = [];
    public $totalDebit = 0;
    public $totalKredit = 0;
    public $saldo = 0;

    public function mount(): void
    {
        // Set default periode (bulan ini)
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        
        $this->loadTransactions();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('startDate')
                    ->label('Dari Tanggal')
                    ->native(false)
                    ->default(now()->startOfMonth()),
                
                DatePicker::make('endDate')
                    ->label('Sampai Tanggal')
                    ->native(false)
                    ->default(now()->endOfMonth()),
            ])
            ->columns(2);
    }

    public function applyFilter(): void
    {
        $this->loadTransactions();
    }

    public function loadTransactions(): void
    {
        $transactions = new Collection();

        // Ambil data Zakat
        $zakatQuery = ZakatPayment::with(['muzakki', 'paymentMethod'])
            ->where('status', 'lunas');

        if ($this->startDate) {
            $zakatQuery->whereDate('tanggal_bayar', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $zakatQuery->whereDate('tanggal_bayar', '<=', $this->endDate);
        }

        $zakatPayments = $zakatQuery->get()->map(function ($payment) {
            return [
                'tanggal' => $payment->tanggal_bayar,
                'kode' => $payment->kode_transaksi,
                'kategori' => 'Zakat Fitrah',
                'nama' => $payment->muzakki->nama_lengkap,
                'keterangan' => $payment->jumlah_jiwa . ' jiwa - ' . ucfirst($payment->jenis_bayar),
                'metode' => $payment->paymentMethod->nama_metode,
                'debit' => $payment->total_bayar,
                'kredit' => 0,
            ];
        });

        // Ambil data Sedekah
        $sedekahQuery = SedekahPayment::with(['paymentMethod'])
            ->where('status', 'diterima');

        if ($this->startDate) {
            $sedekahQuery->whereDate('tanggal_donasi', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $sedekahQuery->whereDate('tanggal_donasi', '<=', $this->endDate);
        }

        $sedekahPayments = $sedekahQuery->get()->map(function ($payment) {
            return [
                'tanggal' => $payment->tanggal_donasi,
                'kode' => $payment->kode_transaksi,
                'kategori' => 'Sedekah',
                'nama' => $payment->display_name,
                'keterangan' => $payment->catatan ?? '-',
                'metode' => $payment->paymentMethod->nama_metode,
                'debit' => $payment->nominal,
                'kredit' => 0,
            ];
        });

        // Gabungkan dan urutkan berdasarkan tanggal
        $this->transactions = $zakatPayments->concat($sedekahPayments)
            ->sortBy('tanggal')
            ->values()
            ->toArray();

        // Hitung total
        $this->totalDebit = array_sum(array_column($this->transactions, 'debit'));
        $this->totalKredit = array_sum(array_column($this->transactions, 'kredit'));
        $this->saldo = $this->totalDebit - $this->totalKredit;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->url(fn () => route('laporan.keuangan.excel', [
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                ])),
            
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-text')
                ->color('danger')
                ->url(fn () => route('laporan.pdf', [
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                ])),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('view laporan keuangan');
    }
}