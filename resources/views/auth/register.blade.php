<x-guest-layout>
    <div class="min-h-screen bg-[#0A0A0A] flex items-center justify-center p-4 py-12">
        <div class="w-full max-w-md">
            {{-- HEADER COM IDENTIDADE --}}
            <div class="text-center mb-10">
                <a href="/" class="inline-block mb-6">
                    <img src="{{ asset('images/logo-limas02.webp') }}" alt="Lima's Studio Barber" class="h-20 w-auto mx-auto">
                </a>
                <h1 class="text-2xl font-black italic text-white uppercase tracking-tighter">Criar sua conta</h1>
                <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest mt-2">Agende mais rápido e acompanhe seus horários</p>
            </div>

            <div class="bg-[#141414] border border-zinc-800 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-[#C9A84C]"></div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- NOME --}}
                    <div class="space-y-2">
                        <label for="name" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Nome</label>
                        <x-text-input id="name" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Seu nome completo" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- WHATSAPP --}}
                    <div class="space-y-2">
                        <label for="whatsapp" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">WhatsApp</label>
                        <x-text-input id="whatsapp" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0" type="tel" name="whatsapp" :value="old('whatsapp')" required autocomplete="tel" placeholder="(85) 90000-0000" />
                        <p class="text-zinc-600 text-[10px] ml-2">Você também pode entrar usando o WhatsApp.</p>
                        <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                    </div>

                    {{-- E-MAIL --}}
                    <div class="space-y-2">
                        <label for="email" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">E-mail <span class="text-zinc-700">(opcional)</span></label>
                        <x-text-input id="email" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0" type="email" name="email" :value="old('email')" autocomplete="email" placeholder="email@exemplo.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- NASCIMENTO --}}
                    <div class="space-y-2">
                        <label for="birthday" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Data de nascimento <span class="text-zinc-700">(opcional)</span></label>
                        <x-text-input id="birthday" style="color-scheme: dark;" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0" type="date" name="birthday" :value="old('birthday')" />
                        <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
                    </div>

                    {{-- SENHA --}}
                    <div class="space-y-2">
                        <label for="password" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Senha</label>
                        <x-text-input id="password" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo de 6 caracteres" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-[9px] font-black uppercase text-zinc-500 ml-2 italic tracking-widest">Confirmar senha</label>
                        <x-text-input id="password_confirmation" class="block w-full bg-zinc-900 border-zinc-800 rounded-2xl py-4 px-5 text-white text-sm focus:border-[#C9A84C] focus:ring-0" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </div>

                    <button type="submit" class="w-full bg-[#C9A84C] text-black py-4 rounded-2xl font-black uppercase text-[11px] tracking-[0.2em] shadow-lg active:scale-95 transition-all">
                        Criar conta
                    </button>
                </form>
            </div>

            <p class="text-center text-zinc-500 text-[11px] mt-6">
                Já tem conta?
                <a href="{{ route('login') }}" class="text-[#C9A84C] font-bold hover:underline">Entrar</a>
            </p>
            <p class="text-center mt-3">
                <a href="{{ url('/') }}" class="text-zinc-600 text-[10px] uppercase tracking-widest hover:text-zinc-400">Voltar ao site</a>
            </p>
        </div>
    </div>
</x-guest-layout>
