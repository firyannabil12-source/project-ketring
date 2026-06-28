<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function index()
    {
        $menus = Menu::when(Schema::hasColumn('menus', 'is_active'), function ($query) {
            $query->where('is_active', true);
        })->take(3)->get();
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
        $cart = session('cart', []);

        return view('orders', compact('cart'));
    }

    public function orderHistory()
    {
        $orders = collect([]);
        $trackedOrders = session('tracked_orders', []);

        if (Auth::check()) {
            // Tampilkan pesanan milik user yang login DAN pesanan yang dibuat saat guest (tracked via session)
            $orders = Order::with('items.menu')
                ->where(function ($q) use ($trackedOrders) {
                    $q->where('user_id', Auth::id());
                    if (! empty($trackedOrders)) {
                        $q->orWhereIn('id', $trackedOrders);
                    }
                })
                ->latest()
                ->take(5)
                ->get();

            // Otomatis klaim pesanan guest ke akun yang sedang login
            if (! empty($trackedOrders)) {
                Order::whereIn('id', $trackedOrders)
                    ->whereNull('user_id')
                    ->update(['user_id' => Auth::id()]);
            }
        } else {
            if (! empty($trackedOrders)) {
                $orders = Order::with('items.menu')
                    ->whereIn('id', $trackedOrders)
                    ->latest()
                    ->take(5)
                    ->get();
            }
        }

        return view('order_history', compact('orders'));
    }

    // API: status pesanan untuk polling real-time
    public function apiOrderStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json([]);
        }

        $trackedOrders = session('tracked_orders', []);
        $orders = Order::whereIn('id', $ids)
            ->where(function ($q) use ($trackedOrders) {
                if (Auth::check()) {
                    $q->where('user_id', Auth::id());

                    if (! empty($trackedOrders)) {
                        $q->orWhereIn('id', $trackedOrders);
                    }
                } elseif (! empty($trackedOrders)) {
                    $q->whereIn('id', $trackedOrders);
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->get(['id', 'status', 'updated_at']);

        return response()->json($orders);
    }
}
