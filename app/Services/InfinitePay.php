<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Checkout integrado da InfinitePay (Pix e cartão).
 * A InfinitePay não gera o QR Code pra mostrar no nosso site nem deixa o checkout ser embutido num iframe:
 * o cliente sempre paga na página dela. Por isso mandamos nome, e-mail, telefone e o endereço da barbearia:
 * assim ela pula "Contato" e "Entrega" e abre direto na escolha do pagamento (um toque em Pix mostra o QR).
 * Docs: https://www.infinitepay.io/checkout-documentacao
 */
class InfinitePay
{
    private const BASE_URL = 'https://api.checkout.infinitepay.io';

    public static function handle(): string
    {
        return ltrim((string) config('services.infinitepay.handle'), '$');
    }

    // Identificador do agendamento enviado pra InfinitePay e devolvido no webhook/redirect
    public static function orderNsu(Appointment $appointment): string
    {
        return 'agendamento-' . $appointment->id;
    }

    public static function appointmentIdFromOrderNsu(?string $orderNsu): ?int
    {
        return preg_match('/^agendamento-(\d+)$/', (string) $orderNsu, $m) ? (int) $m[1] : null;
    }

    /**
     * Cria o link de pagamento e devolve a URL do checkout.
     * $customer: name, email, phone_number (+55DDDNUMERO), pra o checkout já abrir preenchido.
     */
    public static function createLink(Appointment $appointment, float $amount, string $description, array $customer, string $redirectUrl): string
    {
        if (self::handle() === '') {
            throw new RuntimeException('InfinitePay sem InfiniteTag (INFINITEPAY_HANDLE no .env).');
        }

        $body = [
            'handle'       => self::handle(),
            'order_nsu'    => self::orderNsu($appointment),
            'redirect_url' => $redirectUrl,
            'items'        => [[
                'quantity'    => 1,
                'price'       => (int) round($amount * 100), // em centavos
                'description' => $description,
            ]],
            'customer'     => array_filter($customer),
            'address'      => config('services.infinitepay.endereco'),
        ];

        // A InfinitePay não alcança localhost: em dev a confirmação fica só pelo retorno do cliente
        $webhookUrl = route('webhooks.infinitepay');
        if (! preg_match('#^https?://(localhost|127\.0\.0\.1)#', $webhookUrl)) {
            $body['webhook_url'] = $webhookUrl;
        }

        $response = Http::acceptJson()->timeout(15)->post(self::BASE_URL . '/links', $body);
        $url = $response->json('url') ?? $response->json('link') ?? $response->json('checkout_url');

        if (! $response->successful() || ! $url) {
            Log::error('InfinitePay: erro ao criar link', ['status' => $response->status(), 'body' => $response->body()]);
            throw new RuntimeException('Não foi possível gerar o link de pagamento.');
        }

        return $url;
    }

    /**
     * Consulta na InfinitePay se o pagamento foi aprovado.
     * Retorna o JSON da API ({success, paid, amount, paid_amount, installments, capture_method}).
     */
    public static function checkPayment(string $orderNsu, string $transactionNsu, string $slug): array
    {
        $response = Http::acceptJson()->timeout(15)->post(self::BASE_URL . '/payment_check', [
            'handle'          => self::handle(),
            'order_nsu'       => $orderNsu,
            'transaction_nsu' => $transactionNsu,
            'slug'            => $slug,
        ]);

        if (! $response->successful()) {
            Log::error('InfinitePay: erro no payment_check', ['status' => $response->status(), 'body' => $response->body()]);
            throw new RuntimeException('Não foi possível consultar o pagamento.');
        }

        return $response->json() ?? [];
    }

    /**
     * Confere o pagamento na API e confirma o agendamento se estiver pago com o valor certo.
     * Usado pelo webhook e pelo retorno do cliente; nunca confia só nos dados recebidos.
     */
    public static function confirmIfPaid(string $orderNsu, string $transactionNsu, string $slug): ?Appointment
    {
        $id = self::appointmentIdFromOrderNsu($orderNsu);
        $appointment = $id ? Appointment::find($id) : null;
        if (! $appointment || $appointment->status === 'confirmed') {
            return $appointment;
        }

        $check = self::checkPayment($orderNsu, $transactionNsu, $slug);
        $esperado = (int) round((float) $appointment->deposit_amount * 100);

        if (empty($check['paid']) || (int) ($check['amount'] ?? 0) < $esperado) {
            return $appointment;
        }

        // Confirma mesmo se já tinha expirado: o cliente pagou, o barbeiro resolve um eventual conflito
        $appointment->update([
            'status'     => 'confirmed',
            'payment_id' => $transactionNsu,
        ]);
        Log::info("Agendamento {$appointment->id} confirmado via InfinitePay (" . ($check['capture_method'] ?? '?') . ').');

        return $appointment;
    }
}
