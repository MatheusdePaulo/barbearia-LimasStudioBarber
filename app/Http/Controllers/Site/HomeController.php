<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use App\Models\Avaliacao;
use App\Models\Produto;
use App\Models\Configuracao;

class HomeController extends Controller
{
    public function index()
    {
        $servicos   = Servico::where('ativo', true)->get();
        $avaliacoes = Avaliacao::where('aprovado', true)->latest()->take(6)->get();
        $produtos   = Produto::where('ativo', true)->take(6)->get();

        return view('site.home', compact('servicos', 'avaliacoes', 'produtos'));
    }
}
