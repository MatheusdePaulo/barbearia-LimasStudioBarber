<?php

namespace App\Http\Controllers;

use App\Services\InfinitePay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Webhook da InfinitePay: chega quando o cliente paga o checkout.
     * O payload não é assinado, então o pagamento é sempre conferido no payment_check antes de confirmar.
     * Responder 400 faz a InfinitePay tentar de novo.
     */
    public function handleInfinitePay(Request $request)
    {
        Log::info('Webhook InfinitePay recebido:', $request->only(['order_nsu', 'transaction_nsu', 'invoice_slug', 'capture_method']));

        $orderNsu       = (string) $request->input('order_nsu');
        $transactionNsu = (string) $request->input('transaction_nsu');
        $slug           = (string) $request->input('invoice_slug');

        if (! InfinitePay::appointmentIdFromOrderNsu($orderNsu) || $transactionNsu === '' || $slug === '') {
            return response()->json(['error' => 'Payload inválido'], 400);
        }

        try {
            InfinitePay::confirmIfPaid($orderNsu, $transactionNsu, $slug);
        } catch (\Throwable $e) {
            Log::error('Erro no webhook InfinitePay: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao conferir pagamento'], 400);
        }

        return response()->json(['status' => 'received'], 200);
    }
}
