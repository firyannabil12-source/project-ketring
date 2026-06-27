<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DuitkuCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('DUITKU CALLBACK RECEIVED', $request->all());

        $merchantCode = config('services.duitku.merchant_code');
        $apiKey = config('services.duitku.api_key');

        $merchantOrderId = $request->input('merchantOrderId');
        $amount = $request->input('amount');
        $signature = $request->input('signature');
        $reference = $request->input('reference');
        $resultCode = $request->input('resultCode');

        if (empty($merchantCode) || empty($apiKey)) {
            Log::error('DUITKU CALLBACK: merchant code or API key not configured');

            return response('Configuration error', 500);
        }

        if (empty($merchantOrderId) || empty($amount) || empty($signature)) {
            Log::error('DUITKU CALLBACK: missing required parameters', $request->all());

            return response('Bad parameter', 400);
        }

        $stringToSign = $merchantCode.$amount.$merchantOrderId;
        $validSignature = hash_hmac('sha256', $stringToSign, $apiKey);

        if (! hash_equals($validSignature, $signature)) {
            Log::error('DUITKU CALLBACK INVALID SIGNATURE', [
                'received' => $signature,
                'calculated' => $validSignature,
                'payload' => $request->all(),
            ]);

            return response('Invalid signature', 400);
        }

        $order = Order::find($merchantOrderId);

        if (! $order) {
            Log::error('DUITKU CALLBACK ORDER NOT FOUND', ['merchantOrderId' => $merchantOrderId]);

            return response('Order not found', 404);
        }

        // Hindari proses ulang jika sudah paid
        if ($order->payment_status === 'paid') {
            Log::info('DUITKU CALLBACK: order already paid', ['order_id' => $order->id]);

            return response('OK', 200);
        }

        if ($resultCode === '00') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'diproses',
                'reference' => $reference ?: $order->reference,
            ]);

            Log::info('DUITKU CALLBACK: payment success', ['order_id' => $order->id, 'reference' => $reference]);
        } else {
            $order->update([
                'payment_status' => 'expired',
                'reference' => $reference ?: $order->reference,
            ]);

            Log::info('DUITKU CALLBACK: payment failed/expired', ['order_id' => $order->id, 'resultCode' => $resultCode]);
        }

        return response('OK', 200);
    }
}
