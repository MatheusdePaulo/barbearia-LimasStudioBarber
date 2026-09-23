<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $table = 'servicos';

    protected $fillable = [
        'nome', 'descricao', 'preco', 'duracao_minutos', 'icone', 'ativo'
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function agendamentos()
    {
        return $this->belongsToMany(Agendamento::class, 'agendamento_servicos')
            ->withPivot('preco_unitario')
            ->withTimestamps();
    }
}
