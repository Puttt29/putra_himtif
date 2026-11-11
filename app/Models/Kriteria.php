<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode_kriteria_id',
        'nama_kriteria',
        'atribut',
        'bobot',
        'normalisasi',
    ];

    // Relasi ke KodeKriteria
    public function kodeKriteria()
    {
        return $this->belongsTo(KodeKriteria::class, 'kode_kriteria_id', 'id');
    }

    // Relasi ke NilaiAlternatif
    public function nilaiAlternatif()
    {
        return $this->hasMany(NilaiAlternatif::class, 'kriteria_id', 'id');
    }

    // Relasi ke Perhitungan
    public function perhitungan()
    {
        return $this->hasMany(Perhitungan::class, 'kriteria_id', 'id');
    }
}
