@extends('layouts.app')

@section('title', 'Pesanan Saya — Ketring Mama Iksan')

@section('styles')
<style>
    .orders-page { padding: 3rem 0 5rem; }
    .page-header { text-align: center; margin-bottom: 3rem; }
    .page-header h1 { font-family: 'Outfit', sans-serif; font-size: 2.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
    .page-header p { color: #64748b; }

    .orders-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start; }

    /* ── Checkout Form ── */
    .checkout-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        position: sticky;
        top: 6rem;
    }
    .checkout-card-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
    }
    .checkout-card-header h3 { font-family: 'Outfit', sans-serif; font-size: 1.05rem; font-weight: 700; margin: 0; }
    .checkout-card-header p { font-size: 0.78rem; opacity: 0.6; margin-top: 2px; }
    .checkout-card-body { padding: 1.5rem; }

    /* Cart summary in checkout */
    .cart-summary { margin-bottom: 1.5rem; }
    .cart-summary-empty { text-align: center; padding: 1.5rem; color: #94a3b8; font-size: 0.875rem; }
    .cart-summary-empty a { color: #E8572A; text-decoration: none; font-weight: 600; }
    .cart-sum-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }
    .cart-sum-item:last-child { border-bottom: none; }
    .cart-sum-item .item-name { color: #334155; font-weight: 600; }
    .cart-sum-item .item-qty { color: #94a3b8; font-size: 0.78rem; }
    .cart-sum-item .item-price { font-weight: 700; color: #0f172a; }
    .cart-sum-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0 0;
        border-top: 2px solid #f1f5f9;
        margin-top: 0.5rem;
    }
    .cart-sum-total .label { font-weight: 700; color: #0f172a; }
    .cart-sum-total .value { font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 800; color: #E8572A; }

    /* Form */
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: 0.78rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.375rem; }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 0.7rem 0.875rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        color: #0f172a;
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
        box-sizing: border-box;
    }
    .form-group input:focus, .form-group textarea:focus { border-color: #E8572A; box-shadow: 0 0 0 3px rgba(232,87,42,0.1); background: white; }
    .form-group textarea { resize: vertical; min-height: 72px; }
    .field-error { color: #dc2626; font-size: 0.78rem; margin-top: 3px; }

    .btn-pesan {
        width: 100%;
        padding: 0.9rem;
        background: linear-gradient(135deg, #E8572A, #f97316);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
        margin-top: 0.75rem;
    }
    .btn-pesan:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(232,87,42,0.4); }
    .btn-pesan:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    /* ── Orders List ── */
    .orders-list-section {}
    .section-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    .section-title-row h2 { font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0; }

    .realtime-badge {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.75rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
    }
    .live-dot {
        width: 8px; height: 8px;
        background: #22c55e;
        border-radius: 50%;
        animation: livePulse 2s infinite;
    }
    @keyframes livePulse { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:0.5; transform:scale(0.8); } }

    .order-card {
        background: white;
        border-radius: 14px;
        border: 1.5px solid #f1f5f9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 1.25rem;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .order-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.1); }

    .order-card-header {
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f8fafc;
        background: #f8fafc;
    }
    .order-id-badge {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        color: #E8572A;
    }
    .order-date { font-size: 0.78rem; color: #94a3b8; }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .status-indicator::before { content: ''; width: 8px; height: 8px; border-radius: 50%; }
    .status-pending { background: #fef9c3; color: #a16207; }
    .status-pending::before { background: #f59e0b; animation: livePulse 1.5s infinite; }
    .status-diproses { background: #dbeafe; color: #1d4ed8; }
    .status-diproses::before { background: #3b82f6; animation: livePulse 2s infinite; }
    .status-selesai { background: #dcfce7; color: #15803d; }
    .status-selesai::before { background: #22c55e; }
    .status-dibatalkan { background: #fee2e2; color: #dc2626; }
    .status-dibatalkan::before { background: #ef4444; }

    .order-card-body { padding: 1.25rem; }
    .order-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem; }
    .order-info-item { font-size: 0.82rem; }
    .order-info-item .lbl { color: #94a3b8; font-weight: 600; font-size: 0.72rem; text-transform: uppercase; }
    .order-info-item .val { color: #334155; font-weight: 600; margin-top: 2px; }

    .order-items-list { border-top: 1px solid #f1f5f9; padding-top: 0.875rem; }
    .order-items-title { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 0.5rem; }
    .order-item-row { display: flex; justify-content: space-between; font-size: 0.82rem; padding: 0.25rem 0; color: #475569; }
    .order-item-row .item-name { font-weight: 600; }
    .order-item-row .item-total { font-weight: 700; color: #0f172a; }

    .order-card-footer {
        padding: 0.875rem 1.25rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .order-grand-total { font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 800; color: #0f172a; }
    .order-grand-total small { font-size: 0.72rem; color: #94a3b8; font-family: 'Inter', sans-serif; font-weight: 500; }

    .progress-bar-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    .progress-step {
        flex: 1;
        text-align: center;
        font-size: 0.65rem;
        font-weight: 700;
        color: #cbd5e1;
    }
    .progress-step.done { color: #16a34a; }
    .progress-step.active { color: #E8572A; }
    .progress-line { flex: 1; height: 3px; background: #e2e8f0; border-radius: 3px; }
    .progress-line.done { background: #22c55e; }

    .no-orders-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        border: 2px dashed #e2e8f0;
        color: #94a3b8;
    }
    .no-orders-state .icon { font-size: 3.5rem; margin-bottom: 1rem; }
    .no-orders-state p { font-size: 0.9rem; margin-bottom: 1.25rem; }
    .no-orders-state a {
        display: inline-block;
        background: #E8572A;
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .no-orders-state a:hover { background: #d14a20; }

    @media (max-width: 900px) {
        .orders-layout { grid-template-columns: 1fr; }
        .checkout-card { position: static; }
    }
</style>
@endsection

@section('content')
<section class="orders-page">
    <div class="container">
        <div class="page-header">
            <h1>🛒 Pemesanan</h1>
            <p>Isi form di bawah untuk menyelesaikan pesanan Anda, lalu pantau status pesanan secara real-time.</p>
        </div>

        <div class="orders-layout">

            <!-- ── Left: Daftar Pesanan ── -->
            <div class="orders-list-section">
                <div class="section-title-row">
                    <h2>📋 Riwayat Pesanan Anda</h2>
                    <div class="realtime-badge">
                        <span class="live-dot"></span>
                        Live Update
                    </div>
                </div>

                <div id="ordersList">
                    @if($orders->isNotEmpty())
                        @foreach($orders as $order)
                        <div class="order-card" id="order-{{ $order->id }}" data-order-id="{{ $order->id }}">
                            <div class="order-card-header">
                                <span class="order-id-badge">#ORD-{{ $order->id }}</span>
                                <span class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                <span class="status-indicator status-{{ $order->status }}" id="status-badge-{{ $order->id }}">
                                    @php
                                        $statusLabel = ['pending' => 'Menunggu Konfirmasi', 'diproses' => 'Sedang Diproses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'];
                                    @endphp
                                    {{ $statusLabel[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>

                            <div class="order-card-body">
                                <div class="order-info-grid">
                                    <div class="order-info-item">
                                        <div class="lbl">Nama</div>
                                        <div class="val">{{ $order->customer_name }}</div>
                                    </div>
                                    <div class="order-info-item">
                                        <div class="lbl">No. HP</div>
                                        <div class="val">{{ $order->customer_phone }}</div>
                                    </div>
                                    <div class="order-info-item">
                                        <div class="lbl">Tanggal Acara</div>
                                        <div class="val">{{ $order->event_date ? $order->event_date->format('d M Y') : '-' }}</div>
                                    </div>
                                    <div class="order-info-item">
                                        <div class="lbl">Alamat Acara</div>
                                        <div class="val">{{ $order->event_address ?? '-' }}</div>
                                    </div>
                                    <div class="order-info-item">
                                        <div class="lbl">Pembayaran</div>
                                        <div class="val">
                                            <span style="text-transform: capitalize;">{{ $order->payment_method ?? 'Cash/Transfer' }}</span>
                                            @if($order->payment_status === 'unpaid')
                                                <span style="color: #dc2626; font-size: 0.75rem; margin-left: 5px; font-weight: 700;">(Belum Dibayar)</span>
                                            @elseif($order->payment_status === 'paid')
                                                <span style="color: #16a34a; font-size: 0.75rem; margin-left: 5px; font-weight: 700;">(Lunas)</span>
                                            @elseif($order->payment_status === 'expired')
                                                <span style="color: #64748b; font-size: 0.75rem; margin-left: 5px; font-weight: 700;">(Kedaluwarsa)</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($order->estimation_time && in_array($order->status, ['diproses', 'selesai']))
                                    <div class="order-info-item">
                                        <div class="lbl">Estimasi Pembuatan</div>
                                        <div class="val" style="color: #E8572A; font-weight: 800;">⏱️ {{ $order->estimation_time }}</div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Payment Countdown -->
                                @if($order->payment_status === 'unpaid' && $order->payment_expires_at && $order->payment_expires_at->isFuture())
                                <div style="background: #fef2f2; border: 1px dashed #fca5a5; padding: 0.75rem; border-radius: 10px; margin-bottom: 1rem; text-align: center;">
                                    <div style="font-size: 0.78rem; color: #dc2626; font-weight: 600; margin-bottom: 0.25rem;">Selesaikan Pembayaran Dalam:</div>
                                    <div class="payment-countdown" data-expires-at="{{ $order->payment_expires_at->toIso8601String() }}" data-order-id="{{ $order->id }}" style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 800; color: #991b1b; letter-spacing: 2px;">
                                        --:--
                                    </div>
                                </div>
                                @endif

                                <!-- Progress -->
                                @if($order->status !== 'dibatalkan')
                                <div class="progress-bar-wrapper">
                                    <div class="progress-step {{ in_array($order->status, ['pending','diproses','selesai']) ? 'done' : '' }}">
                                        ✅ Masuk
                                    </div>
                                    <div class="progress-line {{ in_array($order->status, ['diproses','selesai']) ? 'done' : '' }}"></div>
                                    <div class="progress-step {{ $order->status === 'diproses' ? 'active' : ($order->status === 'selesai' ? 'done' : '') }}">
                                        🍳 Diproses
                                    </div>
                                    <div class="progress-line {{ $order->status === 'selesai' ? 'done' : '' }}"></div>
                                    <div class="progress-step {{ $order->status === 'selesai' ? 'done' : '' }}">
                                        🎉 Selesai
                                    </div>
                                </div>
                                @endif

                                <!-- Items -->
                                <div class="order-items-list">
                                    <div class="order-items-title">Detail Pesanan</div>
                                    @foreach($order->items as $item)
                                    <div class="order-item-row">
                                        <span class="item-name">{{ $item->menu->name ?? 'Menu dihapus' }} × {{ $item->quantity }}</span>
                                        <span class="item-total">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="order-card-footer">
                                <span class="order-grand-total">
                                    <small>Total Bayar</small><br>
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </span>
                                @if($order->status === 'pending')
                                <span style="font-size: 0.78rem; color: #f59e0b; font-weight: 600;">⏳ Menunggu konfirmasi admin...</span>
                                @elseif($order->status === 'diproses')
                                <span style="font-size: 0.78rem; color: #3b82f6; font-weight: 600;">🍳 Sedang dimasak untuk Anda!</span>
                                @elseif($order->status === 'selesai')
                                <span style="font-size: 0.78rem; color: #16a34a; font-weight: 600;">🎉 Pesanan selesai. Selamat menikmati!</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="no-orders-state">
                        <div class="icon">📋</div>
                        <p>Anda belum memiliki pesanan.<br>Tambahkan menu ke keranjang dan checkout untuk mulai memesan.</p>
                        <a href="{{ route('menu') }}">🍛 Lihat Menu Kami</a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ── Right: Checkout Form ── -->
            <div>
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h3>📝 Form Pemesanan</h3>
                        <p>Isi detail acara Anda</p>
                    </div>
                    <div class="checkout-card-body">

                        <!-- Cart Summary -->
                        <div class="cart-summary" id="checkoutCartSummary">
                            <div class="cart-summary-empty" id="cartSummaryEmpty">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🛒</div>
                                <p>Keranjang kosong.<br><a href="{{ route('menu') }}">Tambah menu terlebih dahulu →</a></p>
                            </div>
                            <div id="cartSummaryItems" style="display:none"></div>
                            <div class="cart-sum-total" id="cartSummaryTotal" style="display:none">
                                <span class="label">Total Pesanan</span>
                                <span class="value" id="checkoutTotal">Rp 0</span>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <form method="POST" action="{{ route('cart.checkout') }}" id="checkoutForm">
                            @csrf

                            @if($errors->any())
                            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1rem; font-size: 0.82rem; color: #dc2626;">
                                @foreach($errors->all() as $error)
                                <div>• {{ $error }}</div>
                                @endforeach
                            </div>
                            @endif

                            <div class="form-group">
                                <label>Nama Pemesan *</label>
                                <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Nama lengkap Anda" required>
                                @error('customer_name')<p class="field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="form-group">
                                <label>Nomor HP / WhatsApp *</label>
                                <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="cth: 08123456789" required>
                                @error('customer_phone')<p class="field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="form-group">
                                <label>Tanggal Acara *</label>
                                <input type="date" name="event_date" value="{{ old('event_date') }}" min="{{ date('Y-m-d') }}" required>
                                @error('event_date')<p class="field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="form-group">
                                <label>Alamat Lokasi Acara *</label>
                                <textarea name="event_address" placeholder="Alamat lengkap tempat acara" required>{{ old('event_address') }}</textarea>
                                @error('event_address')<p class="field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="form-group">
                                <label>Metode Pembayaran *</label>
                                <select name="payment_method" required style="width: 100%; padding: 0.7rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; font-family: 'Inter', sans-serif; color: #0f172a; outline: none; background: #f8fafc;">
                                    <option value="" disabled selected>Pilih metode pembayaran</option>
                                    <option value="duitku">Duitku (Virtual Account / e-Wallet)</option>
                                    <option value="cash">Bayar di Tempat (Cash)</option>
                                </select>
                                @error('payment_method')<p class="field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="form-group">
                                <label>Catatan Tambahan</label>
                                <textarea name="notes" placeholder="Permintaan khusus, alergi makanan, dll.">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="btn-pesan" id="btnPesan" disabled>
                                🛒 Buat Pesanan
                            </button>
                            <p style="font-size: 0.72rem; color: #94a3b8; text-align: center; margin-top: 0.5rem;">
                                Tambahkan menu ke keranjang terlebih dahulu.
                            </p>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// ─── Cart Summary di halaman pesanan ────────────────────────────
function renderCheckoutCart(data) {
    const empty = document.getElementById('cartSummaryEmpty');
    const items = document.getElementById('cartSummaryItems');
    const total = document.getElementById('cartSummaryTotal');
    const btnPesan = document.getElementById('btnPesan');

    if (!data.cart || data.cart.length === 0) {
        empty.style.display = 'block';
        items.style.display = 'none';
        total.style.display = 'none';
        btnPesan.disabled = true;
        btnPesan.textContent = '🛒 Buat Pesanan';
        document.querySelector('#checkoutForm p').style.display = 'block';
        return;
    }

    empty.style.display = 'none';
    items.style.display = 'block';
    total.style.display = 'flex';
    btnPesan.disabled = false;
    btnPesan.textContent = `🛒 Pesan Sekarang (${data.count} item)`;
    document.querySelector('#checkoutForm p').style.display = 'none';

    items.innerHTML = data.cart.map(item => `
        <div class="cart-sum-item">
            <div>
                <div class="item-name">${item.name}</div>
                <div class="item-qty">× ${item.quantity} porsi</div>
            </div>
            <div class="item-price">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</div>
        </div>
    `).join('');

    document.getElementById('checkoutTotal').textContent = 'Rp ' + Number(data.total).toLocaleString('id-ID');
}

// Load cart on page load
fetch('{{ route("cart.get") }}')
    .then(r => r.json())
    .then(data => { cartData = data; renderCheckoutCart(data); });

// ─── Real-time status polling ─────────────────────────────────
const orderIds = @json($orders->pluck('id'));

function pollOrderStatus() {
    if (orderIds.length === 0) return;

    fetch('{{ route("api.order.status") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ ids: orderIds })
    })
    .then(r => r.json())
    .then(orders => {
        orders.forEach(order => {
            const badge = document.getElementById(`status-badge-${order.id}`);
            if (!badge) return;

            const statusMap = {
                'pending':     { label: 'Menunggu Konfirmasi', cls: 'status-pending' },
                'diproses':    { label: 'Sedang Diproses',     cls: 'status-diproses' },
                'selesai':     { label: 'Selesai',              cls: 'status-selesai' },
                'dibatalkan':  { label: 'Dibatalkan',           cls: 'status-dibatalkan' },
            };
            const current = statusMap[order.status];
            if (!current) return;

            // Only update if status changed
            if (!badge.classList.contains(current.cls)) {
                badge.className = `status-indicator ${current.cls}`;
                badge.textContent = current.label;
                showUserToast(`Pesanan #ORD-${order.id}: Status berubah ke "${current.label}"`, 'success');
            }
        });
    })
    .catch(() => {});
}

// Poll every 15 seconds
if (orderIds.length > 0) {
    setInterval(pollOrderStatus, 15000);
}

// ─── Payment Countdown Timer ──────────────────────────────────
function updateCountdowns() {
    document.querySelectorAll('.payment-countdown').forEach(el => {
        const expiresAt = new Date(el.dataset.expiresAt).getTime();
        const now = new Date().getTime();
        const distance = expiresAt - now;

        if (distance < 0) {
            el.innerHTML = "WAKTU HABIS";
            el.style.color = "#64748b";
            // Optional: You could trigger an API call to mark as expired if you don't do it via cron.
        } else {
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            el.innerHTML = String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');
        }
    });
}
setInterval(updateCountdowns, 1000);
updateCountdowns(); // initial call
</script>
@endsection
