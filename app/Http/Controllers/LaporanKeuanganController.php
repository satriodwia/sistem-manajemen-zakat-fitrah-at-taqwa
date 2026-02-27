<?php

namespace App\Http\Controllers;

use App\Models\ZakatPayment;
use App\Models\SedekahPayment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ZakatPaymentsExport;
use App\Exports\SedekahPaymentsExport;
use App\Exports\LaporanKeuanganExport;

class LaporanKeuanganController extends Controller
{
    /**
     * Export Laporan Keuangan ke PDF
     */
    public function exportPDF(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Ambil data Zakat
        $zakatQuery = ZakatPayment::with(['muzakki', 'paymentMethod'])
            ->where('status', 'lunas');

        if ($startDate) {
            $zakatQuery->whereDate('tanggal_bayar', '>=', $startDate);
        }

        if ($endDate) {
            $zakatQuery->whereDate('tanggal_bayar', '<=', $endDate);
        }

        $zakatPayments = $zakatQuery->get();

        // Ambil data Sedekah
        $sedekahQuery = SedekahPayment::with(['paymentMethod'])
            ->where('status', 'diterima');

        if ($startDate) {
            $sedekahQuery->whereDate('tanggal_donasi', '>=', $startDate);
        }

        if ($endDate) {
            $sedekahQuery->whereDate('tanggal_donasi', '<=', $endDate);
        }

        $sedekahPayments = $sedekahQuery->get();

        // Hitung total
        $totalZakat = $zakatPayments->sum('total_bayar');
        $totalSedekah = $sedekahPayments->sum('nominal');
        $grandTotal = $totalZakat + $totalSedekah;

        $data = [
            'title' => 'Laporan Keuangan',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'zakat_payments' => $zakatPayments,
            'sedekah_payments' => $sedekahPayments,
            'total_zakat' => $totalZakat,
            'total_sedekah' => $totalSedekah,
            'grand_total' => $grandTotal,
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('laporan.pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-keuangan-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Zakat Payments ke Excel
     */
    public function exportZakatExcel(Request $request)
    {
        return Excel::download(
            new ZakatPaymentsExport($request->start_date, $request->end_date, $request->status),
            'laporan-zakat-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Sedekah Payments ke Excel
     */
    public function exportSedekahExcel(Request $request)
    {
        return Excel::download(
            new SedekahPaymentsExport($request->start_date, $request->end_date, $request->status),
            'laporan-sedekah-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Laporan Keuangan ke Excel
     */
    public function exportLaporanExcel(Request $request)
    {
        return Excel::download(
            new LaporanKeuanganExport($request->start_date, $request->end_date),
            'laporan-keuangan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}