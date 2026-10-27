<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(Request $request): View
    {
        self::lembrarVolta($request);

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(self::destinoPadrao());
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Admin cai no painel; cliente cai em "Minha conta"
    public static function destinoPadrao(): string
    {
        return Auth::user()?->is_admin
            ? route('admin.dashboard', absolute: false)
            : route('cliente.conta', absolute: false);
    }

    // ?voltar=/agendar/cabelo: depois de entrar (ou criar conta) o cliente volta pra onde estava.
    // Só aceita caminho interno, pra ninguém usar o link pra mandar o cliente pra outro site.
    public static function lembrarVolta(Request $request): void
    {
        $voltar = (string) $request->query('voltar', '');
        if (str_starts_with($voltar, '/') && ! str_starts_with($voltar, '//') && ! str_contains($voltar, '\\')) {
            $request->session()->put('url.intended', url($voltar));
        }
    }
}
