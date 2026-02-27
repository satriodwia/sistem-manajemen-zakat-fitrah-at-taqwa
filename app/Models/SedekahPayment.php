<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SedekahPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'nama_donatur',
        'no_telepon',
        'email',
        'payment_method_id',
        'nominal',
        'tanggal_donasi',
        'catatan',
        'is_anonim',
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
        'nominal' => 'decimal:2',
        'is_anonim' => 'boolean',
        'tanggal_donasi' => 'date',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

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
                $model->kode_transaksi = 'SDK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
            if (empty($model->midtrans_order_id)) {
                $model->midtrans_order_id = 'ORDER-' . time() . '-' . strtoupper(substr(uniqid(), -4));
            }
            // Jika anonim, sembunyikan nama
            if ($model->is_anonim) {
                $model->nama_donatur = 'Hamba Allah (Anonim)';
            }
        });
    }

    // Scope: Hanya donasi diterima
    public function scopeDiterima($query)
    {
        return $query->where('status', 'diterima');
    }

    // Scope: Filter by tahun
    public function scopeByYear($query, $year)
    {
        return $query->whereYear('tanggal_donasi', $year);
    }

    // Helper: Get display name
    public function getDisplayNameAttribute()
    {
        return $this->is_anonim ? 'Hamba Allah (Anonim)' : $this->nama_donatur;
    }
}