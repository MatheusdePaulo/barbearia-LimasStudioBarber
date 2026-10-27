<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Cadastro de cliente. Quem já agendou sem conta (mesmo WhatsApp) ganha senha no cadastro que já existe,
 * assim os agendamentos antigos aparecem em "Minha conta".
 */
class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        AuthenticatedSessionController::lembrarVolta($request);

        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge(['whatsapp' => preg_replace('/\D/', '', (string) $request->whatsapp)]);

        $existente = User::where('whatsapp', $request->whatsapp)->first();

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'digits_between:10,11'],
            'email'    => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($existente?->id)],
            'birthday' => ['nullable', 'date', 'before:today'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'whatsapp.digits_between' => 'Informe o WhatsApp com DDD.',
            'email.unique'            => 'Esse e-mail já tem conta. Tente entrar.',
        ]);

        if ($existente && $existente->password) {
            return back()->withInput()->withErrors(['whatsapp' => 'Esse WhatsApp já tem conta. É só entrar com ele e sua senha.']);
        }

        $dados = [
            'name'     => $request->name,
            'whatsapp' => $request->whatsapp,
            'email'    => $request->email ?: $existente?->email,
            'birthday' => $request->birthday ?: $existente?->birthday,
            'password' => $request->password,
        ];

        $user = $existente ? tap($existente)->update($dados) : User::create($dados);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('cliente.conta', absolute: false))
            ->with('success', 'Conta criada! Bem-vindo à Lima\'s Studio Barber.');
    }
}
