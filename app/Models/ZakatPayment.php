<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZakatPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'muzakki_id',
        'payment_method_id',
        'jumlah_jiwa',
        'nominal_per_jiwa',
        'total_bayar',
        'jenis_bayar',
        'tanggal_bayar',
        'catatan',
        'status',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'snap_token',
        'payment_type',
        'paid_at',
        'expired_at',
        'created_by',
    ];

    protected $casts = [
        'jumlah_jiwa' => 'integer',
        'nominal_per_jiwa' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'tanggal_bayar' => 'date',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // Relationship: Pembayaran punya satu muzakki
    public function muzakki(): BelongsTo
    {
        return $this->belongsTo(Muzakki::class);
    }

    // Relationship: Pembayaran punya satu metode pembayaran
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    // Relationship: Pembayaran dibuat oleh user
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Auto generate kode transaksi
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_transaksi)) {
                $model->kode_transaksi = 'ZKT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
            if (empty($model->midtrans_order_id)) {
                $model->midtrans_order_id = 'ORDER-' . time() . '-' . strtoupper(substr(uniqid(), -4));
            }
        });
    }

    // Scope: Hanya pembayaran lunas
    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    // Scope: Filter by tahun
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('tanggal_bayar', $year);
    }
}