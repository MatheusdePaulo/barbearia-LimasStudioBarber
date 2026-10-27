<?php

namespace App\Support;

/**
 * Imagens responsivas: cada foto tem versões menores ao lado (ex.: fulano1-antes-480w.webp).
 * O srcset lista todas as larguras e o navegador baixa a menor que fica nítida naquela tela
 * (celular baixa a pequena, tela retina continua recebendo a grande). O visual não muda.
 */
class Imagem
{
    private static array $cache = [];

    // srcset de 'images/x.webp': "…/x-480w.webp 480w, …/x-640w.webp 640w, …/x.webp 900w"
    public static function srcset(string $caminho): string
    {
        return self::$cache[$caminho] ??= self::montar($caminho);
    }

    private static function montar(string $caminho): string
    {
        $arquivo = public_path($caminho);
        if (! is_file($arquivo)) {
            return '';
        }

        $base = preg_replace('/\.webp$/', '', $caminho);
        $opcoes = [];
        foreach (glob(public_path($base) . '-*w.webp') ?: [] as $variante) {
            if (preg_match('/-(\d+)w\.webp$/', $variante, $m)) {
                $opcoes[(int) $m[1]] = asset($base . '-' . $m[1] . 'w.webp');
            }
        }
        if (! $opcoes) {
            return '';
        }

        $opcoes[getimagesize($arquivo)[0]] = asset($caminho);
        ksort($opcoes);

        return collect($opcoes)->map(fn ($url, $w) => "{$url} {$w}w")->join(', ');
    }
}
