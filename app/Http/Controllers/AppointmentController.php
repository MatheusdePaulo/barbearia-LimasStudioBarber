<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\ScheduleOverride;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\InfinitePay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AppointmentController extends Controller
{
    /**
     * EXIBE A TELA DE AGENDAMENTO
     */
    public function create(Request $request, $service_slug = 'geral')
    {
        $services = Service::all();

        // Serviços pré-selecionados via URL (?services=1,2,3 ou slug legado)
        $selectedIds = [];
        if ($request->has('services')) {
            $selectedIds = array_filter(explode(',', $request->get('services')));
        } elseif ($service_slug !== 'geral') {
            $bySlug = Service::where('slug', $service_slug)->first();
            if ($bySlug) $selectedIds = [$bySlug->id];
        }

        $selectedServices = $selectedIds
            ? Service::whereIn('id', $selectedIds)->get()
            : collect();

        $totalDuration = $selectedServices->sum('duration') ?: 30;
        $slotsNeeded   = (int) ceil($totalDuration / 30);

        $date       = $request->get('date', Carbon::today()->format('Y-m-d'));
        $isToday    = Carbon::parse($date)->isToday();
        $now        = Carbon::now();

        $allSlots  = ScheduleOverride::getSlotsForDate($date);
        $isClosed  = empty($allSlots);

        // Agendamentos existentes no dia (pending só bloqueia se criado há menos de 10 min)
        $appointments = Appointment::whereDate('date', $date)
            ->where(function ($q) {
                $q->where('status', 'confirmed')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'pending')
                         ->where('created_at', '>=', Carbon::now()->subMinutes(10));
                  });
            })
            ->with('services', 'service')
            ->get();

        // Monta lista de slots já bloqueados (com suporte a múltiplos serviços e legado)
        $bookedSlots = [];
        foreach ($appointments as $app) {
            $appDuration  = $app->total_duration;
            $appSlots     = (int) ceil($appDuration / 30);
            for ($i = 0; $i < $appSlots; $i++) {
                $bookedSlots[] = Carbon::parse($app->time)->addMinutes($i * 30)->format('H:i');
            }
        }

        // Para cada slot candidato, verifica se todos os slots consecutivos necessários estão livres
        $slots = [];
        foreach ($allSlots as $time) {
            $available = true;
            for ($i = 0; $i < $slotsNeeded; $i++) {
                $checkTime = Carbon::parse($date . ' ' . $time)->addMinutes($i * 30)->format('H:i');

                if (!in_array($checkTime, $allSlots)) {
                    $available = false;
                    break;
                }
                if (in_array($checkTime, $bookedSlots)) {
                    $available = false;
                    break;
                }
                if ($isToday && Carbon::parse($date . ' ' . $checkTime)->addMinutes(1)->lessThan($now)) {
                    $available = false;
                    break;
                }
            }

            $slots[] = ['time' => $time, 'available' => $available];
        }

        return view('appointments.create', [
            'services'         => $services,
            'selectedServices' => $selectedServices,
            'selectedIds'      => $selectedIds,
            'slots'            => $slots,
            'selectedDate'     => $date,
            'isClosed'         => $isClosed,
            'totalDuration'    => $totalDuration,
            'slotsNeeded'      => $slotsNeeded,
        ]);
    }

    /**
     * PROCESSA O AGENDAMENTO DO CLIENTE (logado ou guest)
     */
    public function store(Request $request)
    {
        $rules = [
            'service_ids'   => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'date'          => 'required|date',
            'time'          => 'required',
            'payment_type'  => 'required|in:presencial,full',
        ];

        if (! auth()->check()) {
            $rules['guest_name']     = 'required|string|max:255';
            // WhatsApp obrigatório: no pagar no dia é pro barbeiro falar com o cliente se ele faltar,
            // no pagar pelo site vai pré-preenchido no checkout da InfinitePay
            $rules['guest_whatsapp'] = ['required', 'string', 'max:20',
                function ($attribute, $value, $fail) {
                    if (strlen(preg_replace('/\D/', '', (string) $value)) < 10) {
                        $fail('Informe um WhatsApp válido, com DDD.');
                    }
                }];
            $rules['guest_birthday'] = 'nullable|date';
        }

        $rules['coupon_code'] = 'nullable|string|max:30';

        $request->validate($rules, [
            'guest_whatsapp.required'   => 'Informe seu WhatsApp com DDD.',
        ]);

        $services      = Service::whereIn('id', $request->service_ids)->get();
        $totalDuration = $services->sum('duration');
        $slotsNeeded   = (int) ceil($totalDuration / 30);

        for ($i = 0; $i < $slotsNeeded; $i++) {
            $checkTime = Carbon::parse($request->date . ' ' . $request->time)
                ->addMinutes($i * 30)->format('H:i');

            $exists = Appointment::whereDate('date', $request->date)
                ->where('time', $checkTime)
                ->where(function ($q) {
                    $q->where('status', 'confirmed')
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'pending')
                             ->where('created_at', '>=', Carbon::now()->subMinutes(10));
                      });
                })
                ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'Ops! Um dos horários necessários já está ocupado. Escolha outro.');
            }
        }

        try {
            // Resolve o usuário (logado, guest com whatsapp, ou anônimo)
            if (auth()->check()) {
                $user       = auth()->user();
                $clientName = null;
            } else {
                $guestName     = $request->guest_name;
                $guestWhatsapp = $request->filled('guest_whatsapp')
                    ? preg_replace('/[^0-9]/', '', $request->guest_whatsapp)
                    : null;
                $guestBirthday = $request->filled('guest_birthday') ? $request->guest_birthday : null;

                if ($guestWhatsapp) {
                    $user = User::where('whatsapp', $guestWhatsapp)->first();
                    if ($user) {
                        if (! $user->birthday && $guestBirthday) {
                            $user->update(['birthday' => $guestBirthday]);
                        }
                    } else {
                        $user = User::create([
                            'name'     => $guestName,
                            'whatsapp' => $guestWhatsapp,
                            'birthday' => $guestBirthday,
                        ]);
                    }
                    $clientName = null;
                } else {
                    $user       = null;
                    $clientName = $guestName;
                }
            }

            // Valida cupom (se informado)
            $coupon = null;
            if ($request->filled('coupon_code')) {
                $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
                if (! $coupon || ! $coupon->isValid()) {
                    return redirect()->back()->with('error', 'Cupom inválido ou já utilizado.');
                }
            }

            // Valor do serviço com o desconto do cupom (no presencial é o que o cliente paga no dia)
            $amount = $services->sum(fn($s) => (float) $s->active_price);
            if ($coupon) {
                $amount = round($amount * (1 - $coupon->discount_percent / 100), 2);
                $amount = max($amount, 0.01);
            }

            $online = $request->payment_type === 'full';

            // Presencial já entra confirmado; pagamento pelo site fica pendente até o Pix ser aprovado
            $appointment = Appointment::create([
                'user_id'        => $user?->id,
                'client_name'    => $clientName,
                'service_id'     => $services->first()->id,
                'date'           => $request->date,
                'time'           => $request->time,
                'status'         => $online ? 'pending' : 'confirmed',
                'payment_type'   => $request->payment_type,
                'deposit_amount' => $online ? $amount : 0,
                'coupon_code'    => $coupon?->code,
            ]);

            $appointment->services()->attach($request->service_ids);

            if ($online) {
                $displayName = $user ? $user->name : ($request->guest_name ?? 'Cliente');
                // Muitos clientes não têm e-mail: o checkout da InfinitePay vai com o e-mail padrão da barbearia
                // (pra pular a etapa "Contato"). Quem tem e-mail no cadastro recebe o comprovante no próprio.
                $email = filter_var($user?->email, FILTER_VALIDATE_EMAIL) ? $user->email : config('services.infinitepay.email_padrao');

                try {
                    $paymentUrl = InfinitePay::createLink(
                        $appointment,
                        $amount,
                        "Lima's Studio Barber: " . $services->pluck('name')->join(', '),
                        [
                            'name'         => $displayName,
                            'email'        => $email,
                            'phone_number' => $user?->whatsapp ? '+55' . $user->whatsapp : null,
                        ],
                        URL::signedRoute('appointments.retorno', $appointment),
                    );
                } catch (\Throwable $e) {
                    // Sem link não tem como pagar: libera o horário
                    $appointment->services()->detach();
                    $appointment->forceDelete();
                    throw $e;
                }

                $appointment->update(['payment_url' => $paymentUrl]);
            }

            // Marca cupom como usado
            if ($coupon) {
                $coupon->increment('use_count');
                if ($coupon->max_uses > 0 && $coupon->use_count >= $coupon->max_uses) {
                    $coupon->update(['used' => true, 'used_at' => now()]);
                }
            }

            // Redireciona (em vez de mostrar a tela no POST) pra um F5 não tentar agendar de novo
            return redirect(URL::signedRoute('appointments.pagamento', $appointment));

        } catch (\Throwable $e) {
            Log::error('Erro ao agendar: ' . $e->getMessage());

            $final = config('app.debug') ? $e->getMessage() : 'Tente novamente em instantes.';
            return redirect()->back()->with('error', 'Erro ao processar o agendamento. ' . $final);
        }
    }

    /**
     * TELA DO AGENDAMENTO: timer segurando a vaga + botão pro checkout da InfinitePay (pendente),
     * ou horário confirmado. Link assinado, pra ninguém ver o agendamento dos outros trocando o número na URL.
     */
    public function pagamento(Appointment $appointment)
    {
        return $this->telaConfirmacao($appointment);
    }

    /**
     * RETORNO DO CHECKOUT DA INFINITEPAY
     * A InfinitePay devolve o cliente pra cá com order_nsu, transaction_nsu e slug na URL.
     * Confere o pagamento na API antes de confirmar e manda pra tela do agendamento.
     * Link assinado (ignorando os parâmetros que a InfinitePay acrescenta), senão daria pra abrir
     * o agendamento de qualquer um trocando o número na URL.
     */
    public function retorno(Request $request, Appointment $appointment)
    {
        $paramsInfinitePay = ['receipt_url', 'order_nsu', 'slug', 'capture_method', 'transaction_nsu'];
        abort_unless($request->hasValidSignatureWhileIgnoring($paramsInfinitePay), 403);

        if ($request->filled(['transaction_nsu', 'slug']) && $request->order_nsu === InfinitePay::orderNsu($appointment)) {
            try {
                InfinitePay::confirmIfPaid($request->order_nsu, $request->transaction_nsu, $request->slug);
            } catch (\Throwable $e) {
                // O webhook ainda pode confirmar; a tela continua consultando o status
                Log::warning("Retorno InfinitePay do agendamento {$appointment->id}: " . $e->getMessage());
            }
        }

        return redirect(URL::signedRoute('appointments.pagamento', $appointment));
    }

    /**
     * STATUS (consultado pela tela do agendamento a cada 3s): vira "confirmed" quando o webhook
     * ou o retorno da InfinitePay confirmam o pagamento.
     */
    public function status(Appointment $appointment)
    {
        return response()->json(['status' => $appointment->status]);
    }

    private function telaConfirmacao(Appointment $appointment)
    {
        $settings            = Setting::pluck('value', 'key');
        $reviewCouponActive  = (bool) ($settings['review_coupon_active']  ?? true);
        $reviewCouponPercent = (int)  ($settings['review_coupon_percent'] ?? 5);

        return view('appointments.success', compact('appointment', 'reviewCouponActive', 'reviewCouponPercent'));
    }

    /**
     * AGENDAMENTO MANUAL (WALK-IN) pelo admin
     */
    public function storeAvulso(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'service_id'  => 'required|exists:services,id',
            'date'        => 'required|date',
            'time'        => 'required',
        ]);

        $service  = Service::findOrFail($validated['service_id']);
        $duration = $service->total_duration ?? $service->duration ?? 30;
        $slotsNeeded = (int) ceil($duration / 30);

        for ($i = 0; $i < $slotsNeeded; $i++) {
            $checkTime = Carbon::parse($validated['date'] . ' ' . $validated['time'])
                ->addMinutes($i * 30)->format('H:i');

            $exists = Appointment::whereDate('date', $validated['date'])
                ->where('time', $checkTime)
                ->where(function ($q) {
                    $q->where('status', 'confirmed')
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'pending')
                             ->where('created_at', '>=', Carbon::now()->subMinutes(10));
                      });
                })
                ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'Horário já ocupado no banco de dados!');
            }
        }

        try {
            $appointment = Appointment::create([
                'user_id'        => null,
                'client_name'    => $validated['client_name'],
                'service_id'     => $validated['service_id'],
                'date'           => $validated['date'],
                'time'           => $validated['time'],
                'status'         => 'confirmed',
                'deposit_amount' => 0.00,
            ]);

            $appointment->services()->attach($validated['service_id']);

            return redirect()->back()->with('success', 'Agendamento registrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status atualizado!');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return redirect()->back()->with('success', 'Agendamento removido.');
    }
}
