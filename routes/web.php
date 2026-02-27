<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransWebhookController;

// Homepage
Route::get('/', function () {
    return view('home');
})->name('home');

// Routes untuk halaman publik pembayaran
Route::get('/zakat', [PaymentController::class, 'zakatForm'])->name('zakat.form');
Route::get('/sedekah', [PaymentController::class, 'sedekahForm'])->name('sedekah.form');
Route::post('/zakat/process', [PaymentController::class, 'processZakat'])->name('zakat.process');
Route::post('/sedekah/process', [PaymentController::class, 'processSedekah'])->name('sedekah.process');
Route::get('/payment/success/{orderId}', [PaymentController::class, 'success'])->name('payment.success');

// Print Bukti Pembayaran
Route::get('/print/zakat/{id}', [App\Http\Controllers\PaymentController::class, 'printZakat'])->name('print.zakat');
Route::get('/print/sedekah/{id}', [App\Http\Controllers\PaymentController::class, 'printSedekah'])->name('print.sedekah');

// Webhook Midtrans
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');

// Laporan Keuangan Routes
Route::middleware('auth')->group(function () {
    Route::get('/laporan/pdf', [App\Http\Controllers\LaporanKeuanganController::class, 'exportPDF'])->name('laporan.pdf');
    Route::get('/laporan/zakat/excel', [App\Http\Controllers\LaporanKeuanganController::class, 'exportZakatExcel'])->name('laporan.zakat.excel');
    Route::get('/laporan/sedekah/excel', [App\Http\Controllers\LaporanKeuanganController::class, 'exportSedekahExcel'])->name('laporan.sedekah.excel');
    Route::get('/laporan/keuangan/excel', [App\Http\Controllers\LaporanKeuanganController::class, 'exportLaporanExcel'])->name('laporan.keuangan.excel');
});