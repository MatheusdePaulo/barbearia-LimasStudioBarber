<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendamentoServico extends Model
{
    protected $fillable = [
        'agendamento_id', 'servico_id', 'preco_unitario'
    ];

    protected $casts = [
        'preco_unitario' => 'decimal:2',
    ];
}
