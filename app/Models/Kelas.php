<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
    ];

    /**
     * Relasi:
     * Satu Kelas memiliki banyak Alternatif (Siswa)
     */
    public function alternatif()
    {
        return $this->hasMany(Alternatif::class, 'kelas_id');
    }
}
