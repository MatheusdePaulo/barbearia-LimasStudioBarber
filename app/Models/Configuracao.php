<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracoes';

    protected $fillable = ['chave', 'valor'];

    public static function get(string $chave, $default = null)
    {
        return static::where('chave', $chave)->value('valor') ?? $default;
    }

    public static function set(string $chave, $valor)
    {
        return static::updateOrCreate(
            ['chave' => $chave],
            ['valor' => $valor]
        );
    }
}
