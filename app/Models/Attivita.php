<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attivita extends Model
{
    use HasFactory;

    protected $table = 'attivita';

    protected $fillable = [
        'cliente_id',
        'opportunita_id',
        'user_id',
        'tipo',
        'data',
        'note',
        'stato',
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

    public function opportunita()
    {
        return $this->belongsTo(Opportunita::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
