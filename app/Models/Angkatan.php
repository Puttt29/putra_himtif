<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory;

    protected $table = 'angkatan';

    protected $fillable = [
        'nama_angkatan',
    ];

    /**
     * Relasi ke Alternatif
     * angkatan.id -> alternatif.angkatan_id
     */
    public function alternatif()
    {
        return $this->hasMany(Alternatif::class, 'angkatan_id', 'id');
    }

    /**
     * Relasi ke PerhitunganSession
     * angkatan.id -> perhitungan_sessions.angkatan_id
     */
    public function perhitunganSessions()
    {
        return $this->hasMany(PerhitunganSession::class, 'angkatan_id', 'id');
    }
}
