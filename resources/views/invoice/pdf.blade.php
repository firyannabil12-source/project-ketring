<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice #ORD-{{ $order->id }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 26px;
            background: #fff7ed;
            color: #1f2937;
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.45;
        }

        .invoice-box {
            background: #ffffff;
            border: 2px solid #E8572A;
            border-radius: 14px;
            padding: 28px;
        }

        .header {
            background: #0f172a;
            color: #ffffff;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 28px;
        }

        .brand {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: .5px;
            margin: 0;
        }

        .brand span {
            color: #fb923c;
        }

        .subtitle {
            margin: 6px 0 0;
            color: #fed7aa;
            font-size: 14px;
        }

        .info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .info td {
            padding: 5px 0;
            vertical-align: top;
        }

        .info .label {
            width: 120px;
            color: #334155;
            font-weight: 800;
        }

        .info .sep {
            width: 10px;
            color: #94a3b8;
        }

        .info .value {
            color: #1f2937;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .4px;
        }

        .badge-paid {
            background: #dcfce7;
            color: #166534;
        }

        .badge-unpaid {
            background: #ffedd5;
            color: #c2410c;
        }

        .badge-expired {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-default {
            background: #f1f5f9;
            color: #334155;
        }

        .section-title {
            margin: 0 0 10px;
            color: #0f172a;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
        }

        table.items th {
            background: #fff7ed;
            color: #0f172a;
            padding: 11px 10px;
            border: 1px solid #fed7aa;
            font-weight: 800;
            text-align: left;
        }

        table.items td {
            padding: 11px 10px;
            border: 1px solid #fed7aa;
            color: #334155;
        }

        table.items .center {
            text-align: center;
        }

        table.items .money {
            white-space: nowrap;
        }

        .total {
            margin-top: 22px;
            text-align: right;
            color: #E8572A;
            font-size: 24px;
            font-weight: 800;
        }

        .note {
            margin-top: 30px;
            padding: 14px 16px;
            background: #fff7ed;
            border-left: 4px solid #E8572A;
            border-radius: 10px;
            color: #64748b;
            font-size: 12px;
        }

        .footer {
            margin-top: 28px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <h1 class="brand"><span>Risha</span> Catering</h1>
            <p class="subtitle">Invoice Pemesanan Katering</p>
        </div>

        <table class="info">
            <tr>
                <td class="label">No Invoice</td>
                <td class="sep">:</td>
                <td class="value">#ORD-{{ $order->id }}</td>
                <td class="label">Status Bayar</td>
                <td class="sep">:</td>
                <td class="value">
                    @if ($order->payment_status === 'paid')
                        <span class="badge badge-paid">LUNAS</span>
                    @elseif($order->payment_status === 'unpaid')
                        <span class="badge badge-unpaid">BELUM BAYAR</span>
                    @elseif($order->payment_status === 'expired')
                        <span class="badge badge-expired">KEDALUWARSA</span>
                    @else
                        <span class="badge badge-default">{{ strtoupper($order->payment_status ?? '-') }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Customer</td>
                <td class="sep">:</td>
                <td class="value">{{ $order->customer_name }}</td>
                <td class="label">Tanggal Pesan</td>
                <td class="sep">:</td>
                <td class="value">{{ $order->created_at->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td class="label">No HP</td>
                <td class="sep">:</td>
                <td class="value">{{ $order->customer_phone }}</td>
                <td class="label">Tanggal Acara</td>
                <td class="sep">:</td>
                <td class="value">{{ $order->event_date ? $order->event_date->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Acara</td>
                <td class="sep">:</td>
                <td class="value" colspan="4">{{ $order->event_address ?? '-' }}</td>
            </tr>
        </table>

        <h2 class="section-title">Detail Pesanan</h2>
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 48px;">No</th>
                    <th>Menu</th>
                    <th style="width: 70px;" class="center">Qty</th>
                    <th style="width: 130px;">Harga</th>
                    <th style="width: 140px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->menu->name ?? 'Menu dihapus' }}</td>
                        <td class="center">{{ $item->quantity }}</td>
                        <td class="money">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="money">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </div>

        @if ($order->notes)
            <div class="note">
                <strong>Catatan:</strong> {{ $order->notes }}
            </div>
        @endif

        <div class="footer">
            Terima kasih telah melakukan pemesanan di Risha Catering.<br>
            Simpan invoice ini sebagai bukti pemesanan.
        </div>
    </div>
</body>

</html>
