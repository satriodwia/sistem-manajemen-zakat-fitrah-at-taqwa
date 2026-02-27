<?php

namespace App\Exports;

use App\Models\SedekahPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SedekahPaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = SedekahPayment::with(['paymentMethod']);

        if ($this->startDate) {
            $query->whereDate('tanggal_donasi', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('tanggal_donasi', '<=', $this->endDate);
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
            'Nama Donatur',
            'No. Telepon',
            'Email',
            'Nominal',
            'Metode Pembayaran',
            'Anonim',
            'Status',
            'Catatan',
        ];
    }

    public function map($payment): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $payment->kode_transaksi,
            $payment->tanggal_donasi->format('d/m/Y'),
            $payment->display_name,
            $payment->is_anonim ? '-' : ($payment->no_telepon ?? '-'),
            $payment->is_anonim ? '-' : ($payment->email ?? '-'),
            number_format($payment->nominal, 0, ',', '.'),
            $payment->paymentMethod->nama_metode,
            $payment->is_anonim ? 'Ya' : 'Tidak',
            ucfirst($payment->status),
            $payment->catatan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EC4899']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}