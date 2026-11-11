<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Alternatif
     * users.id -> alternatif.user_id
     */
    public function alternatif()
    {
        return $this->hasMany(Alternatif::class, 'user_id', 'id');
    }

    public function alternatifAssigned()
    {
        return $this->belongsToMany(Alternatif::class, 'alternatif_pewawancara');
    }
}
