<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    protected $table = 'cupons';

    protected $fillable = [
        'codigo', 'tipo', 'valor', 'limite_usos', 'usos', 'validade', 'ativo'
    ];

    protected $casts = [
        'validade' => 'datetime',
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function isValido()
    {
        if (!$this->ativo) return false;
        if ($this->validade && $this->validade->isPast()) return false;
        if ($this->limite_usos && $this->usos >= $this->limite_usos) return false;
        return true;
    }
}
