<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    // GET: ambil isi cart (JSON)
    public function get()
    {
        $cart = session('cart', []);
        return response()->json([
            'cart'  => array_values($cart),
            'count' => array_sum(array_column($cart, 'quantity')),
            'total' => array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart)),
        ]);
    }

    // POST: tambah item ke cart
    public function add(Request $request)
    {
        $request->validate([
            'menu_id'  => 'required|exists:menus,id',
            'quantity' => 'integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        $qty  = (int) $request->input('quantity', 1);

        $cart = session('cart', []);
        $key  = 'menu_' . $menu->id;

        if (isset($cart[$key])) {
            $newQty = $cart[$key]['quantity'] + $qty;
            $cart[$key]['quantity'] = $newQty;
        } else {
            $cart[$key] = [
                'menu_id'  => $menu->id,
                'name'     => $menu->name,
                'category' => $menu->category,
                'price'    => (float) $menu->price,
                'image'    => $menu->image,
                'quantity' => $qty,
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => "\"{$menu->name}\" ditambahkan ke keranjang!",
            'count'   => array_sum(array_column($cart, 'quantity')),
            'total'   => array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart)),
        ]);
    }

    // POST: kurangi / hapus item dari cart
    public function remove(Request $request)
    {
        $request->validate(['menu_id' => 'required']);
        $cart = session('cart', []);
        $key  = 'menu_' . $request->menu_id;

        unset($cart[$key]);
        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'count'   => array_sum(array_column($cart, 'quantity')),
            'total'   => array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart)),
        ]);
    }

    // POST: update quantity item di cart
    public function update(Request $request)
    {
        $request->validate([
            'menu_id'  => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = session('cart', []);
        $key  = 'menu_' . $request->menu_id;

        if ($request->quantity == 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $menu = Menu::find($request->menu_id);
            $cart[$key]['quantity'] = (int) $request->quantity;
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'count'   => array_sum(array_column($cart, 'quantity')),
            'total'   => array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart)),
        ]);
    }

    // POST: checkout — simpan pesanan ke DB
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'event_date'     => 'required|date|after_or_equal:today',
            'event_address'  => 'required|string|max:500',
            'payment_method' => 'required|string|in:duitku',
            'notes'          => 'nullable|string|max:1000',
        ], [
            'customer_name.required'  => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor HP wajib diisi.',
            'event_date.required'     => 'Tanggal acara wajib diisi.',
            'event_date.after_or_equal' => 'Tanggal acara tidak boleh di masa lalu.',
            'event_address.required'  => 'Alamat acara wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang Anda kosong. Tambahkan menu terlebih dahulu.');
        }

        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));

        // Set waktu expired (misal 10 menit dari sekarang jika pakai Duitku)
        $expiresAt = null;
        if ($request->payment_method === 'duitku') {
            $expiresAt = now()->addMinutes(10);
        }

        // Simpan order
        $order = Order::create([
            'user_id'            => \Illuminate\Support\Facades\Auth::id(),
            'customer_name'      => $request->customer_name,
            'customer_phone'     => $request->customer_phone,
            'event_date'         => $request->event_date,
            'event_address'      => $request->event_address,
            'total_price'        => $total,
            'status'             => 'pending',
            'notes'              => $request->notes,
            'payment_method'     => $request->payment_method,
            'payment_status'     => 'unpaid',
            'payment_expires_at' => $expiresAt,
        ]);

        // Simpan order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id'  => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price'    => $item['price'],
            ]);
        }

        // Kosongkan cart & simpan order_id di session untuk tracking
        session()->forget('cart');
        $trackedOrders = session('tracked_orders', []);
        $trackedOrders[] = $order->id;
        session(['tracked_orders' => $trackedOrders]);

        // PROSES DUITKU JIKA DIPILIH
        if ($request->payment_method === 'duitku') {
            $merchantCode = config('services.duitku.merchant_code');
            $apiKey = config('services.duitku.api_key');
            $env = config('services.duitku.env', 'sandbox');
            $callbackUrl = config('services.duitku.callback_url');
            $returnUrl = config('services.duitku.return_url');

            $merchantOrderId = (string) $order->id;
            $paymentAmount = (int) $total;
            $signature = md5($merchantCode . $merchantOrderId . $paymentAmount . $apiKey);

            $params = [
                'merchantCode' => $merchantCode,
                'paymentAmount' => $paymentAmount,
                'merchantOrderId' => $merchantOrderId,
                'productDetails' => 'Pesanan Katering #ORD-' . $order->id,
                'email' => 'customer@ketringmamaiksan.com', // Dummy email if not available
                'phoneNumber' => $order->customer_phone,
                'customerVaName' => $order->customer_name,
                'callbackUrl' => $callbackUrl,
                'returnUrl' => $returnUrl,
                'signature' => $signature,
                'expiryPeriod' => 10
            ];

            $url = $env === 'sandbox' 
                ? 'https://api-sandbox.duitku.com/api/merchant/createinvoice' 
                : 'https://api-prod.duitku.com/api/merchant/createinvoice';

            try {
                $response = \Illuminate\Support\Facades\Http::post($url, $params);
                $result = $response->json();

                if (isset($result['statusCode']) && $result['statusCode'] == '00') {
                    $order->update([
                        'payment_url' => $result['paymentUrl'],
                        'reference'   => $result['reference']
                    ]);
                    
                    // Redirect to Duitku payment page
                    return redirect($result['paymentUrl']);
                } else {
                    \Illuminate\Support\Facades\Log::error('Duitku Error Response Body: ' . $response->body());
                    \Illuminate\Support\Facades\Log::error('Duitku Error Parsed JSON: ' . json_encode($result));
                    return redirect()->route('orders')->with('error', 'Gagal membuat link pembayaran Duitku. Silakan coba lagi atau hubungi admin.');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Duitku Exception: ' . $e->getMessage());
                return redirect()->route('orders')->with('error', 'Terjadi kesalahan sistem saat menghubungi payment gateway.');
            }
        }

        return redirect()->route('orders')
            ->with('success', "Pesanan #ORD-{$order->id} berhasil dibuat! Kami akan segera memproses pesanan Anda.")
            ->with('new_order_id', $order->id);
    }

    // POST: callback dari Duitku
    public function callback(Request $request)
    {
        $merchantCode = config('services.duitku.merchant_code');
        $apiKey = config('services.duitku.api_key');

        $merchantOrderId = $request->input('merchantOrderId');
        $amount = $request->input('amount');
        $resultCode = $request->input('resultCode');
        $signature = $request->input('signature');
        $reference = $request->input('reference');

        if (!$merchantOrderId || !$amount || !$signature) {
            return response()->json(['success' => false, 'message' => 'Invalid parameters'], 400);
        }

        // Verify signature: md5(merchantCode + amount + merchantOrderId + apiKey)
        $expectedSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

        if ($signature !== $expectedSignature) {
            \Illuminate\Support\Facades\Log::warning('Duitku Callback: Invalid Signature', $request->all());
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
        }

        $order = Order::find($merchantOrderId);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // resultCode: 00 = Success, 01 = Failed, 02 = Expired
        if ($resultCode == '00') {
            $order->update([
                'payment_status' => 'paid',
                // Optional: update status to diproses automatically
                // 'status' => 'diproses' 
            ]);
        } else if ($resultCode == '01' || $resultCode == '02') {
            $order->update([
                'payment_status' => 'expired'
            ]);
        }

        return response()->json(['success' => true]);
    }
}
