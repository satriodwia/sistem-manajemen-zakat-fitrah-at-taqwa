<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'nama_metode' => 'Tunai',
                'keterangan' => 'Pembayaran langsung dengan uang tunai ke panitia',
                'status' => 'aktif',
            ],
            [
                'nama_metode' => 'Transfer Bank',
                'keterangan' => 'Transfer ke rekening Bank BCA: 1234567890 a.n. Panitia Zakat',
                'status' => 'aktif',
            ],
            [
                'nama_metode' => 'Midtrans Payment Gateway',
                'keterangan' => 'Pembayaran online melalui Midtrans (Credit Card, GoPay, QRIS, dll)',
                'status' => 'aktif',
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}