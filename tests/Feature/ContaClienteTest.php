<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContaClienteTest extends TestCase
{
    use RefreshDatabase;

    private function cadastro(array $extra = [])
    {
        return $this->post(route('register'), array_merge([
            'name' => 'João Cliente', 'whatsapp' => '(85) 98888-7777', 'email' => '',
            'password' => 'segredo1', 'password_confirmation' => 'segredo1',
        ], $extra));
    }

    private function agendamento(User $user, string $quando, array $extra = []): Appointment
    {
        $inicio = now()->modify($quando);
        $servico = Service::firstOrCreate(['name' => 'Corte'], ['price' => 30, 'duration' => 30]);

        return Appointment::create(array_merge([
            'user_id' => $user->id, 'service_id' => $servico->id,
            'date' => $inicio->format('Y-m-d'), 'time' => $inicio->format('H:i'),
            'status' => 'confirmed', 'payment_type' => 'presencial', 'deposit_amount' => 0,
        ], $extra));
    }

    public function test_cadastro_cria_conta_e_cai_em_minha_conta(): void
    {
        $this->get(route('register'))->assertOk();
        $this->cadastro()->assertRedirect(route('cliente.conta'));

        $user = User::first();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('85988887777', $user->whatsapp);
        $this->assertFalse($user->is_admin);
        $this->get(route('cliente.conta'))->assertOk()->assertSee('Olá, João');
    }

    public function test_cadastro_aproveita_cliente_que_ja_agendou_sem_conta(): void
    {
        $guest = User::create(['name' => 'João', 'whatsapp' => '85988887777']);
        $this->agendamento($guest, '+2 days');

        $this->cadastro()->assertRedirect();

        $this->assertSame(1, User::count());
        $this->assertNotNull($guest->fresh()->password);
        $this->get(route('cliente.conta'))->assertSee('Corte');
    }

    public function test_whatsapp_com_conta_nao_cadastra_de_novo(): void
    {
        User::create(['name' => 'João', 'whatsapp' => '85988887777', 'password' => 'outrasenha']);

        $this->cadastro()->assertSessionHasErrors('whatsapp');
        $this->assertGuest();
    }

    public function test_login_por_whatsapp_leva_cliente_pra_minha_conta_e_admin_pro_painel(): void
    {
        User::create(['name' => 'João', 'whatsapp' => '85988887777', 'password' => 'segredo1']);
        $this->post(route('login'), ['login' => '(85) 98888-7777', 'password' => 'segredo1'])
            ->assertRedirect(route('cliente.conta'));

        $this->post(route('logout'));

        User::create(['name' => 'Enos', 'email' => 'enos@limas.com', 'password' => 'admin123', 'is_admin' => true]);
        $this->post(route('login'), ['login' => 'enos@limas.com', 'password' => 'admin123'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_volta_pra_pagina_de_agendamento(): void
    {
        User::create(['name' => 'João', 'whatsapp' => '85988887777', 'password' => 'segredo1']);

        $this->get(route('login', ['voltar' => '/agendar/cabelo']))->assertOk();
        $this->post(route('login'), ['login' => '85988887777', 'password' => 'segredo1'])
            ->assertRedirect(url('/agendar/cabelo'));
    }

    public function test_voltar_nao_aceita_site_externo(): void
    {
        User::create(['name' => 'João', 'whatsapp' => '85988887777', 'password' => 'segredo1']);

        $this->get(route('login', ['voltar' => '//site-malicioso.com']));
        $this->post(route('login'), ['login' => '85988887777', 'password' => 'segredo1'])
            ->assertRedirect(route('cliente.conta'));
    }

    public function test_cliente_cancela_presencial_com_antecedencia(): void
    {
        $user = User::create(['name' => 'João', 'whatsapp' => '85988887777', 'password' => 'segredo1']);
        $a = $this->agendamento($user, '+1 day');

        $this->actingAs($user)->post(route('cliente.cancelar', $a))->assertSessionHas('success');
        $this->assertSame('client_canceled', $a->fresh()->status);
    }

    public function test_nao_cancela_em_cima_da_hora_nem_pago_online_nem_de_outro_cliente(): void
    {
        $user = User::create(['name' => 'João', 'whatsapp' => '85988887777', 'password' => 'segredo1']);
        $outro = User::create(['name' => 'Pedro', 'whatsapp' => '85977776666', 'password' => 'segredo1']);

        $emCima = $this->agendamento($user, '+1 hour');
        $pago   = $this->agendamento($user, '+3 days', ['payment_type' => 'full', 'deposit_amount' => 30]);
        $alheio = $this->agendamento($outro, '+3 days');

        $this->actingAs($user)->post(route('cliente.cancelar', $emCima))->assertSessionHas('error');
        $this->actingAs($user)->post(route('cliente.cancelar', $pago))->assertSessionHas('error');
        $this->actingAs($user)->post(route('cliente.cancelar', $alheio))->assertNotFound();

        $this->assertSame('confirmed', $emCima->fresh()->status);
        $this->assertSame('confirmed', $pago->fresh()->status);
        $this->assertSame('confirmed', $alheio->fresh()->status);
    }

    public function test_cliente_nao_entra_no_painel_admin(): void
    {
        $user = User::create(['name' => 'João', 'whatsapp' => '85988887777', 'password' => 'segredo1']);
        $this->actingAs($user)->get(route('admin.dashboard'))->assertRedirect('/');
    }

    public function test_cliente_atualiza_os_dados(): void
    {
        $user = User::create(['name' => 'João', 'whatsapp' => '85988887777', 'password' => 'segredo1']);

        $this->actingAs($user)->put(route('cliente.atualizar'), [
            'name' => 'João Silva', 'whatsapp' => '(85) 91111-2222', 'email' => 'joao@x.com',
        ])->assertSessionHas('success');

        $this->assertSame('85911112222', $user->fresh()->whatsapp);
        $this->assertSame('joao@x.com', $user->fresh()->email);
    }
}
