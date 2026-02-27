<?php

namespace App\Exports;

use App\Models\ZakatPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ZakatPaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function collection()
    {
        $query = ZakatPayment::with(['muzakki', 'paymentMethod']);

        if ($this->startDate) {
            $query->whereDate('tanggal_bayar', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('tanggal_bayar', '<=', $this->endDate);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Tanggal',
            'Nama Muzakki',
            'NIK',
            'No. Telepon',
            'Jumlah Jiwa',
            'Nominal/Jiwa',
            'Total Bayar',
            'Jenis Bayar',
            'Metode Pembayaran',
            'Status',
        ];
    }

    public function map($payment): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $payment->kode_transaksi,
            $payment->tanggal_bayar->format('d/m/Y'),
            $payment->muzakki->nama_lengkap,
            $payment->muzakki->nik,
            $payment->muzakki->no_telepon,
            $payment->jumlah_jiwa,
            number_format($payment->nominal_per_jiwa, 0, ',', '.'),
            number_format($payment->total_bayar, 0, ',', '.'),
            ucfirst($payment->jenis_bayar),
            $payment->paymentMethod->nama_metode,
            ucfirst($payment->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}