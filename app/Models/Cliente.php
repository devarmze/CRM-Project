<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clienti';

    protected $fillable = [
        'nome',
        'tipo',
        'email',
        'telefono',
        'citta',
        'settore',
        'stato',
        'created_by',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'cliente_user');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contatti()
    {
        return $this->hasMany(Contatto::class);
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
        return $this->hasMany(Interazione::class)->orderBy('data', 'desc');
    }
}
