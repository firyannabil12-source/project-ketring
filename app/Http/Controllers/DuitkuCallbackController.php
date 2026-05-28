<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class DuitkuCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('DUITKU CALLBACK', $request->all());

        $merchantCode = config('services.duitku.merchant_code');
        $apiKey = config('services.duitku.api_key');

        $merchantOrderId = $request->merchantOrderId;
        $amount = $request->amount;
        $signature = $request->signature;

        $validSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

        if ($signature !== $validSignature) {
            Log::error('DUITKU CALLBACK INVALID SIGNATURE', $request->all());
            return response('Invalid signature', 400);
        }

        $order = Order::find($merchantOrderId);

        if (!$order) {
            return response('Order not found', 404);
        }

        if ($request->resultCode == '00') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'diproses',
            ]);
        } else {
            $order->update([
                'payment_status' => 'expired',
            ]);
        }

        return response('OK', 200);
    }
}   
