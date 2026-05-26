<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #ORD-{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 13px;
        }

        .invoice-box {
            padding: 25px;
            border: 2px solid #0f766e;
            border-radius: 10px;
        }

        .header {
            background: #0f766e;
            color: white;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
        }

        .header p {
            margin: 4px 0 0;
        }

        .info {
            width: 100%;
            margin-bottom: 20px;
        }

        .info td {
            padding: 6px 0;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
        }

        .badge-paid {
            background: #dcfce7;
            color: #166534;
        }

        .badge-unpaid {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-expired {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-default {
            background: #f3f4f6;
            color: #374151;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.items th {
            background: #f3f4f6;
            padding: 10px;
            border: 1px solid #d1d5db;
        }

        table.items td {
            padding: 10px;
            border: 1px solid #d1d5db;
        }

        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #0f766e;
        }

        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="header">
        <h1>KETRING MAMA IKSAN</h1>
        <p>Invoice Pemesanan Catering</p>
    </div>

    <table class="info">
        <tr>
            <td><strong>No Invoice</strong></td>
            <td>: #ORD-{{ $order->id }}</td>
            <td><strong>Status Pembayaran</strong></td>
            <td>: 
                @if($order->payment_status === 'paid')
                    <span class="badge badge-paid">LUNAS</span>
                @elseif($order->payment_status === 'unpaid')
                    <span class="badge badge-unpaid">BELUM BAYAR</span>
                @elseif($order->payment_status === 'expired')
                    <span class="badge badge-expired">KEDALUWARSA</span>
                @else
                    <span class="badge badge-default">{{ strtoupper($order->payment_status) }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Customer</strong></td>
            <td>: {{ $order->customer_name }}</td>
            <td><strong>Tanggal Pesan</strong></td>
            <td>: {{ $order->created_at->format('d M Y, H:i') }}</td>
        </tr>
        <tr>
            <td><strong>No HP</strong></td>
            <td>: {{ $order->customer_phone }}</td>
            <td><strong>Tanggal Acara</strong></td>
            <td>: {{ $order->event_date ? $order->event_date->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Alamat Acara</strong></td>
            <td colspan="3">: {{ $order->event_address }}</td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>No</th>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->menu->name ?? '-' }}</td>
                <td align="center">{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
    </div>

    <div class="footer">
        Terima kasih telah melakukan pemesanan di Ketring Mama Iksan.
        <br>
        Simpan invoice ini sebagai bukti pemesanan.
    </div>
</div>

</body>
</html>