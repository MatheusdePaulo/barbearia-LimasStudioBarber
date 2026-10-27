<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Coupon;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AgendamentoPagamentoTest extends TestCase
{
    use RefreshDatabase;

    private Service $corte;

    protected function setUp(): void
    {
        parent::setUp();
        $this->corte = Service::create(['name' => 'Corte', 'price' => 40, 'duration' => 30]);
    }

    private function agendar(string $tipo, array $extra = [])
    {
        return $this->post(route('appointments.store'), array_merge([
            'service_ids'    => [$this->corte->id],
            'date'           => now()->addDay()->format('Y-m-d'),
            'time'           => '10:00',
            'payment_type'   => $tipo,
            'guest_name'     => 'João Teste',
            'guest_whatsapp' => '(85) 99999-0000',
        ], $extra));
    }

    public function test_presencial_confirma_sem_pagamento(): void
    {
        Http::fake();

        $this->followingRedirects()->agendar('presencial')->assertOk()->assertSee('A pagar no dia')->assertSee('Agendado!', false);

        $a = Appointment::first();
        $this->assertSame('confirmed', $a->status);
        $this->assertSame('presencial', $a->payment_type);
        $this->assertEquals(0, $a->deposit_amount);
        $this->assertEquals(40, $a->valor_total);
        Http::assertNothingSent();
    }

    public function test_presencial_guarda_cupom_e_valor_com_desconto(): void
    {
        Coupon::create(['code' => 'AVA-TESTE', 'discount_percent' => 10, 'is_active' => true, 'max_uses' => 1, 'use_count' => 0]);

        $this->agendar('presencial', ['coupon_code' => 'ava-teste'])->assertRedirect();

        $a = Appointment::first();
        $this->assertSame('AVA-TESTE', $a->coupon_code);
        $this->assertEquals(36, $a->valor_total);
        $this->assertTrue((bool) Coupon::first()->used);
    }

    public function test_presencial_exige_whatsapp(): void
    {
        $this->agendar('presencial', ['guest_whatsapp' => ''])->assertSessionHasErrors('guest_whatsapp');
        $this->agendar('presencial', ['guest_whatsapp' => '9999'])->assertSessionHasErrors('guest_whatsapp');
        $this->assertSame(0, Appointment::count());
    }

    public function test_pagamento_no_site_exige_whatsapp(): void
    {
        $this->agendar('full', ['guest_whatsapp' => ''])->assertSessionHasErrors('guest_whatsapp');
        $this->assertSame(0, Appointment::count());
    }

    public function test_sinal_nao_e_mais_aceito(): void
    {
        $this->agendar('signal')->assertSessionHasErrors('payment_type');
        $this->assertSame(0, Appointment::count());
    }

    private function fakeLink(): void
    {
        Http::fake(['api.checkout.infinitepay.io/links' => Http::response(['url' => 'https://checkout.infinitepay.io/enosbarbeiro/abc'])]);
    }

    public function test_pagamento_no_site_segura_a_vaga_e_mostra_botao_pro_checkout_preenchido(): void
    {
        $this->fakeLink();

        config(['services.infinitepay.email_padrao' => 'barbearia@example.com']);

        // Sem campo de e-mail: muitos clientes não têm
        $resposta = $this->agendar('full');

        $a = Appointment::first();
        $this->assertStringContainsString('/agendar/pagamento/' . $a->id, $resposta->headers->get('Location'));
        $this->assertSame('pending', $a->status);
        $this->assertSame('full', $a->payment_type);
        $this->assertEquals(40, $a->deposit_amount);
        $this->assertSame('https://checkout.infinitepay.io/enosbarbeiro/abc', $a->payment_url);

        $this->get($resposta->headers->get('Location'))->assertOk()
            ->assertSee('Tempo restante para pagar')
            ->assertSee('https://checkout.infinitepay.io/enosbarbeiro/abc', false);

        // Checkout já vai com nome, e-mail padrão da barbearia e telefone, pra InfinitePay abrir preenchida
        Http::assertSent(fn ($req) => $req['handle'] === 'enosbarbeiro'
            && $req['order_nsu'] === 'agendamento-' . $a->id
            && $req['items'][0]['price'] === 4000
            && $req['customer'] === ['name' => 'João Teste', 'email' => 'barbearia@example.com', 'phone_number' => '+5585999990000']
            && $req['address']['cep'] === '62850000'
            && str_contains($req['redirect_url'], '/agendar/pagamento/' . $a->id . '/retorno'));

        // O e-mail padrão não vai pro cadastro do cliente
        $this->assertNull($a->user->email);
    }

    public function test_cliente_com_email_no_cadastro_recebe_no_proprio_email(): void
    {
        $this->fakeLink();
        $user = User::create(['name' => 'Maria Souza', 'whatsapp' => '85911112222', 'email' => 'maria@x.com', 'password' => 'segredo1']);

        $this->actingAs($user)->agendar('full', ['guest_name' => null, 'guest_whatsapp' => null])->assertRedirect();

        Http::assertSent(fn ($req) => $req['customer']['email'] === 'maria@x.com' && $req['customer']['phone_number'] === '+5585911112222');
    }

    public function test_erro_na_infinitepay_libera_o_horario(): void
    {
        Http::fake(['api.checkout.infinitepay.io/links' => Http::response(['error' => 'x'], 422)]);

        $this->agendar('full')->assertRedirect()->assertSessionHas('error');
        $this->assertSame(0, Appointment::withTrashed()->count());
    }

    private function pendente(): Appointment
    {
        return Appointment::create([
            'service_id' => $this->corte->id, 'client_name' => 'João', 'date' => now()->addDay(), 'time' => '10:00',
            'status' => 'pending', 'payment_type' => 'full', 'deposit_amount' => 40, 'payment_url' => 'https://checkout.infinitepay.io/x',
        ]);
    }

    private function fakeCheck(array $resposta): void
    {
        Http::fake(['api.checkout.infinitepay.io/payment_check' => Http::response($resposta)]);
    }

    public function test_webhook_confirma_so_depois_de_conferir_na_api(): void
    {
        $a = $this->pendente();
        $this->fakeCheck(['success' => true, 'paid' => true, 'amount' => 4000, 'paid_amount' => 4000, 'capture_method' => 'pix']);

        $this->postJson(route('webhooks.infinitepay'), [
            'order_nsu' => 'agendamento-' . $a->id, 'transaction_nsu' => 'tx-1', 'invoice_slug' => 'slug-1', 'amount' => 4000,
        ])->assertOk();

        $this->assertSame('confirmed', $a->fresh()->status);
        $this->assertSame('tx-1', $a->fresh()->payment_id);
        Http::assertSent(fn ($req) => $req['handle'] === 'enosbarbeiro' && $req['slug'] === 'slug-1');

        // A tela do agendamento (que consulta o status a cada 3s) vira "confirmado"
        $this->getJson(route('appointments.status', $a))->assertJson(['status' => 'confirmed']);
    }

    public function test_webhook_falso_nao_confirma(): void
    {
        $a = $this->pendente();
        $this->fakeCheck(['success' => true, 'paid' => false]);

        $this->postJson(route('webhooks.infinitepay'), [
            'order_nsu' => 'agendamento-' . $a->id, 'transaction_nsu' => 'tx-falso', 'invoice_slug' => 'slug-falso',
        ])->assertOk();

        $this->assertSame('pending', $a->fresh()->status);
    }

    public function test_valor_pago_menor_nao_confirma(): void
    {
        $a = $this->pendente();
        $this->fakeCheck(['success' => true, 'paid' => true, 'amount' => 100]);

        $this->postJson(route('webhooks.infinitepay'), [
            'order_nsu' => 'agendamento-' . $a->id, 'transaction_nsu' => 'tx', 'invoice_slug' => 's',
        ])->assertOk();

        $this->assertSame('pending', $a->fresh()->status);
    }

    public function test_retorno_do_cliente_confirma_e_mostra_agendado(): void
    {
        $a = $this->pendente();
        $this->fakeCheck(['success' => true, 'paid' => true, 'amount' => 4000, 'capture_method' => 'pix']);

        $url = URL::signedRoute('appointments.retorno', $a) . '&order_nsu=agendamento-' . $a->id . '&transaction_nsu=tx-2&slug=slug-2&capture_method=pix&receipt_url=https%3A%2F%2Fx';

        $this->followingRedirects()->get($url)->assertOk()->assertSee('Pago no site');
        $this->assertSame('confirmed', $a->fresh()->status);
    }

    public function test_retorno_e_tela_do_agendamento_exigem_link_assinado(): void
    {
        $a = $this->pendente();
        $this->get(route('appointments.retorno', $a))->assertForbidden();
        $this->get(route('appointments.pagamento', $a))->assertForbidden();
    }
}
