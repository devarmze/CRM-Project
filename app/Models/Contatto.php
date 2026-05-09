<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contatto extends Model
{
    use HasFactory;

    protected $table = 'contatti';

    protected $fillable = [
        'cliente_id',
        'nome',
        'ruolo',
        'email',
        'telefono',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
