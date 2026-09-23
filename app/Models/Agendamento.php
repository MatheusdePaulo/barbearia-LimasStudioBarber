<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $table = 'agendamentos';

    protected $fillable = [
        'cliente_id', 'cupom_id', 'data_hora', 'status',
        'forma_pagamento', 'status_pagamento', 'valor_total',
        'desconto', 'observacoes'
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        'valor_total' => 'decimal:2',
        'desconto' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cupom()
    {
        return $this->belongsTo(Cupom::class);
    }

    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'agendamento_servicos')
            ->withPivot('preco_unitario')
            ->withTimestamps();
    }

    public function transacao()
    {
        return $this->hasOne(Transacao::class);
    }
}
