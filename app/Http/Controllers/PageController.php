<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;

class PageController extends Controller
{
    public function index()
    {
        $menus = Menu::take(3)->get();
        $featuredMenus = $menus;
        return view('home', compact('featuredMenus'));
    }

    public function menu()
    {
        $menus = Menu::orderBy('category')->orderBy('name')->get();
        return view('menu', compact('menus'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function orders()
    {
        $orders = collect([]);
        $trackedOrders = session('tracked_orders', []);

        if (\Illuminate\Support\Facades\Auth::check()) {
            // Tampilkan pesanan milik user yang login DAN pesanan yang dibuat saat guest (tracked via session)
            $orders = Order::with('items.menu')
                ->where(function ($q) use ($trackedOrders) {
                    $q->where('user_id', \Illuminate\Support\Facades\Auth::id());
                    if (!empty($trackedOrders)) {
                        $q->orWhereIn('id', $trackedOrders);
                    }
                })
                ->latest()
                ->take(5)
                ->get();

            // Otomatis klaim pesanan guest ke akun yang sedang login
            if (!empty($trackedOrders)) {
                Order::whereIn('id', $trackedOrders)
                    ->whereNull('user_id')
                    ->update(['user_id' => \Illuminate\Support\Facades\Auth::id()]);
            }
        } else {
            if (!empty($trackedOrders)) {
                $orders = Order::with('items.menu')
                    ->whereIn('id', $trackedOrders)
                    ->latest()
                    ->take(5)
                    ->get();
            }
        }

        $cart = session('cart', []);
        return view('orders', compact('orders', 'cart'));
    }

    // API: status pesanan untuk polling real-time
    public function apiOrderStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json([]);
        }
        $orders = Order::whereIn('id', $ids)->get(['id', 'status', 'updated_at']);
        return response()->json($orders);
    }
}
