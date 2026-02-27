<?php

namespace App\Http\Controllers;

use App\Models\Muzakki;
use App\Models\PaymentMethod;
use App\Models\ZakatPayment;
use App\Models\SedekahPayment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Halaman form pembayaran zakat
     */
    public function zakatForm()
    {
        $paymentMethods = PaymentMethod::where('status', 'aktif')->get();
        return view('payment.zakat-form', compact('paymentMethods'));
    }

    /**
     * Halaman form sedekah
     */
    public function sedekahForm()
    {
        $paymentMethods = PaymentMethod::where('status', 'aktif')->get();
        return view('payment.sedekah-form', compact('paymentMethods'));
    }

    /**
     * Proses pembayaran zakat
     */
    public function processZakat(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16',
            'no_telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'email' => 'nullable|email',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'jumlah_jiwa' => 'required|integer|min:1',
            'nominal_per_jiwa' => 'required|numeric|min:1000',
            'jenis_bayar' => 'required|in:uang,beras',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Cari atau buat muzakki
            $muzakki = Muzakki::updateOrCreate(
                ['nik' => $request->nik],
                [
                    'nama_lengkap' => $request->nama_lengkap,
                    'alamat' => $request->alamat,
                    'no_telepon' => $request->no_telepon,
                    'email' => $request->email,
                    'jumlah_tanggungan' => $request->jumlah_tanggungan,
                    'status' => 'aktif',
                ]
            );

            // Hitung total
            $totalBayar = $request->jumlah_jiwa * $request->nominal_per_jiwa;

            // Cari payment method
            $paymentMethod = PaymentMethod::findOrFail($request->payment_method);

            // Buat pembayaran zakat
            $zakatPayment = ZakatPayment::create([
                'muzakki_id' => $muzakki->id,
                'payment_method_id' => $paymentMethod->id,
                'jumlah_jiwa' => $request->jumlah_jiwa,
                'nominal_per_jiwa' => $request->nominal_per_jiwa,
                'total_bayar' => $totalBayar,
                'jenis_bayar' => $request->jenis_bayar,
                'tanggal_bayar' => now(),
                'status' => 'pending',
            ]);

            // Jika pakai Midtrans
            if ($paymentMethod->nama_metode != 'Tunai') {
                // Generate Snap Token
                $customerDetails = [
                    'first_name' => $muzakki->nama_lengkap,
                    'email' => $muzakki->email ?? 'noreply@zakat.com',
                    'phone' => $muzakki->no_telepon,
                ];

                $itemDetails = [
                    [
                        'id' => $zakatPayment->kode_transaksi,
                        'price' => $request->nominal_per_jiwa,
                        'quantity' => $request->jumlah_jiwa,
                        'name' => 'Zakat Fitrah - ' . $request->jumlah_jiwa . ' Jiwa',
                    ]
                ];

                $snapParams = $this->midtransService->generateSnapParams(
                    $zakatPayment->midtrans_order_id,
                    $totalBayar,
                    $customerDetails,
                    $itemDetails
                );

                $snapResult = $this->midtransService->createTransaction($snapParams);

                if ($snapResult['success']) {
                    $zakatPayment->update([
                        'snap_token' => $snapResult['snap_token'],
                        'status' => 'waiting',
                        'expired_at' => now()->addHours(24),
                    ]);

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'snap_token' => $snapResult['snap_token'],
                        'order_id' => $zakatPayment->kode_transaksi,
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat transaksi: ' . $snapResult['message'],
                    ], 500);
                }
            } else {
                // Pembayaran manual (Tunai)
                DB::commit();
                return response()->json([
                    'success' => true,
                    'manual' => true,
                    'order_id' => $zakatPayment->kode_transaksi,
                    'message' => 'Pembayaran berhasil dicatat. Silakan lakukan pembayaran ke masjid terdekat dan simpan kode transaksi.',
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proses sedekah
     */
    public function processSedekah(Request $request)
    {
        $request->validate([
            'nama_donatur' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email',
            'nominal' => 'required|numeric|min:1000',
            'is_anonim' => 'nullable|boolean',
            'payment_method' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Cari payment method
            $paymentMethod = PaymentMethod::findOrFail($request->payment_method);

            // Buat sedekah
            $sedekahPayment = SedekahPayment::create([
                'nama_donatur' => $request->is_anonim ? 'Hamba Allah (Anonim)' : ($request->nama_donatur ?? 'Hamba Allah'),
                'no_telepon' => $request->is_anonim ? null : $request->no_telepon,
                'email' => $request->is_anonim ? null : $request->email,
                'payment_method_id' => $paymentMethod->id,
                'nominal' => $request->nominal,
                'tanggal_donasi' => now(),
                'catatan' => $request->catatan,
                'is_anonim' => $request->is_anonim ?? false,
                'status' => 'pending',
            ]);

            // Jika pakai Midtrans
            if ($paymentMethod->nama_metode != 'Tunai') {
                // Generate Snap Token
                $customerDetails = [
                    'first_name' => $request->is_anonim ? 'Anonim' : $request->nama_donatur,
                    'email' => $request->is_anonim ? 'noreply@zakat.com' : ($request->email ?? 'noreply@zakat.com'),
                    'phone' => $request->is_anonim ? '08123456789' : ($request->no_telepon ?? '08123456789'),
                ];

                $itemDetails = [
                    [
                        'id' => $sedekahPayment->kode_transaksi,
                        'price' => $request->nominal,
                        'quantity' => 1,
                        'name' => 'Sedekah',
                    ]
                ];

                $snapParams = $this->midtransService->generateSnapParams(
                    $sedekahPayment->midtrans_order_id,
                    $request->nominal,
                    $customerDetails,
                    $itemDetails
                );

                $snapResult = $this->midtransService->createTransaction($snapParams);

                if ($snapResult['success']) {
                    $sedekahPayment->update([
                        'snap_token' => $snapResult['snap_token'],
                        'status' => 'waiting',
                        'expired_at' => now()->addHours(24),
                    ]);

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'snap_token' => $snapResult['snap_token'],
                        'order_id' => $sedekahPayment->kode_transaksi,
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal membuat transaksi: ' . $snapResult['message'],
                    ], 500);
                }
            } else {
                // Pembayaran manual
                DB::commit();
                return response()->json([
                    'success' => true,
                    'manual' => true,
                    'order_id' => $sedekahPayment->kode_transaksi,
                    'message' => 'Sedekah berhasil dicatat. Silakan lakukan pembayaran sesuai metode yang dipilih.',
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Halaman sukses
     */
    public function success($orderId)
    {
        // Cari berdasarkan kode_transaksi atau midtrans_order_id
        $zakat = ZakatPayment::where('kode_transaksi', $orderId)
            ->orWhere('midtrans_order_id', $orderId)
            ->first();
            
        $sedekah = SedekahPayment::where('kode_transaksi', $orderId)
            ->orWhere('midtrans_order_id', $orderId)
            ->first();

        $payment = $zakat ?? $sedekah;
        $type = $zakat ? 'zakat' : 'sedekah';

        // Jika payment tidak ditemukan
        if (!$payment) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        return view('payment.success', compact('payment', 'type'));
    }

    /**
     * Print bukti zakat
     */
    public function printZakat($id)
    {
        $payment = ZakatPayment::with(['muzakki', 'paymentMethod'])->findOrFail($id);
        
        return view('payment.print-zakat', compact('payment'));
    }

    /**
     * Print bukti sedekah
     */
    public function printSedekah($id)
    {
        $payment = SedekahPayment::with(['paymentMethod'])->findOrFail($id);
        
        return view('payment.print-sedekah', compact('payment'));
    }
}