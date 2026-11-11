<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerhitunganSession extends Model
{
    use HasFactory;

    protected $table = 'perhitungan_sessions';

    protected $fillable = [
        'nama_perhitungan',
        'angkatan_id',
        'tanggal_hitung',
    ];

    protected $casts = [
        'tanggal_hitung' => 'datetime',
    ];

    /* ===================== RELATION ===================== */

    /**
     * Relasi ke Angkatan
     * perhitungan_sessions.angkatan_id -> angkatan.id
     */
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id', 'id');
    }

    /**
     * Relasi ke Perhitungan
     * perhitungan_sessions.id -> perhitungan.perhitungan_session_id
     */
    public function perhitungan()
    {
        return $this->hasMany(Perhitungan::class, 'perhitungan_session_id', 'id');
    }
}
