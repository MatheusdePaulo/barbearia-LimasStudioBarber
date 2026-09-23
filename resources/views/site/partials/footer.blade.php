{{-- ═══════════ FOOTER ═══════════ --}}
<footer style="background: #0A0A0A; border-top: 1px solid rgba(201,168,76,0.2);">

    <div style="max-width: 1100px; margin: 0 auto; padding: 32px 24px 24px; display: flex; flex-wrap: wrap; gap: 32px; justify-content: space-between;">

        {{-- Logo --}}
        <div style="flex: 1 1 160px; max-width: 200px; display: flex; align-items: center;">
            <img src="{{ asset('images/logo-limas02.png') }}" alt="Lima's Studio Barber"
                 style="height: 74px; width: auto; object-fit: contain;">
        </div>

        {{-- Endereço --}}
        <div style="flex: 1 1 200px;">
            <h4 style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; color: #C9A84C; margin: 0 0 10px;">
                Endereço
            </h4>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 13px; color: rgba(245,240,232,0.75); line-height: 1.7; margin: 0;">
                Rua Pedro Ciríaco, 909<br>
                Bairro Mutirão — Cascavel, CE
            </p>
        </div>

        {{-- Horário --}}
        <div style="flex: 1 1 180px;">
            <h4 style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; color: #C9A84C; margin: 0 0 10px;">
                Horário
            </h4>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 13px; color: rgba(245,240,232,0.75); line-height: 1.7; margin: 0;">
                Segunda a sábado<br>
                8h às 18h
            </p>
        </div>

        {{-- Contato --}}
        <div style="flex: 1 1 200px;">
            <h4 style="font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; color: #C9A84C; margin: 0 0 10px;">
                Contato
            </h4>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="https://wa.me/5585985430142" target="_blank" rel="noopener"
                   style="display: flex; align-items: center; gap: 10px; font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 13px; color: rgba(245,240,232,0.75); text-decoration: none; transition: color 0.25s ease;"
                   onmouseover="this.style.color='#C9A84C'"
                   onmouseout="this.style.color='rgba(245,240,232,0.75)'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink: 0;">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12.05 22h-.01c-1.827 0-3.618-.489-5.183-1.417l-.372-.221-3.855 1.011 1.03-3.759-.243-.386C2.3 15.633 1.76 13.856 1.76 12 1.76 6.334 6.35 1.744 12.05 1.744c2.758 0 5.35 1.075 7.298 3.024a10.257 10.257 0 0 1 3.02 7.294c-.002 5.665-4.593 10.26-10.318 10.26zm7.28-17.53A12.192 12.192 0 0 0 12.05 0C5.47 0 .12 5.35.117 11.933a11.9 11.9 0 0 0 1.588 5.985L.058 24l6.246-1.637a11.99 11.99 0 0 0 5.74 1.463h.005c6.579 0 11.93-5.352 11.933-11.933A11.86 11.86 0 0 0 19.33 4.47z"/>
                    </svg>
                    (85) 98543-0142
                </a>
                <a href="https://www.instagram.com/limasstudiobarber/" target="_blank" rel="noopener"
                   style="display: flex; align-items: center; gap: 10px; font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 13px; color: rgba(245,240,232,0.75); text-decoration: none; transition: color 0.25s ease;"
                   onmouseover="this.style.color='#C9A84C'"
                   onmouseout="this.style.color='rgba(245,240,232,0.75)'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="flex-shrink: 0;">
                        <rect x="2" y="2" width="20" height="20" rx="5"/>
                        <circle cx="12" cy="12" r="4.2"/>
                        <circle cx="17.6" cy="6.4" r="1" fill="currentColor" stroke="none"/>
                    </svg>
                    @limasstudiobarber
                </a>
            </div>
        </div>

    </div>

    {{-- Barra inferior — direitos autorais à esquerda, desenvolvido por à direita --}}
    <div style="border-top: 1px solid rgba(245,240,232,0.08); padding: 16px 0;">
        <div style="max-width: 1100px; margin: 0 auto; padding: 0 24px; display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between;">
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 12px; color: rgba(245,240,232,0.45); margin: 0;">
                © {{ date('Y') }} Lima's Studio Barber. Todos os direitos reservados.
            </p>
            <p style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 12px; color: rgba(245,240,232,0.45); margin: 0;">
                Desenvolvido por
                <a href="https://matheusdepaulo.com" target="_blank" rel="noopener" style="color: #C9A84C; text-decoration: none;">Matheus de Paulo</a>.
            </p>
        </div>
    </div>

</footer>
