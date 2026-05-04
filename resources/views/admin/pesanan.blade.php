@extends('layouts.admin')

@section('page-title', 'Pesanan Masuk')
@section('breadcrumb', 'Manajemen Pesanan')

@section('styles')
<style>
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .stats-mini {
        display: flex;
        gap: 1rem;
    }
    .stat-mini-card {
        background: white;
        border-radius: 10px;
        padding: 0.875rem 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        text-align: center;
        min-width: 110px;
    }
    .stat-mini-card .val { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; }
    .stat-mini-card .lbl { font-size: 0.7rem; color: #94a3b8; font-weight: 600; }
    .filter-bar { display: flex; gap: 0.5rem; }
    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: white;
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }
    .filter-btn.active { background: #E8572A; color: white; border-color: #E8572A; }
    .filter-btn:hover:not(.active) { background: #f1f5f9; }

    .orders-table-wrapper {
        background: white;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; }
    th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        background: #f8fafc;
        white-space: nowrap;
    }
    td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; font-size: 0.875rem; color: #334155; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafafa; }

    .order-id { font-weight: 700; color: #E8572A; font-size: 0.9rem; }
    .customer-name { font-weight: 700; color: #0f172a; }
    .customer-phone { font-size: 0.78rem; color: #94a3b8; }
    .items-list { font-size: 0.8rem; color: #475569; max-width: 220px; }
    .items-list div { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .status-select {
        padding: 5px 10px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        outline: none;
        transition: all 0.2s;
        background: white;
    }
    .status-select:focus { border-color: #E8572A; }
    .status-select.pending { color: #a16207; background: #fef9c3; border-color: #fde68a; }
    .status-select.diproses { color: #1d4ed8; background: #dbeafe; border-color: #bfdbfe; }
    .status-select.selesai { color: #15803d; background: #dcfce7; border-color: #bbf7d0; }
    .status-select.dibatalkan { color: #dc2626; background: #fee2e2; border-color: #fecaca; }

    .refresh-badge {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.78rem;
        color: #94a3b8;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        background: #f1f5f9;
    }
    .refresh-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #22c55e;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .empty-state { padding: 4rem 2rem; text-align: center; color: #94a3b8; }
    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 1rem; }

    .event-date { font-size: 0.8rem; color: #475569; }
    .event-addr { font-size: 0.75rem; color: #94a3b8; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .total-price { font-weight: 700; font-size: 0.9rem; color: #0f172a; }
</style>
@endsection

@section('content')

<div class="orders-header">
    <div class="stats-mini">
        <div class="stat-mini-card">
            <div class="val" style="color: #f59e0b;" id="countPending">{{ $orders->where('status','pending')->count() }}</div>
            <div class="lbl">Pending</div>
        </div>
        <div class="stat-mini-card">
            <div class="val" style="color: #1d4ed8;">{{ $orders->where('status','diproses')->count() }}</div>
            <div class="lbl">Diproses</div>
        </div>
        <div class="stat-mini-card">
            <div class="val" style="color: #16a34a;">{{ $orders->where('status','selesai')->count() }}</div>
            <div class="lbl">Selesai</div>
        </div>
    </div>

    <div style="display:flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
        <div class="refresh-badge">
            <span class="refresh-dot"></span>
            Auto-refresh: <span id="countdownEl">30</span>s
        </div>
        <div class="filter-bar">
            <button class="filter-btn active" onclick="filterStatus(this,'all')">Semua</button>
            <button class="filter-btn" onclick="filterStatus(this,'pending')">Pending</button>
            <button class="filter-btn" onclick="filterStatus(this,'diproses')">Diproses</button>
            <button class="filter-btn" onclick="filterStatus(this,'selesai')">Selesai</button>
        </div>
    </div>
</div>

<div class="orders-table-wrapper">
    <table id="ordersTable">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Menu Dipesan</th>
                <th>Acara</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody id="ordersBody">
            @forelse($orders as $order)
            <tr data-status="{{ $order->status }}" id="order-row-{{ $order->id }}">
                <td class="order-id">#ORD-{{ $order->id }}</td>
                <td>
                    <div class="customer-name">{{ $order->customer_name }}</div>
                    <div class="customer-phone">📱 {{ $order->customer_phone }}</div>
                </td>
                <td>
                    <div class="items-list">
                        @foreach($order->items as $item)
                        <div>• {{ $item->quantity }}x {{ $item->menu->name ?? 'Menu dihapus' }}</div>
                        @endforeach
                    </div>
                </td>
                <td>
                    <div class="event-date">📅 {{ $order->event_date ? $order->event_date->format('d M Y') : '-' }}</div>
                    <div class="event-addr" title="{{ $order->event_address }}">📍 {{ $order->event_address ?? '-' }}</div>
                </td>
                <td class="total-price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>
                    <div style="font-size: 0.78rem; font-weight: 700; text-transform: capitalize; color: #334155;">
                        {{ $order->payment_method ?? 'Cash' }}
                    </div>
                    @if($order->payment_status === 'unpaid')
                        <span style="display:inline-block; margin-bottom: 5px; padding: 2px 8px; background: #fee2e2; color: #dc2626; border-radius: 10px; font-size: 0.7rem; font-weight: 700;">Belum Lunas</span><br>
                        <button onclick="openConfirmPaymentModal({{ $order->id }})" style="background: #16a34a; color: white; border: none; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; cursor: pointer; font-weight: 600;">
                            ✅ Konfirmasi Bayar
                        </button>
                    @elseif($order->payment_status === 'paid')
                        <span style="display:inline-block; margin-bottom: 5px; padding: 2px 8px; background: #dcfce7; color: #15803d; border-radius: 10px; font-size: 0.7rem; font-weight: 700;">Lunas</span><br>
                        @if($order->estimation_time)
                            <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">⏱️ {{ $order->estimation_time }}</div>
                        @endif
                    @elseif($order->payment_status === 'expired')
                        <span style="display:inline-block; margin-bottom: 5px; padding: 2px 8px; background: #f1f5f9; color: #64748b; border-radius: 10px; font-size: 0.7rem; font-weight: 700;">Kedaluwarsa</span>
                    @endif
                </td>
                <td>
                    <select class="status-select {{ $order->status }}"
                            data-order-id="{{ $order->id }}"
                            onchange="updateStatus(this)">
                        @foreach(['pending','diproses','selesai','dibatalkan'] as $st)
                        <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>
                            {{ ucfirst($st) }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td style="font-size: 0.78rem; color: #94a3b8; white-space: nowrap;">
                    {{ $order->created_at->diffForHumans() }}
                </td>
            </tr>
            @empty
            <tr><td colspan="7">
                <div class="empty-state">
                    <div class="empty-icon">🛒</div>
                    <p>Belum ada pesanan masuk. Bagikan website Anda kepada pelanggan!</p>
                </div>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
<div style="margin-top: 1.5rem; display: flex; justify-content: center;">
    {{ $orders->links() }}
</div>
@endif

<!-- Modal Konfirmasi Pembayaran -->
<div id="paymentModalOverlay" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9998; backdrop-filter: blur(2px);"></div>
<div id="paymentModal" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 2rem; border-radius: 16px; z-index: 9999; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 90%; max-width: 400px;">
    <h3 style="margin-top:0; font-family:'Outfit',sans-serif; color:#0f172a;">✅ Konfirmasi Pembayaran</h3>
    <p style="font-size:0.875rem; color:#64748b; margin-bottom:1.5rem;">Pesanan akan ditandai Lunas dan status berubah menjadi Diproses.</p>
    
    <form id="confirmPaymentForm">
        <input type="hidden" id="confirmOrderId">
        <div style="margin-bottom: 1rem;">
            <label style="display:block; font-size:0.78rem; font-weight:700; margin-bottom:0.5rem; color:#475569;">Estimasi Waktu Pembuatan (Opsional)</label>
            <input type="text" id="estimationTime" placeholder="Contoh: 45 Menit, 1 Jam" style="width: 100%; padding: 0.7rem; border: 1.5px solid #e2e8f0; border-radius: 8px; box-sizing: border-box; font-family:'Inter',sans-serif; font-size:0.875rem;">
        </div>
        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1.5rem;">
            <button type="button" onclick="closeConfirmPaymentModal()" style="padding: 0.6rem 1rem; border: none; background: #f1f5f9; color: #475569; border-radius: 8px; cursor: pointer; font-weight: 600;">Batal</button>
            <button type="submit" style="padding: 0.6rem 1rem; border: none; background: #16a34a; color: white; border-radius: 8px; cursor: pointer; font-weight: 700;">Konfirmasi & Simpan</button>
        </div>
    </form>
</div>


@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Update status via AJAX
function updateStatus(selectEl) {
    const orderId = selectEl.dataset.orderId;
    const newStatus = selectEl.value;

    selectEl.disabled = true;

    fetch(`/admin/pesanan/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            selectEl.className = `status-select ${newStatus}`;
            const row = selectEl.closest('tr');
            row.dataset.status = newStatus;
            showToast(`Pesanan #ORD-${orderId} → ${newStatus}`);
            // Apply current filter
            filterStatus(document.querySelector('.filter-btn.active'), currentFilter);
        }
    })
    .catch(() => showToast('Gagal memperbarui status.', 'error'))
    .finally(() => { selectEl.disabled = false; });
}

// Filter by status
let currentFilter = 'all';
function filterStatus(btn, status) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentFilter = status;
    document.querySelectorAll('#ordersTable tbody tr[data-status]').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
}

// Payment Confirmation Logic
function openConfirmPaymentModal(orderId) {
    document.getElementById('confirmOrderId').value = orderId;
    document.getElementById('estimationTime').value = '';
    document.getElementById('paymentModalOverlay').style.display = 'block';
    document.getElementById('paymentModal').style.display = 'block';
}

function closeConfirmPaymentModal() {
    document.getElementById('paymentModalOverlay').style.display = 'none';
    document.getElementById('paymentModal').style.display = 'none';
}

document.getElementById('confirmPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const orderId = document.getElementById('confirmOrderId').value;
    const estimation = document.getElementById('estimationTime').value;

    fetch(`/admin/pesanan/${orderId}/konfirmasi-pembayaran`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ estimation_time: estimation })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000); // Reload to reflect full changes easily
        } else {
            showToast(data.message || 'Gagal konfirmasi pembayaran.', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'))
    .finally(() => closeConfirmPaymentModal());
});

// Auto-refresh countdown (30s)
let countdown = 30;
const countdownEl = document.getElementById('countdownEl');
setInterval(() => {
    countdown--;
    if (countdownEl) countdownEl.textContent = countdown;
    if (countdown <= 0) {
        window.location.reload();
    }
}, 1000);
</script>
@endsection
