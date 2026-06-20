@extends('layouts.app')

@section('title', 'Pemesanan - Risha Catering')

@section('styles')
    <style>
        .orders-page {
            padding: 3rem 0 5rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #64748b;
        }

        .orders-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(360px, 0.75fr);
            gap: 2rem;
            align-items: start;
        }

        /* Checkout Form */
        .checkout-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .checkout-card.is-sticky {
            position: sticky;
            top: 1.5rem;
        }

        .checkout-card-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
        }

        .checkout-card-header h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
        }

        .checkout-card-header p {
            font-size: 0.78rem;
            opacity: 0.6;
            margin-top: 2px;
        }

        .checkout-card-body {
            padding: 1.5rem;
        }

        /* Cart summary in checkout */
        .cart-summary {
            margin-bottom: 0;
        }

        .cart-summary-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.875rem;
        }

        .cart-summary-title h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .cart-summary-title a {
            color: #E8572A;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            padding: 0.4rem 0.8rem;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .cart-summary-empty {
            text-align: center;
            padding: 1.5rem;
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .cart-summary-empty a {
            color: #E8572A;
            text-decoration: none;
            font-weight: 600;
        }

        .cart-sum-item {
            display: flex;
            gap: 0.875rem;
            align-items: center;
            padding: 0.9rem;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 0.9rem;
            background: #fff;
        }

        .cart-sum-item:last-child {
            margin-bottom: 0;
        }

        .cart-sum-img {
            width: 62px;
            height: 62px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            background: #f1f5f9;
        }

        .cart-sum-info {
            flex: 1;
            min-width: 0;
        }

        .cart-sum-item .item-category {
            color: #94a3b8;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.15rem;
        }

        .cart-sum-item .item-name {
            color: #334155;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cart-sum-item .item-qty {
            color: #94a3b8;
            font-size: 0.78rem;
        }

        .cart-sum-item .item-price {
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.25rem;
        }

        .cart-sum-actions {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-shrink: 0;
        }

        .cart-sum-actions .qty-btn,
        .cart-sum-remove {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid #fed7aa;
            background: #fff7ed;
            color: #E8572A;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 800;
            transition: all 0.2s;
        }

        .cart-sum-actions .qty-btn:hover,
        .cart-sum-remove:hover {
            background: #E8572A;
            color: #fff;
        }

        .cart-sum-actions .qty-display {
            min-width: 24px;
            text-align: center;
            font-weight: 800;
            color: #0f172a;
        }

        .cart-sum-remove {
            margin-left: 0.25rem;
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }

        .cart-sum-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0 0;
            border-top: 2px solid #f1f5f9;
            margin-top: 0.5rem;
        }

        .cart-sum-total .label {
            font-weight: 700;
            color: #0f172a;
        }

        .cart-sum-total .value {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #E8572A;
        }

        .payment-summary {
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
        }

        .payment-summary h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.85rem;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.55rem 0;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .payment-row.total {
            border-top: 1px solid #f1f5f9;
            margin-top: 0.35rem;
            padding-top: 0.85rem;
            color: #0f172a;
        }

        .payment-row.total span:last-child {
            color: #E8572A;
            font-family: 'Outfit', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
        }

        /* Form */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.375rem;
        }

        .form-group input,
        .form-group textarea {
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

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #E8572A;
            box-shadow: 0 0 0 3px rgba(232, 87, 42, 0.1);
            background: white;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 72px;
        }

        .field-error {
            color: #dc2626;
            font-size: 0.78rem;
            margin-top: 3px;
        }

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

        .btn-pesan:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(232, 87, 42, 0.4);
        }

        .btn-pesan:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Orders List */
        .orders-list-section {}

        .section-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .section-title-row h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

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
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            animation: livePulse 2s infinite;
        }

        @keyframes livePulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.8);
            }
        }

        .order-card {
            background: white;
            border-radius: 14px;
            border: 1.5px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.25rem;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }

        .order-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

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

        .order-date {
            font-size: 0.78rem;
            color: #94a3b8;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .status-indicator::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-pending {
            background: #fef9c3;
            color: #a16207;
        }

        .status-pending::before {
            background: #f59e0b;
            animation: livePulse 1.5s infinite;
        }

        .status-diproses {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-diproses::before {
            background: #3b82f6;
            animation: livePulse 2s infinite;
        }

        .status-dikirim {
            background: #ede9fe;
            color: #5b21b6;
        }

        .status-dikirim::before {
            background: #7c3aed;
            animation: livePulse 2s infinite;
        }

        .status-selesai {
            background: #dcfce7;
            color: #15803d;
        }

        .status-selesai::before {
            background: #22c55e;
        }

        .status-dibatalkan {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-dibatalkan::before {
            background: #ef4444;
        }

        .order-card-body {
            padding: 1.25rem;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .order-info-item {
            font-size: 0.82rem;
        }

        .order-info-item .lbl {
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
        }

        .order-info-item .val {
            color: #334155;
            font-weight: 600;
            margin-top: 2px;
        }

        .order-items-list {
            border-top: 1px solid #f1f5f9;
            padding-top: 0.875rem;
        }

        .order-items-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            margin-bottom: 0.5rem;
        }

        .order-item-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.82rem;
            padding: 0.25rem 0;
            color: #475569;
        }

        .order-item-row .item-name {
            font-weight: 600;
        }

        .order-item-row .item-total {
            font-weight: 700;
            color: #0f172a;
        }

        .order-card-footer {
            padding: 0.875rem 1.25rem;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-grand-total {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
        }

        .order-grand-total small {
            font-size: 0.72rem;
            color: #94a3b8;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }

        .order-progress {
            display: flex;
            gap: 8px;
            margin-top: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .order-progress span {
            padding: 5px 12px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid #e2e8f0;
        }

        .order-progress span.active {
            background: #22c55e;
            color: white;
            border-color: #22c55e;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.2);
        }

        .no-orders-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            border: 2px dashed #e2e8f0;
            color: #94a3b8;
        }

        .no-orders-state .icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }

        .no-orders-state p {
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
        }

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

        .no-orders-state a:hover {
            background: #d14a20;
        }

        .btn-invoice {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-invoice:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .map-wrapper {
            position: relative;
        }

        #map {
            height: 320px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            margin-bottom: 0.5rem;
        }

        .locate-btn {
            position: absolute;
            top: 80px;
            left: 10px;
            z-index: 1000;
            background: white;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            width: 34px;
            height: 34px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .locate-btn:hover {
            background: #f4f4f4;
        }

        .leaflet-routing-container {
            display: none !important;
        }

        .map-pin {
            width: 34px;
            height: 34px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            border: 3px solid #fff;
            box-shadow: 0 6px 14px rgba(15, 23, 42, .25);
        }

        .map-pin::after {
            content: "";
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #fff;
            top: 8px;
            left: 8px;
        }

        .map-pin-store {
            background: #2563eb;
        }

        .map-pin-user {
            background: #e8572a;
        }

        .distance-label div {
            background: #16a34a;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
            text-align: center;
        }

        .map-area-warning {
            display: none;
            margin-top: 0.5rem;
            padding: 0.65rem 0.8rem;
            border: 1px solid #fecaca;
            border-radius: 10px;
            background: #fef2f2;
            color: #dc2626;
            font-size: 0.78rem;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .orders-layout {
                grid-template-columns: 1fr;
            }

            .checkout-card.is-sticky {
                position: static;
            }

            .cart-sum-item {
                align-items: flex-start;
            }

            .cart-sum-actions {
                flex-wrap: wrap;
                justify-content: flex-end;
                max-width: 96px;
            }

            #map {
                height: 360px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="orders-page">
        <div class="container">
            <div class="page-header">
                <h1>Pemesanan</h1>
                <p>Isi form di bawah untuk menyelesaikan pesanan Anda.</p>
            </div>

            <div class="orders-layout">
                <div class="checkout-card">
                    <div class="checkout-card-header">
                        <h3>Daftar Item</h3>
                        <p>Periksa menu pilihan Anda</p>
                    </div>
                    <div class="checkout-card-body">
                        <div class="cart-summary" id="checkoutCartSummary">
                            <div class="cart-summary-title">
                                <h4>Keranjang Saya</h4>
                                <a href="{{ route('menu') }}">Tambah Menu</a>
                            </div>
                            <div class="cart-summary-empty" id="cartSummaryEmpty">
                                <p>Keranjang kosong.<br><a href="{{ route('menu') }}">Tambah menu terlebih dahulu</a>
                                </p>
                            </div>
                            <div id="cartSummaryItems" style="display:none"></div>
                            <div class="cart-sum-total" id="cartSummaryTotal" style="display:none">
                                <span class="label">Total Pesanan</span>
                                <span class="value" id="checkoutTotal">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkout-card is-sticky">
                    <div class="checkout-card-header">
                        <h3>Form Pemesanan</h3>
                        <p>Isi detail acara dan lokasi</p>
                    </div>
                    <div class="checkout-card-body">
                        <div class="payment-summary">
                            <h4>Ringkasan</h4>
                            <div class="payment-row">
                                <span>Subtotal Menu</span>
                                <span id="checkoutSideSubtotal">Rp 0</span>
                            </div>
                            <div class="payment-row">
                                <span>Ongkos Kirim</span>
                                <span>Belum tersedia</span>
                            </div>
                            <div class="payment-row total">
                                <span>Total Bayar</span>
                                <span id="checkoutSideTotal">Rp 0</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('cart.checkout') }}" id="checkoutForm">
                                @csrf

                                @if ($errors->any())
                                    <div
                                        style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 0.875rem 1rem; margin-bottom: 1rem; font-size: 0.82rem; color: #dc2626;">
                                        @foreach ($errors->all() as $error)
                                            <div>- {{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label>Nama Pemesan *</label>
                                    <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                        placeholder="Nama lengkap Anda" required>
                                    @error('customer_name')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nomor HP / WhatsApp *</label>
                                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                                        placeholder="cth: 08123456789" required>
                                    @error('customer_phone')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Acara *</label>
                                    <input type="date" name="event_date" value="{{ old('event_date') }}"
                                        min="{{ date('Y-m-d') }}" required>
                                    @error('event_date')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Alamat Lokasi Acara *</label>
                                    <textarea id="address" name="event_address" placeholder="Alamat lengkap tempat acara" required>{{ old('event_address') }}</textarea>
                                    @error('event_address')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Pilih Lokasi pada Peta</label>
                                    <div class="map-wrapper">
                                        <button type="button" id="locateBtn" class="locate-btn"
                                            title="Cari Lokasi Saya">+</button>
                                        <div id="map"></div>
                                    </div>
                                    <p class="map-area-warning" id="mapAreaWarning">
                                        Lokasi pemesanan hanya mencakup area Jabodetabek.
                                    </p>
                                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                                    <input type="hidden" name="longitude" id="longitude"
                                        value="{{ old('longitude') }}">
                                </div>

                                <div class="form-group">
                                    <label>Metode Pembayaran *</label>
                                    <select name="payment_method" required
                                        style="width: 100%; padding: 0.7rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; font-family: 'Inter', sans-serif; color: #0f172a; outline: none; background: #f8fafc;">
                                        <option value="" disabled selected>Pilih metode pembayaran</option>
                                        <option value="duitku">Duitku (Virtual Account / e-Wallet)</option>
                                    </select>
                                    @error('payment_method')
                                        <p class="field-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Catatan Tambahan</label>
                                    <textarea name="notes" placeholder="Permintaan khusus, alergi makanan, dll.">{{ old('notes') }}</textarea>
                                </div>

                                <button type="submit" class="btn-pesan" id="btnPesan" disabled>
                                    Buat Pesanan
                                </button>
                                <p id="checkoutEmptyHint"
                                    style="font-size: 0.72rem; color: #94a3b8; text-align: center; margin-top: 0.5rem;">
                                    Tambahkan menu ke keranjang terlebih dahulu.
                                </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Cart Summary di halaman pesanan 
        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = value ?? '';
            return div.innerHTML;
        }

        function renderCheckoutCart(data) {
            const empty = document.getElementById('cartSummaryEmpty');
            const items = document.getElementById('cartSummaryItems');
            const total = document.getElementById('cartSummaryTotal');
            const btnPesan = document.getElementById('btnPesan');
            const checkoutEmptyHint = document.getElementById('checkoutEmptyHint');
            const checkoutSideSubtotal = document.getElementById('checkoutSideSubtotal');
            const checkoutSideTotal = document.getElementById('checkoutSideTotal');
            const formattedTotal = 'Rp ' + Number(data.total || 0).toLocaleString('id-ID');

            checkoutSideSubtotal.textContent = formattedTotal;
            checkoutSideTotal.textContent = formattedTotal;

            if (!data.cart || data.cart.length === 0) {
                empty.style.display = 'block';
                items.style.display = 'none';
                total.style.display = 'none';
                btnPesan.disabled = true;
                btnPesan.textContent = 'Buat Pesanan';
                checkoutEmptyHint.style.display = 'block';
                return;
            }

            empty.style.display = 'none';
            items.style.display = 'block';
            total.style.display = 'flex';
            btnPesan.disabled = false;
            btnPesan.textContent = `Pesan Sekarang (${data.count} item)`;
            checkoutEmptyHint.style.display = 'none';

            items.innerHTML = data.cart.map(item => {
                const imgSrc = escapeHtml(item.image && item.image.startsWith('http')
                    ? item.image
                    : '/' + (item.image || 'images/placeholder.png'));
                const name = escapeHtml(item.name);
                const category = escapeHtml(item.category || 'Menu');
                const menuId = Number(item.menu_id);
                const quantity = Number(item.quantity);
                const price = Number(item.price);

                return `
 <div class="cart-sum-item">
 <img src="${imgSrc}" alt="${name}" class="cart-sum-img"
 onerror="this.src='https://via.placeholder.com/62x62/E8572A/white?text=M'">
 <div class="cart-sum-info">
 <div class="item-category">${category}</div>
 <div class="item-name">${name}</div>
 <div class="item-price">Rp ${price.toLocaleString('id-ID')}</div>
 </div>
 <div class="cart-sum-actions">
 <button type="button" class="qty-btn" onclick="updateQty(${menuId}, ${quantity - 1})" aria-label="Kurangi ${name}">-</button>
 <span class="qty-display">${quantity}</span>
 <button type="button" class="qty-btn" onclick="updateQty(${menuId}, ${quantity + 1})" aria-label="Tambah ${name}">+</button>
 <button type="button" class="cart-sum-remove" onclick="removeFromCart(${menuId})" aria-label="Hapus ${name}">&times;</button>
 </div>
 </div>
 `;
            }).join('');

            document.getElementById('checkoutTotal').textContent = 'Rp ' + Number(data.total).toLocaleString('id-ID');
        }

        // Load cart on page load
        fetch('{{ route('cart.get') }}')
            .then(r => r.json())
            .then(data => {
                cartData = data;
                renderCheckoutCart(data);
            });

        // Leaflet Map
        const jabodetabekBounds = L.latLngBounds(
            L.latLng(-6.90, 106.35),
            L.latLng(-5.90, 107.25)
        );
        const map = L.map('map', {
            maxBounds: jabodetabekBounds,
            maxBoundsViscosity: 1.0,
            minZoom: 9
        }).setView([-6.30, 106.85], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        map.fitBounds(jabodetabekBounds);

        let marker;
        let routingControl;
        let fallbackRoute;
        let distanceMarker;
        const cateringLat = -6.410915594179738;
        const cateringLng = 106.75735160036807;
        const catering = L.latLng(cateringLat, cateringLng);
        const mapAreaWarning = document.getElementById('mapAreaWarning');
        const storeIcon = L.divIcon({
            className: '',
            html: '<div class="map-pin map-pin-store"></div>',
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -34]
        });
        const userIcon = L.divIcon({
            className: '',
            html: '<div class="map-pin map-pin-user"></div>',
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -34]
        });

        // Marker catering
        L.marker(catering, {
            icon: storeIcon
        }).addTo(map).bindPopup('Risha Catering');

        function isInJabodetabek(lat, lng) {
            return jabodetabekBounds.contains(L.latLng(Number(lat), Number(lng)));
        }

        function showMapAreaWarning(message) {
            mapAreaWarning.textContent = message;
            mapAreaWarning.style.display = 'block';
        }

        function hideMapAreaWarning() {
            mapAreaWarning.style.display = 'none';
        }

        async function setMarker(lat, lng) {
            if (!isInJabodetabek(lat, lng)) {
                showMapAreaWarning('Lokasi pemesanan hanya mencakup area Jabodetabek. Silakan pilih titik di Jakarta, Bogor, Depok, Tangerang, atau Bekasi.');
                map.fitBounds(jabodetabekBounds);
                return;
            }

            hideMapAreaWarning();
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            const user = L.latLng(lat, lng);

            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(user, {
                icon: userIcon
            }).addTo(map).bindPopup('Lokasi User').openPopup();

            if (routingControl) {
                map.removeControl(routingControl);
            }

            if (fallbackRoute) {
                map.removeLayer(fallbackRoute);
            }

            if (distanceMarker) {
                map.removeLayer(distanceMarker);
            }

            routingControl = L.Routing.control({
                waypoints: [
                    catering,
                    user
                ],
                lineOptions: {
                    styles: [{
                        color: '#2563eb',
                        opacity: 0.9,
                        weight: 5
                    }]
                },
                routeWhileDragging: false,
                addWaypoints: false,
                draggableWaypoints: false,
                show: false,
                collapsible: false,
                fitSelectedRoutes: true,
                createMarker: function() {
                    return null;
                }
            }).addTo(map);

            fallbackRoute = L.polyline([catering, user], {
                color: '#16a34a',
                weight: 4,
                opacity: 0.85,
                dashArray: '8 8'
            }).addTo(map);

            routingControl.on('routesfound', function() {
                if (fallbackRoute) {
                    map.removeLayer(fallbackRoute);
                    fallbackRoute = null;
                }
            });

            routingControl.on('routingerror', function(error) {
                console.warn('Rute jalan tidak tersedia, memakai garis lurus sementara.', error);
            });

            function hitungJarak(lat1, lon1, lat2, lon2) {
                const R = 6371; // KM
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;

                const a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) *
                    Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) *
                    Math.sin(dLon / 2);

                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return R * c;
            }

            const jarak = hitungJarak(cateringLat, cateringLng, lat, lng);
            const tengahLat = (cateringLat + lat) / 2;
            const tengahLng = (cateringLng + lng) / 2;

            distanceMarker = L.marker([tengahLat, tengahLng], {
                icon: L.divIcon({
                    className: 'distance-label',
                    html: `<div>${jarak.toFixed(2)} km</div>`,
                    iconSize: [100, 30]
                })
            }).addTo(map);

            map.fitBounds(L.latLngBounds([catering, user]).pad(0.15), {
                maxZoom: 14
            });

            // Ambil alamat otomatis
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
                );
                const data = await response.json();
                if (data && data.display_name) {
                    document.getElementById('address').value = data.display_name;
                } else {
                    document.getElementById('address').value = 'Alamat tidak ditemukan';
                }
            } catch (error) {
                console.error('Error fetching address:', error);
            }
        }

        // If we already have old inputs, show marker
        const oldLat = document.getElementById('latitude').value;
        const oldLng = document.getElementById('longitude').value;
        if (oldLat && oldLng) {
            setMarker(oldLat, oldLng);
        } else {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        setMarker(position.coords.latitude, position.coords.longitude);
                    },
                    function() {
                        // Ignore or show subtle warning
                    }
                );
            }
        }

        document.getElementById('locateBtn').addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung lokasi.');
                return;
            }

            navigator.geolocation.getCurrentPosition(function(position) {
                if (!isInJabodetabek(position.coords.latitude, position.coords.longitude)) {
                    showMapAreaWarning('Lokasi Anda berada di luar Jabodetabek. Silakan pilih titik acara di area Jabodetabek.');
                    map.fitBounds(jabodetabekBounds);
                    return;
                }

                setMarker(position.coords.latitude, position.coords.longitude);
            }, function() {
                alert('Izinkan akses lokasi terlebih dahulu.');
            });
        });

        map.on('click', function(e) {
            setMarker(e.latlng.lat, e.latlng.lng);
        });
    </script>
@endsection
