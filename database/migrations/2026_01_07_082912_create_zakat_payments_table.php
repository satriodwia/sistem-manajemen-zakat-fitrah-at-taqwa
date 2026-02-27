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
        Schema::create('zakat_payments', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('muzakki_id')->constrained('muzakki')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->integer('jumlah_jiwa'); // Jumlah jiwa yang dibayarkan
            $table->decimal('nominal_per_jiwa', 15, 2); // Harga beras/uang per jiwa
            $table->decimal('total_bayar', 15, 2); // Total pembayaran
            $table->enum('jenis_bayar', ['beras', 'uang'])->default('uang');
            $table->date('tanggal_bayar');
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'lunas', 'batal'])->default('lunas');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_payments');
    }
};