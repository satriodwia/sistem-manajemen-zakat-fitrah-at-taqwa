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
        Schema::create('sedekah_payments', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('nama_donatur'); // Bisa anonim
            $table->string('no_telepon', 15)->nullable();
            $table->string('email')->nullable();
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_donasi');
            $table->text('catatan')->nullable();
            $table->boolean('is_anonim')->default(false);
            $table->enum('status', ['pending', 'diterima', 'batal'])->default('diterima');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sedekah_payments');
    }
};