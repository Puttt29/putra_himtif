<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeKriteria extends Model
{
    use HasFactory;

    protected $table = 'kode_kriteria';

    protected $fillable = ['kode_kriteria'];

    // Relasi ke Kriteria
    public function kriteria()
    {
        return $this->hasMany(Kriteria::class, 'kode_kriteria_id', 'id');
    }
}
