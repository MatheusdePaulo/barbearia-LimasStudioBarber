<x-guest-layout>
    <div class="min-h-screen bg-[#0A0A0A] flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            {{-- HEADER COM IDENTIDADE --}}
            <div class="text-center mb-10">
                <a href="/" class="inline-block mb-6">
                    <img src="{{ asset('images/logo-limas02.png') }}" alt="Lima's Studio Barber" class="h-20 w-auto mx-auto">
                </a>
                <h1 class="text-2xl font-black italic text-white uppercase tracking-tighter">Entrar na Barbearia</h1>
                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-2">Acesse seu perfil para agendar seu corte</p>
            </div>

            <div class="bg-[#141414] border border-zinc-800 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-[#C9A84C]"></div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- LOGIN: EMAIL OU WHATSAPP --}}
                    <div class="space-y-2">
                        <label for="login" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">E-mail ou WhatsApp</label>
                        <x-text-input id="login" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="email@exemplo.com ou (85) 90000-0000" />
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    </div>

                    {{-- PASSWORD --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-2">
                            <label for="password" class="text-[9px] font-black uppercase text-zinc-500 italic tracking-widest">Senha</label>
                            @if (Route::has('password.request'))
                                <a class="text-[9px] font-black uppercase text-[#C9A84C] hover:underline" href="{{ route('password.request') }}">Esqueceu?</a>
                            @endif
                        </div>
                        <x-text-input id="password" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- REMEMBER ME --}}
                    <div class="flex items-center px-2">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-zinc-800 bg-zinc-900 text-[#C9A84C] focus:ring-0 shadow-none" name="remember">
                            <span class="ms-2 text-[10px] font-black text-zinc-500 uppercase italic tracking-widest">Lembrar de mim</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-[#C9A84C] text-black py-4 rounded-2xl font-black uppercase text-[11px] tracking-[0.2em] shadow-lg active:scale-95 transition-all">
                        Fazer Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
