<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interazione extends Model
{
    use HasFactory;

    protected $table = 'interazioni';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'data',
        'tipo',
        'descrizione',
        'esito',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'datetime',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
