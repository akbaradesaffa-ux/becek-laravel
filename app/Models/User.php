<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'tb_user';
    public $timestamps = false;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'username', // legacy compatibility, tidak dipakai lagi di UI
        'password',
        'status_role',
    ];

    protected $hidden = [
        'password',
    ];


    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}
