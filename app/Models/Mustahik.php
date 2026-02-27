<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mustahik extends Model
{
    use HasFactory;

    protected $table = 'mustahik';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'alamat',
        'no_telepon',
        'kategori',
        'keterangan',
        'status',
    ];

    // Konstanta untuk kategori mustahik (8 asnaf)
    public const KATEGORI = [
        'fakir' => 'Fakir',
        'miskin' => 'Miskin',
        'amil' => 'Amil',
        'mualaf' => 'Mualaf',
        'riqab' => 'Riqab (Hamba Sahaya)',
        'gharimin' => 'Gharimin (Orang Berhutang)',
        'fisabilillah' => 'Fisabilillah',
        'ibnu sabil' => 'Ibnu Sabil (Musafir)',
    ];

    // Helper: Get formatted kategori
    public function getKategoriLabelAttribute()
    {
        return self::KATEGORI[$this->kategori] ?? $this->kategori;
    }
}