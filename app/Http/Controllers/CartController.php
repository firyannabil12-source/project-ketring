<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // GET: ambil isi cart (JSON)
    public function get()
    {
        $cart = session('cart', []);

        return response()->json([
            'cart' => array_values($cart),
            'count' => array_sum(array_column($cart, 'quantity')),
            'total' => array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $cart)),
        ]);
    }

    // POST: tambah item ke cart
    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'integer|min:10',
        ], [
            'quantity.min' => 'Minimal pembelian 10 porsi untuk satu paket menu.',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        if (!$menu->is_active) {
            return response()->json([
                'success' => false,
                'message' => "Menu \"{$menu->name}\" saat ini sedang tidak aktif / tidak tersedia.",
            ], 422);
        }
        $qty = (int) $request->input('quantity', 1);

        $cart = session('cart', []);
        $key = 'menu_'.$menu->id;
        $currentQty = $cart[$key]['quantity'] ?? 0;

        if (isset($cart[$key])) {
            $newQty = $currentQty + $qty;
            $cart[$key]['quantity'] = $newQty;
        } else {
            $cart[$key] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'category' => $menu->category,
                'price' => (float) $menu->price,
                'image' => $menu->image,
                'quantity' => $qty,
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => "\"{$menu->name}\" ditambahkan ke keranjang!",
            'count' => array_sum(array_column($cart, 'quantity')),
            'total' => array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $cart)),
        ]);
    }

    // POST: kurangi / hapus item dari cart
    public function remove(Request $request)
    {
        $request->validate(['menu_id' => 'required']);
        $cart = session('cart', []);
        $key = 'menu_'.$request->menu_id;

        unset($cart[$key]);
        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'count' => array_sum(array_column($cart, 'quantity')),
            'total' => array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $cart)),
        ]);
    }

    // POST: update quantity item di cart
    public function update(Request $request)
    {
        $request->validate([
            'menu_id' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($request->quantity > 0 && $request->quantity < 10) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian 10 porsi untuk satu paket menu.',
            ], 422);
        }

        $cart = session('cart', []);
        $key = 'menu_'.$request->menu_id;

        if ($request->quantity == 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $menu = Menu::find($request->menu_id);
            if (! $menu || ! $menu->is_active) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = (int) $request->quantity;
            }
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'count' => array_sum(array_column($cart, 'quantity')),
            'total' => array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $cart)),
        ]);
    }

    // POST: checkout simpan pesanan ke DB
    public function checkout(Request $request)
    {
        $jabodetabekBounds = [
            'min_lat' => -6.90,
            'max_lat' => -5.90,
            'min_lng' => 106.35,
            'max_lng' => 107.25,
        ];

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'event_date' => 'required|date|after_or_equal:today',
            'event_address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'payment_method' => 'required|string|in:duitku',
            'notes' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor HP wajib diisi.',
            'event_date.required' => 'Tanggal acara wajib diisi.',
            'event_date.after_or_equal' => 'Tanggal acara tidak boleh di masa lalu.',
            'event_address.required' => 'Alamat acara wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        if ($request->filled('latitude') || $request->filled('longitude')) {
            $lat = (float) $request->latitude;
            $lng = (float) $request->longitude;

            if (
                ! $request->filled('latitude') ||
                ! $request->filled('longitude') ||
                $lat < $jabodetabekBounds['min_lat'] ||
                $lat > $jabodetabekBounds['max_lat'] ||
                $lng < $jabodetabekBounds['min_lng'] ||
                $lng > $jabodetabekBounds['max_lng']
            ) {
                return back()
                    ->withErrors(['latitude' => 'Lokasi pemesanan hanya mencakup area Jabodetabek.'])
                    ->withInput();
            }
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang Anda kosong. Tambahkan menu terlebih dahulu.');
        }

        $menuIds = collect($cart)->pluck('menu_id')->all();
        $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

        foreach ($cart as $item) {
            $menu = $menus->get($item['menu_id']);

            if (! $menu || ! $menu->is_active) {
                return back()->with('error', 'Ada menu di keranjang yang sudah tidak aktif atau tidak tersedia. Silakan perbarui keranjang Anda.');
            }

            if ($item['quantity'] < 10) {
                return back()->with('error', "Minimal pembelian untuk {$menu->name} adalah 10 porsi.");
            }
        }

        $total = array_sum(array_map(fn ($i) => $i['price'] * $i['quantity'], $cart));

        // Set waktu expired (misal 10 menit dari sekarang jika pakai Duitku)
        $expiresAt = null;
        if ($request->payment_method === 'duitku') {
            $expiresAt = now()->addMinutes(10);
        }

        try {
            $order = DB::transaction(function () use ($request, $cart, $total, $expiresAt) {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'event_date' => $request->event_date,
                    'event_address' => $request->event_address,
                    'total_price' => $total,
                    'status' => 'pending',
                    'notes' => $request->notes,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'unpaid',
                    'payment_expires_at' => $expiresAt,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                foreach ($cart as $item) {
                    $menu = Menu::lockForUpdate()->findOrFail($item['menu_id']);

                    if (!$menu->is_active) {
                        throw new \RuntimeException("Menu {$menu->name} saat ini sedang tidak aktif / tidak tersedia.");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                return $order;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
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
            $appUrl = rtrim(config('app.url'), '/');
            $callbackUrl = config('services.duitku.callback_url') ?: $appUrl.'/callback';
            $returnUrl = config('services.duitku.return_url') ?: $appUrl.'/riwayat-pemesanan';

            $timestamp = round(microtime(true) * 1000);
            $signature = hash_hmac('sha256', $merchantCode.$timestamp, $apiKey);

            $merchantOrderId = 'ORD-'.$order->id;
            $paymentAmount = (int) $total;

            $params = [
                'paymentAmount' => $paymentAmount,
                'merchantOrderId' => $merchantOrderId,
                'productDetails' => 'Pesanan Katering #ORD-'.$order->id,
                'email' => 'customer@rishacatering.com',
                'phoneNumber' => $order->customer_phone,
                'customerVaName' => $order->customer_name,
                'callbackUrl' => $callbackUrl,
                'returnUrl' => $returnUrl,
                'expiryPeriod' => 10,
            ];

            $url = $env === 'sandbox'
            ? 'https://api-sandbox.duitku.com/api/merchant/createInvoice'
            : 'https://api-prod.duitku.com/api/merchant/createInvoice';

            try {
                Log::info('DUITKU DEBUG', [
                    'merchant_code' => $merchantCode,
                    'env' => $env,
                    'url' => $url,
                    'callback_url' => $callbackUrl,
                    'return_url' => $returnUrl,
                    'payment_amount' => $paymentAmount,
                    'merchant_order_id' => $merchantOrderId,
                    'api_key_exists' => ! empty($apiKey),
                    'signature' => $signature,
                ]);

                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'x-duitku-signature' => $signature,
                    'x-duitku-timestamp' => $timestamp,
                    'x-duitku-merchantcode' => $merchantCode,
                ])->post($url, $params);

                $result = $response->json();

                if ($response->successful() && isset($result['paymentUrl'])) {
                    $order->update([
                        'payment_url' => $result['paymentUrl'],
                        'reference' => $result['reference'] ?? null,
                    ]);

                    return redirect($result['paymentUrl']);
                }

                Log::error('Duitku HTTP Status: '.$response->status());
                Log::error('Duitku Error Response Body: '.$response->body());
                Log::error('Duitku Error Parsed JSON: '.json_encode($result));

                return redirect()->route('orders')
                    ->with('error', 'Gagal membuat link pembayaran Duitku. Silakan coba lagi atau hubungi admin.');

            } catch (\Exception $e) {
                Log::error('Duitku Exception: '.$e->getMessage());

                return redirect()->route('orders')
                    ->with('error', 'Terjadi kesalahan sistem saat menghubungi payment gateway.');
            }
        }

        return redirect()->route('orders')
            ->with('success', "Pesanan #ORD-{$order->id} berhasil dibuat! Kami akan segera memproses pesanan Anda.")
            ->with('new_order_id', $order->id);
    }
}
