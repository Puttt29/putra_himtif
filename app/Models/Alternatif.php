<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatif';

    protected $fillable = [
        'nama_alternatif',
        'catatan_pewawancara',
        'nim',
        'angkatan_id',
        'user_id',
    ];

    /**
     * Relasi ke Angkatan
     * alternatif.angkatan_id -> angkatan.id
     */
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id', 'id');
    }

    /**
     * Relasi ke User (opsional)
     * alternatif.user_id -> users.id
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke NilaiAlternatif
     */
    public function nilaiAlternatif()
    {
        return $this->hasMany(NilaiAlternatif::class, 'alternatif_id', 'id');
    }

    /**
     * Relasi many-to-many ke Kriteria (via nilai_alternatif)
     */
    public function kriterias()
    {
        return $this->belongsToMany(
            Kriteria::class,
            'nilai_alternatif',
            'alternatif_id',
            'kriteria_id'
        )->withPivot('nilai');
    }

    /**
     * Relasi ke Perhitungan
     */
    public function perhitungan()
    {
        return $this->hasMany(Perhitungan::class, 'alternatif_id', 'id');
    }

    public function pewawancaras()
    {
        return $this->belongsToMany(User::class, 'alternatif_pewawancara');
    }
}
