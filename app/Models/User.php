<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function clienti()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_user');
    }

    public function opportunita()
    {
        return $this->hasMany(Opportunita::class);
    }

    public function attivita()
    {
        return $this->hasMany(Attivita::class);
    }

    public function interazioni()
    {
        return $this->hasMany(Interazione::class);
    }

    public function clientiCreati()
    {
        return $this->hasMany(Cliente::class, 'created_by');
    }
}
