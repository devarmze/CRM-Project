<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opportunita extends Model
{
    use HasFactory;

    protected $table = 'opportunita';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'titolo',
        'valore_stimato',
        'fase',
        'data_chiusura_prevista',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'data_chiusura_prevista' => 'date',
            'valore_stimato' => 'decimal:2',
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

    public function attivita()
    {
        return $this->hasMany(Attivita::class);
    }
}
