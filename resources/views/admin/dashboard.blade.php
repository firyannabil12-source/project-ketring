@extends('layouts.admin')

@section('page-title', 'Dashboard')
@section('breadcrumb', 'Overview')

@section('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }

        .stat-icon {
            position: absolute;
            right: 1rem;
            top: 1rem;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff7ed;
            color: #E8572A;
        }

        .stat-icon svg {
            width: 21px;
            height: 21px;
            stroke-width: 2.1;
        }

        .stat-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-sub {
            font-size: 0.78rem;
            font-weight: 600;
        }

        .stat-sub.up {
            color: #16a34a;
        }

        .stat-sub.warn {
            color: #dc2626;
        }

        .stat-sub.neutral {
            color: #f59e0b;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
        }

        .card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .card-body {
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 0.875rem 1.25rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            background: #f8fafc;
        }

        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            color: #334155;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .status-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-pending {
            background: #fef9c3;
            color: #a16207;
        }

        .status-diproses {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-selesai {
            background: #dcfce7;
            color: #15803d;
        }

        .status-dibatalkan {
            background: #fee2e2;
            color: #dc2626;
        }

        .quick-action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            padding: 1.25rem;
        }

        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 0.75rem;
            border-radius: 10px;
            background: #f8fafc;
            text-decoration: none;
            color: #334155;
            font-size: 0.8rem;
            font-weight: 600;
            gap: 0.5rem;
            transition: all 0.2s;
            border: 1.5px solid #e2e8f0;
            text-align: center;
        }

        .quick-action:hover {
            background: #E8572A;
            color: white;
            border-color: #E8572A;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(232, 87, 42, 0.3);
        }

        .quick-action .qa-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: #E8572A;
            transition: all 0.2s;
        }

        .quick-action .qa-icon svg {
            width: 20px;
            height: 20px;
            stroke-width: 2.15;
        }

        .quick-action:hover .qa-icon {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            transform: scale(1.06);
        }

        .low-stock-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.875rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .low-stock-item:last-child {
            border-bottom: none;
        }

        .stock-badge {
            font-size: 0.8rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .stock-critical {
            background: #fee2e2;
            color: #dc2626;
        }

        .stock-low {
            background: #fef9c3;
            color: #a16207;
        }

        .hero-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 14px;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            overflow: hidden;
            position: relative;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            right: 2rem;
            font-size: 5rem;
            opacity: 0.12;
        }

        .hero-banner h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            color: white;
            font-weight: 700;
            margin-bottom: 0.375rem;
        }

        .hero-banner p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .hero-banner .btn-primary-sm {
            background: #E8572A;
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero-banner .btn-primary-sm:hover {
            background: #d14a20;
        }

        .hero-banner .btn-primary-sm svg,
        .card-header svg {
            width: 17px;
            height: 17px;
        }

        .empty-state {
            padding: 2rem;
            text-align: center;
            color: #94a3b8;
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('content')

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div>
            <h2>Selamat datang, {{ Auth::guard('admin')->user()->name }}!</h2>
            <p>Pantau semua aktivitas katering Anda dari sini.</p>
            <a href="{{ route('admin.pesanan') }}" class="btn-primary-sm"><i data-lucide="receipt-text"></i>Lihat Pesanan
                Masuk</a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon"><i data-lucide="shopping-bag"></i></span>
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-sub neutral">Semua waktu</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon"><i data-lucide="wallet"></i></span>
            <div class="stat-label">Pendapatan Bersih</div>
            <div class="stat-value" style="font-size: 1.4rem;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-sub up">Dari pesanan selesai</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon"><i data-lucide="clock-3"></i></span>
            <div class="stat-label">Pesanan Pending</div>
            <div class="stat-value {{ $pendingOrders > 0 ? 'text-warning' : '' }}"
                style="{{ $pendingOrders > 0 ? 'color:#f59e0b' : '' }}">{{ $pendingOrders }}</div>
            <div class="stat-sub {{ $pendingOrders > 0 ? 'neutral' : 'up' }}">
                {{ $pendingOrders > 0 ? 'Menunggu diproses' : 'Semua terproses' }}
            </div>
        </div>
        <div class="stat-card">
            <span class="stat-icon"><i data-lucide="flame"></i></span>
            <div class="stat-label">Menu Terlaris</div>
            <div class="stat-value" style="font-size: 1.4rem;">{{ $topMenu ? $topMenu->menu->name : '-' }}</div>
            <div class="stat-sub up">{{ $topMenu ? $topMenu->total_qty . ' Porsi Terjual' : 'Belum ada data' }}</div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="dashboard-grid">

        <!-- Pesanan Terbaru -->
        <div class="card">
            <div class="card-header">
                <h3 style="display:flex; align-items:center; gap:0.5rem;"><i data-lucide="history"></i>Pesanan Terbaru</h3>
                <a href="{{ route('admin.pesanan') }}"
                    style="font-size: 0.8rem; color: #E8572A; text-decoration: none; font-weight: 600;">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if ($recentOrders->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td style="font-weight: 700; color: #E8572A;">#ORD-{{ $order->id }}</td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $order->customer_name }}</div>
                                        <div style="font-size: 0.78rem; color: #94a3b8;">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td style="font-weight: 700;">Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td><span
                                            class="status-pill status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <p>Belum ada pesanan masuk. Bagikan website Anda!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right column -->
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">

            <!-- Aksi Cepat -->
            <div class="card">
                <div class="card-header">
                    <h3 style="display:flex; align-items:center; gap:0.5rem;"><i data-lucide="zap"></i>Aksi Cepat</h3>
                </div>
                <div class="quick-action-grid">
                    <a href="{{ route('admin.stok') }}" class="quick-action">
                        <span class="qa-icon"><i data-lucide="utensils"></i></span> Kelola Menu
                    </a>
                    <a href="{{ route('admin.menu.create') }}" class="quick-action">
                        <span class="qa-icon"><i data-lucide="plus"></i></span> Tambah Menu
                    </a>
                    <a href="{{ route('admin.pesanan') }}" class="quick-action">
                        <span class="qa-icon"><i data-lucide="receipt-text"></i></span> Pesanan
                    </a>
                    <a href="{{ route('home') }}" class="quick-action" target="_blank">
                        <span class="qa-icon"><i data-lucide="external-link"></i></span> Website
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
