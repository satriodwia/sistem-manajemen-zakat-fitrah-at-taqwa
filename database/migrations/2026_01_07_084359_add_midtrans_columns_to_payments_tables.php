<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update tabel zakat_payments
        Schema::table('zakat_payments', function (Blueprint $table) {
            // Ubah status enum untuk include status midtrans
            $table->enum('status', ['pending', 'waiting', 'lunas', 'expired', 'batal'])->default('pending')->change();
            
            // Tambah kolom midtrans
            $table->string('midtrans_order_id')->nullable()->unique()->after('kode_transaksi');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->text('snap_token')->nullable()->after('midtrans_transaction_id');
            $table->string('payment_type')->nullable()->after('snap_token'); // bank_transfer, gopay, dll
            $table->timestamp('paid_at')->nullable()->after('tanggal_bayar');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
        });

        // Update tabel sedekah_payments
        Schema::table('sedekah_payments', function (Blueprint $table) {
            // Ubah status enum untuk include status midtrans
            $table->enum('status', ['pending', 'waiting', 'diterima', 'expired', 'batal'])->default('pending')->change();
            
            // Tambah kolom midtrans
            $table->string('midtrans_order_id')->nullable()->unique()->after('kode_transaksi');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->text('snap_token')->nullable()->after('midtrans_transaction_id');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->timestamp('paid_at')->nullable()->after('tanggal_donasi');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakat_payments', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'snap_token',
                'payment_type',
                'paid_at',
                'expired_at'
            ]);
        });

        Schema::table('sedekah_payments', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'snap_token',
                'payment_type',
                'paid_at',
                'expired_at'
            ]);
        });
    }
};