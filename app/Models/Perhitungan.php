<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perhitungan extends Model
{
    use HasFactory;

    protected $table = 'perhitungan';

    protected $fillable = [
        'perhitungan_session_id',
        'alternatif_id',
        'kriteria_id',
        'nilai_utilitas',
        'nilai_akhir',
    ];

    /* ===================== RELATION ===================== */

    public function session()
    {
        return $this->belongsTo(
            PerhitunganSession::class,
            'perhitungan_session_id'
        );
    }

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
