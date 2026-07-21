<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Buat transaksi Snap
     */
    public function createTransaction($params)
    {
        try {
            $snapToken = Snap::getSnapToken($params);
            return [
                'success' => true,
                'snap_token' => $snapToken,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verifikasi notifikasi dari Midtrans
     */
    public function verifyNotification()
    {
        try {
            $notification = new Notification();

            return [
                'success' => true,
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'fraud_status' => $notification->fraud_status ?? null,
                'payment_type' => $notification->payment_type,
                'gross_amount' => $notification->gross_amount,
                'transaction_id' => $notification->transaction_id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate parameter untuk Snap Midtrans
     */
    public function generateSnapParams($orderId, $grossAmount, $customerDetails, $itemDetails)
    {
        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'enabled_payments' => [
                'credit_card',
                'mandiri_clickpay',
                'cimb_clicks',
                'bca_klikbca',
                'bca_klikpay',
                'bri_epay',
                'echannel',
                'permata_va',
                'bca_va',
                'bni_va',
                'bri_va',
                'other_va',
                'gopay',
                'shopeepay',
                'qris',
                'akulaku',
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24,
            ],
        ];
    }

    /**
 * Cek status transaksi langsung ke Midtrans API (dipakai oleh tombol Sync Status)
 */
    public function checkTransactionStatus($orderId)
    {
        try {
        $status = Transaction::status($orderId);

        return [
            'success' => true,
            'order_id' => $status->order_id,
            'transaction_status' => $status->transaction_status,
            'fraud_status' => $status->fraud_status ?? null,
            'payment_type' => $status->payment_type ?? null,
            'transaction_id' => $status->transaction_id,
            'gross_amount' => $status->gross_amount,
            ];
        } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
}

    /**
     * Determine status dari notifikasi Midtrans
     */
    public function determineStatus($transactionStatus, $fraudStatus = null)
    {
        $status = 'pending';

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $status = 'lunas'; // untuk zakat
                // $status = 'diterima'; // untuk sedekah
            }
        } elseif ($transactionStatus == 'settlement') {
            $status = 'lunas'; // untuk zakat
            // $status = 'diterima'; // untuk sedekah
        } elseif ($transactionStatus == 'pending') {
            $status = 'waiting';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            if ($transactionStatus == 'expire') {
                $status = 'expired';
            } else {
                $status = 'batal';
            }
        }

        return $status;
    }
}