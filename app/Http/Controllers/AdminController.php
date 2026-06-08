<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total_price');
        $pendingOrders = Order::where('status', 'pending')->count();
        $recentOrders = Order::with('items.menu')->latest()->take(5)->get();
        $topMenu = OrderItem::selectRaw('menu_id, SUM(quantity) as total_qty')
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->with('menu')
            ->first();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'pendingOrders',
            'recentOrders', 'topMenu'
        ));
    }

    // Manajemen Menu
    public function stok()
    {
        $menus = Menu::orderBy('category')->orderBy('name')->get();

        return view('admin.stok', compact('menus'));
    }

    // Tambah / Edit / Hapus Menu
    public function createMenu()
    {
        $menu = new Menu;

        return view('admin.menu_form', compact('menu'));
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->only(['name', 'category', 'price', 'stock', 'description']);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/menus'), $imageName);
            $data['image'] = 'images/menus/'.$imageName;
        }

        Menu::create($data);

        return redirect()->route('admin.stok')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    public function editMenu(Menu $menu)
    {
        return view('admin.menu_form', compact('menu'));
    }

    public function updateMenu(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->only(['name', 'category', 'price', 'stock', 'description']);

        if ($request->hasFile('image')) {
            // Delete old image if needed (optional)
            if ($menu->image && file_exists(public_path($menu->image)) && ! str_starts_with($menu->image, 'http')) {
                unlink(public_path($menu->image));
            }

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/menus'), $imageName);
            $data['image'] = 'images/menus/'.$imageName;
        }

        $menu->update($data);

        return redirect()->route('admin.stok')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroyMenu(Menu $menu)
    {
        // Delete image if exists
        if ($menu->image && file_exists(public_path($menu->image)) && ! str_starts_with($menu->image, 'http')) {
            unlink(public_path($menu->image));
        }

        $menu->delete();

        return redirect()->route('admin.stok')->with('success', 'Menu berhasil dihapus!');
    }

    // Pesanan Masuk
    public function pesanan()
    {
        $orders = Order::with('items.menu')->latest()->paginate(15);

        return view('admin.pesanan', compact('orders'));
    }

    public function updateStatusPesanan(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai,dibatalkan',
        ]);

        $order->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status' => $order->status,
                'message' => 'Status pesanan diperbarui.',
            ]);
        }

        return back()->with('success', "Status pesanan #{$order->id} diperbarui ke {$order->status}.");
    }

    // API: total pesanan pending (untuk polling)
    public function apiPendingCount()
    {
        return response()->json([
            'pending' => Order::where('status', 'pending')->count(),
        ]);
    }

    // Konfirmasi Pembayaran
    public function konfirmasiPembayaran(Request $request, Order $order)
    {
        $request->validate([
            'estimation_time' => 'nullable|string|max:255',
        ]);

        $order->update([
            'payment_status' => 'paid',
            'status' => 'diproses',
            'estimation_time' => $request->estimation_time,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dikonfirmasi.',
        ]);
    }
}
