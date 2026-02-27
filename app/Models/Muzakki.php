<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Muzakki extends Model
{
    use HasFactory;

    protected $table = 'muzakki';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'alamat',
        'no_telepon',
        'email',
        'jumlah_tanggungan',
        'status',
    ];

    protected $casts = [
        'jumlah_tanggungan' => 'integer',
    ];

    // Relationship: Muzakki punya banyak pembayaran zakat
    public function zakatPayments(): HasMany
    {
        return $this->hasMany(ZakatPayment::class);
    }

    // Helper: Total pembayaran muzakki
    public function getTotalPembayaranAttribute()
    {
        return $this->zakatPayments()
            ->where('status', 'lunas')
            ->sum('total_bayar');
    }
}