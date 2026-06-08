<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function download($id)
    {
        $order = Order::with('items.menu')->findOrFail($id);
        $trackedOrders = session('tracked_orders', []);
        $user = Auth::user();

        $canDownload = in_array($order->id, $trackedOrders)
        || ($user && ($order->user_id === $user->id || $user->role === 'admin'));

        abort_unless($canDownload, 403);

        $pdf = Pdf::loadView('invoice.pdf', compact('order'));

        return $pdf->download('invoice-'.$order->id.'.pdf');
    }
}
