<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ScheduleOverrideController;
use App\Http\Controllers\ServiceAdminController;
use App\Http\Controllers\Site;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// ─── Site público ────────────────────────────────────────────
Route::get('/', [Site\HomeController::class, 'index'])->name('home');
// Página com os tipos de corte de cabelo e barba (os cards de Serviços da home levam pra cá)
Route::view('/cortes', 'site.cortes')->name('cortes');

// Webhook da InfinitePay (fora de qualquer middleware de autenticação)
Route::post('/webhooks/infinitepay', [WebhookController::class, 'handleInfinitePay'])
    ->name('webhooks.infinitepay');

// Avaliações e cupons (públicos)
Route::get('/avaliar/cliente', [ReviewController::class, 'form'])->name('reviews.form')->middleware('signed');
Route::post('/avaliar', [ReviewController::class, 'store'])->name('reviews.store');
Route::post('/avaliar/cupom', [ReviewController::class, 'generateCoupon'])->name('reviews.generateCoupon');
Route::post('/agendar/validar-cupom', [CouponController::class, 'validateCoupon'])->name('coupons.validate');

// ─── Agendamento (fluxo do cliente, sem login) ───────────────
Route::prefix('agendar')->name('appointments.')->group(function () {
    // Rotas fixas antes do {service?}, senão "sucesso"/"status" viram slug de serviço
    Route::get('/sucesso', function () {
        return view('appointments.success');
    })->name('success');

    Route::get('/status/{appointment}', [AppointmentController::class, 'status'])
        ->middleware('throttle:60,1')->name('status');

    Route::post('/confirmar', [AppointmentController::class, 'store'])->name('store');
    Route::get('/pagamento/{appointment}', [AppointmentController::class, 'pagamento'])
        ->middleware('signed')->name('pagamento');
    // A InfinitePay devolve o cliente pra cá depois do pagamento (com order_nsu, transaction_nsu e slug na URL)
    Route::get('/pagamento/{appointment}/retorno', [AppointmentController::class, 'retorno'])->name('retorno');
    Route::get('/{service?}', [AppointmentController::class, 'create'])->name('create');
});

// ─── Painel Admin ─────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Rotas do Breeze (verificação de e-mail / confirmar senha) redirecionam pra "dashboard":
    // admin vai pro painel, cliente pra "Minha conta"
    Route::get('/dashboard', fn () => redirect(AuthenticatedSessionController::destinoPadrao()))->name('dashboard');

    // Área do cliente
    Route::prefix('minha-conta')->name('cliente.')->controller(ClienteController::class)->group(function () {
        Route::get('/', 'index')->name('conta');
        Route::put('/dados', 'atualizar')->name('atualizar');
        Route::post('/agendamentos/{appointment}/cancelar', 'cancelar')->name('cancelar');
    });

    // Só entra quem tem is_admin = 1
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

        // Dashboard e marketing
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/aniversariantes', [AdminController::class, 'birthdays'])->name('birthdays');

        // Clientes e histórico
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/clientes', 'index')->name('customers');
            Route::get('/clientes/{id}', 'show')->name('customers.show');
        });
        Route::post('/clientes/sorteio', [CustomerController::class, 'draw'])->name('customers.draw');

        // Status dos agendamentos (Concluído / Faltou)
        Route::patch('/agendamentos/{id}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

        // Serviços (preço e duração)
        Route::get('/servicos', [ServiceAdminController::class, 'index'])->name('services.index');
        Route::put('/servicos/{id}', [ServiceAdminController::class, 'update'])->name('services.update');

        // Produtos (CRUD)
        Route::resource('products', ProductController::class);
        Route::post('/products/{id}/sell', [ProductController::class, 'sell'])->name('products.sell');

        // Agenda e relatórios
        Route::get('/agenda', [AdminController::class, 'agenda'])->name('agenda');
        Route::get('/relatorios', [AdminController::class, 'reports'])->name('reports');
        Route::post('/relatorios/transacao', [AdminController::class, 'storeTransaction'])->name('transactions.store');

        // Configurações
        Route::get('/configuracoes', [AdminController::class, 'settings'])->name('settings');
        Route::post('/configuracoes/update', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Agendamento avulso (modal da agenda) e horário especial por dia
        Route::post('/agenda/avulso', [AppointmentController::class, 'storeAvulso'])->name('appointments.avulso');
        Route::post('/agenda/horario', [ScheduleOverrideController::class, 'upsert'])->name('schedule.upsert');
        Route::post('/agenda/horario/restaurar', [ScheduleOverrideController::class, 'destroy'])->name('schedule.destroy');

        // Avaliações
        Route::get('/avaliacoes', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/avaliacoes/preview', function () {
            $user = \App\Models\User::where('is_admin', false)->first();
            return redirect(\URL::temporarySignedRoute('reviews.form', now()->addHours(1), ['user_id' => $user?->id ?? 1]));
        })->name('reviews.preview');

        // Cupons
        Route::resource('cupons', CouponController::class)->names('coupons');
        Route::patch('/cupons/{id}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
    });
});

require __DIR__.'/auth.php';
