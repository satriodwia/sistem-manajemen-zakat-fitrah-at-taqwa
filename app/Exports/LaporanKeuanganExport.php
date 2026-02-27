<?php

namespace App\Exports;

use App\Models\ZakatPayment;
use App\Models\SedekahPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
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
        $transactions = $zakatPayments->concat($sedekahPayments)
            ->sortBy('tanggal')
            ->values();

        return $transactions;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kode Transaksi',
            'Kategori',
            'Nama',
            'Keterangan',
            'Metode Pembayaran',
            'Debit (Masuk)',
            'Kredit (Keluar)',
            'Saldo',
        ];
    }

    public function map($transaction): array
    {
        static $no = 0;
        static $saldo = 0;
        $no++;

        $saldo += $transaction['debit'] - $transaction['kredit'];

        return [
            $no,
            $transaction['tanggal']->format('d/m/Y'),
            $transaction['kode'],
            $transaction['kategori'],
            $transaction['nama'],
            $transaction['keterangan'],
            $transaction['metode'],
            number_format($transaction['debit'], 0, ',', '.'),
            number_format($transaction['kredit'], 0, ',', '.'),
            number_format($saldo, 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0891B2']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Keuangan';
    }
}