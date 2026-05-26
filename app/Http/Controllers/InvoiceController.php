<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download($id)
    {
        $order = Order::with('items.menu')->findOrFail($id);

        $pdf = Pdf::loadView('invoice.pdf', compact('order'));

        return $pdf->download('invoice-'.$order->id.'.pdf');
    }
}