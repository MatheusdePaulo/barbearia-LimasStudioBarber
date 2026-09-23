<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;

// ─── Site público ────────────────────────────────────────────
Route::get('/', [Site\HomeController::class, 'index'])->name('home');
Route::get('/agendar', [Site\AgendamentoController::class, 'index'])->name('agendar');
Route::post('/agendar', [Site\AgendamentoController::class, 'store'])->name('agendar.store');
Route::get('/agendar/confirmacao/{agendamento}', [Site\AgendamentoController::class, 'confirmacao'])->name('agendar.confirmacao');
Route::post('/avaliacoes', [Site\AgendamentoController::class, 'avaliar'])->name('avaliacoes.store');
Route::post('/cupom/validar', [Site\AgendamentoController::class, 'validarCupom'])->name('cupom.validar');

// ─── Auth ─────────────────────────────────────────────────────
Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->name('login');

// ─── Painel Admin ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clientes', Admin\ClienteController::class);
    Route::resource('servicos', Admin\ServicoController::class);
    Route::resource('produtos', Admin\ProdutoController::class);
    Route::resource('agendamentos', Admin\AgendamentoController::class);
    Route::resource('cupons', Admin\CupomController::class);
    Route::resource('avaliacoes', Admin\AvaliacaoController::class);
    Route::resource('transacoes', Admin\TransacaoController::class);

    Route::get('financeiro', [Admin\TransacaoController::class, 'financeiro'])->name('financeiro');
    Route::get('aniversariantes', [Admin\ClienteController::class, 'aniversariantes'])->name('aniversariantes');

    Route::get('configuracoes', [Admin\ConfiguracaoController::class, 'index'])->name('configuracoes.index');
    Route::post('configuracoes', [Admin\ConfiguracaoController::class, 'update'])->name('configuracoes.update');
});
