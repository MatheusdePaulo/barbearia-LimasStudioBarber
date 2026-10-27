<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * "Minha conta" do cliente: próximos horários, histórico, cancelar e editar os dados.
 */
class ClienteController extends Controller
{
    // Antecedência mínima pra o próprio cliente cancelar pelo site
    public const HORAS_PARA_CANCELAR = 2;

    public function index()
    {
        $user = Auth::user();

        $agendamentos = $user->appointments()
            ->with('services', 'service')
            ->whereNotIn('status', ['expired'])
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        $agora = now();
        [$proximos, $historico] = $agendamentos->partition(fn ($a) =>
            in_array($a->status, ['confirmed', 'pending']) && self::inicio($a)->greaterThan($agora)
        );

        return view('cliente.conta', [
            'user'      => $user,
            'proximos'  => $proximos->sortBy(fn ($a) => self::inicio($a))->values(),
            'historico' => $historico->values(),
        ]);
    }

    public function cancelar(Appointment $appointment)
    {
        abort_unless($appointment->user_id === Auth::id(), 404);

        if (! self::podeCancelar($appointment)) {
            return back()->with('error', 'Esse horário não pode mais ser cancelado pelo site. Fale com a barbearia no WhatsApp.');
        }

        // Status próprio: no painel "canceled" é o botão "Faltou", e cancelar com antecedência não é falta
        $appointment->update(['status' => 'client_canceled']);

        return back()->with('success', 'Horário cancelado. O horário foi liberado na agenda.');
    }

    public function atualizar(Request $request)
    {
        $user = Auth::user();
        $request->merge(['whatsapp' => preg_replace('/\D/', '', (string) $request->whatsapp)]);

        $dados = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'digits_between:10,11', Rule::unique('users', 'whatsapp')->ignore($user->id)],
            'email'    => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'birthday' => ['nullable', 'date', 'before:today'],
        ], [
            'whatsapp.digits_between' => 'Informe o WhatsApp com DDD.',
            'whatsapp.unique'         => 'Esse WhatsApp já está em outra conta.',
        ]);

        $user->update($dados);

        return back()->with('success', 'Dados atualizados.');
    }

    // Cancelamento pelo site só no "pagar no dia" e com antecedência; quem pagou online fala com a barbearia (estorno)
    public static function podeCancelar(Appointment $a): bool
    {
        return $a->status === 'confirmed'
            && $a->payment_type !== 'full'
            && self::inicio($a)->greaterThan(now()->addHours(self::HORAS_PARA_CANCELAR));
    }

    public static function inicio(Appointment $a): Carbon
    {
        return Carbon::parse(Carbon::parse($a->date)->format('Y-m-d') . ' ' . $a->time);
    }
}
