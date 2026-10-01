<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Carrossel de produtos: usa os produtos cadastrados no admin. Enquanto não houver nenhum,
        // mostra os de exemplo pra seção não ficar vazia.
        $produtos = Product::orderBy('name')->get()->map(fn (Product $p) => [
            'nome'  => $p->name,
            'marca' => $p->description ?? '',
            'preco' => number_format((float) $p->price, 2, ',', '.'),
            // o admin salva só o nome do arquivo, que fica em public/images
            'imagem' => $p->image ? asset('images/'.$p->image) : null,
        ]);

        if ($produtos->isEmpty()) {
            $produtos = collect([
                ['nome' => 'Pomada Matte',    'marca' => 'Jaboque',    'preco' => '49,90', 'imagem' => asset('images/produto-pomada-matte.png'), 'escala' => 0.7],
                ['nome' => 'Óleo de Barba',   'marca' => 'Force Men',  'preco' => '59,90', 'imagem' => asset('images/produto-oleo-barba.png')],
                ['nome' => 'Shampoo 3 em 1',  'marca' => 'Force Men',  'preco' => '39,90', 'imagem' => asset('images/produto-shampoo.png')],
            ]);
        }

        // "escala" diminui a foto de um produto no card (ex.: o pote largo da pomada ocupava a largura toda
        // e chamava mais atenção que os frascos). Sem escala = 1.
        $produtos = $produtos->map(fn ($p) => $p + ['escala' => 1]);

        return view('site.home', ['produtos' => $produtos->values()]);
    }
}
