<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_metode',
        'keterangan',
        'status',
    ];

    // Relationship: Payment method punya banyak pembayaran zakat
    public function zakatPayments(): HasMany
    {
        return $this->hasMany(ZakatPayment::class);
    }

    // Relationship: Payment method punya banyak pembayaran sedekah
    public function sedekahPayments(): HasMany
    {
        return $this->hasMany(SedekahPayment::class);
    }

    // Scope: Hanya metode aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}