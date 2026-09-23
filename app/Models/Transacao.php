<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    protected $table = 'transacoes';

    protected $fillable = [
        'agendamento_id', 'tipo', 'descricao', 'valor', 'forma_pagamento', 'data'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'date',
    ];

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class);
    }
}
