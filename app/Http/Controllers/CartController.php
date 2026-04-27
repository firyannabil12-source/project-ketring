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
            'notes'          => 'nullable|string|max:1000',
        ], [
            'customer_name.required'  => 'Nama pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor HP wajib diisi.',
            'event_date.required'     => 'Tanggal acara wajib diisi.',
            'event_date.after_or_equal' => 'Tanggal acara tidak boleh di masa lalu.',
            'event_address.required'  => 'Alamat acara wajib diisi.',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang Anda kosong. Tambahkan menu terlebih dahulu.');
        }

        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));

        // Simpan order
        $order = Order::create([
            'user_id'        => \Illuminate\Support\Facades\Auth::id(),
            'customer_name'  => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'event_date'     => $request->event_date,
            'event_address'  => $request->event_address,
            'total_price'    => $total,
            'status'         => 'pending',
            'notes'          => $request->notes,
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

        return redirect()->route('orders')
            ->with('success', "Pesanan #ORD-{$order->id} berhasil dibuat! Kami akan segera memproses pesanan Anda.")
            ->with('new_order_id', $order->id);
    }
}
