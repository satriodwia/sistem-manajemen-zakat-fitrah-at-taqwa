<?php

namespace App\Http\Controllers;

use App\Models\ZakatPayment;
use App\Models\SedekahPayment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle webhook notification dari Midtrans
     */
    public function handle(Request $request)
    {
        // Verifikasi notifikasi
        $notification = $this->midtransService->verifyNotification();

        if (!$notification['success']) {
            Log::error('Midtrans Notification Error: ' . $notification['message']);
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        // Log notifikasi untuk debugging
        Log::info('Midtrans Notification Received', $notification);

        $orderId = $notification['order_id'];
        $transactionStatus = $notification['transaction_status'];
        $fraudStatus = $notification['fraud_status'];
        $paymentType = $notification['payment_type'];
        $transactionId = $notification['transaction_id'];

        // Tentukan status baru
        $newStatus = $this->midtransService->determineStatus($transactionStatus, $fraudStatus);

        // Update payment berdasarkan order_id
        $zakatPayment = ZakatPayment::where('midtrans_order_id', $orderId)->first();
        $sedekahPayment = SedekahPayment::where('midtrans_order_id', $orderId)->first();

        if ($zakatPayment) {
            // Update zakat payment
            $zakatPayment->update([
                'status' => $newStatus,
                'midtrans_transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'paid_at' => in_array($newStatus, ['lunas']) ? now() : null,
            ]);

            Log::info("Zakat Payment {$zakatPayment->kode_transaksi} updated to status: {$newStatus}");

        } elseif ($sedekahPayment) {
            // Update sedekah payment
            $statusSedekah = $newStatus == 'lunas' ? 'diterima' : $newStatus;
            
            $sedekahPayment->update([
                'status' => $statusSedekah,
                'midtrans_transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'paid_at' => in_array($statusSedekah, ['diterima']) ? now() : null,
            ]);

            Log::info("Sedekah Payment {$sedekahPayment->kode_transaksi} updated to status: {$statusSedekah}");
        } else {
            Log::warning("Payment with order_id {$orderId} not found");
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json(['message' => 'Notification processed successfully']);
    }
}