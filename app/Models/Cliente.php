<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nome', 'telefone', 'email', 'data_nascimento',
        'cpf', 'sexo', 'observacoes', 'ativo'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class);
    }

    public function isAniversariante()
    {
        return $this->data_nascimento &&
            $this->data_nascimento->format('m-d') === now()->format('m-d');
    }
}
