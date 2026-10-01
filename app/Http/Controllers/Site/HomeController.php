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
        ]);

        if ($produtos->isEmpty()) {
            $produtos = collect([
                ['nome' => 'Pomada Matte',    'marca' => 'Barber Pro',  'preco' => '49,90'],
                ['nome' => 'Óleo de Barba',   'marca' => "Lima's",      'preco' => '59,90'],
                ['nome' => 'Shampoo Premium', 'marca' => 'Barber Gold', 'preco' => '39,90'],
            ]);
        }

        return view('site.home', ['produtos' => $produtos->values()]);
    }
}
