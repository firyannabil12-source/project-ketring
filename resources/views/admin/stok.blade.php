@extends('layouts.admin')

@section('page-title', 'Kelola Stok & Menu')
@section('breadcrumb', 'Manajemen Stok')

@section('styles')
<style>
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .search-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        flex: 1;
        max-width: 320px;
    }
    .search-box input {
        border: none; outline: none;
        font-size: 0.875rem;
        color: #334155;
        background: transparent;
        width: 100%;
    }
    .btn-add {
        background: #E8572A;
        color: white;
        padding: 0.7rem 1.25rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .btn-add:hover { background: #d14a20; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(232,87,42,0.35); }

    .stok-table-wrapper {
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

    .menu-img {
        width: 44px; height: 44px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .menu-name { font-weight: 700; color: #0f172a; font-size: 0.9rem; }
    .menu-desc { font-size: 0.75rem; color: #94a3b8; margin-top: 2px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .category-pill {
        background: #f1f5f9;
        color: #475569;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.04em;
    }

    /* Stock Controls */
    .stock-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .stock-btn {
        width: 32px; height: 32px;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
        line-height: 1;
    }
    .stock-btn.minus { background: #fee2e2; color: #dc2626; }
    .stock-btn.minus:hover { background: #dc2626; color: white; }
    .stock-btn.plus { background: #dcfce7; color: #16a34a; }
    .stock-btn.plus:hover { background: #16a34a; color: white; }
    .stock-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .stock-display {
        min-width: 56px;
        text-align: center;
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px 8px;
    }
    .stock-display.critical { color: #dc2626; border-color: #fecaca; background: #fff5f5; }
    .stock-display.low { color: #d97706; border-color: #fde68a; background: #fffbeb; }
    .stock-display.ok { color: #16a34a; border-color: #bbf7d0; background: #f0fdf4; }

    .status-badge { font-size: 0.78rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
    .status-ok { background: #dcfce7; color: #15803d; }
    .status-low { background: #fef9c3; color: #a16207; }
    .status-out { background: #fee2e2; color: #dc2626; }

    .action-btns { display: flex; gap: 0.5rem; }
    .btn-edit {
        background: #dbeafe;
        color: #1d4ed8;
        border: none;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-edit:hover { background: #1d4ed8; color: white; }
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-delete:hover { background: #dc2626; color: white; }

    .saving-indicator {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-left: 0.25rem;
        display: none;
    }
    .saving-indicator.show { display: inline-block; }

    /* Filter tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        background: white;
        border-radius: 10px;
        padding: 0.375rem;
        border: 1.5px solid #e2e8f0;
    }
    .filter-tab {
        padding: 0.4rem 1rem;
        border-radius: 7px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background: transparent;
        color: #64748b;
        transition: all 0.15s;
    }
    .filter-tab.active { background: #E8572A; color: white; }
    .filter-tab:hover:not(.active) { background: #f1f5f9; color: #334155; }
</style>
@endsection

@section('content')

@if(session('success'))
<div id="flashToast" style="display:none">{{ session('success') }}</div>
@endif

<div class="toolbar">
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;">
        <div class="search-box">
            🔍
            <input type="text" id="searchInput" placeholder="Cari nama menu..." oninput="filterTable()">
        </div>
        <div class="filter-tabs" id="categoryFilter">
            <button class="filter-tab active" onclick="filterCategory(this, 'all')">Semua</button>
            @php $categories = \App\Models\Menu::select('category')->distinct()->pluck('category'); @endphp
            @foreach($categories as $cat)
            <button class="filter-tab" onclick="filterCategory(this, '{{ $cat }}')">{{ ucwords(strtolower($cat)) }}</button>
            @endforeach
        </div>
    </div>
    <a href="{{ route('admin.menu.create') }}" class="btn-add">➕ Tambah Menu Baru</a>
</div>

<div class="stok-table-wrapper">
    <table id="stokTable">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr data-category="{{ $menu->category }}" data-name="{{ strtolower($menu->name) }}" id="row-{{ $menu->id }}">
                <td>
                    <div style="display: flex; align-items: center; gap: 0.875rem;">
                        <img src="{{ Str::startsWith($menu->image, 'http') ? $menu->image : asset($menu->image) }}"
                             alt="{{ $menu->name }}" class="menu-img"
                             onerror="this.src='https://via.placeholder.com/44x44/E8572A/white?text=M'">
                        <div>
                            <div class="menu-name">{{ $menu->name }}</div>
                            <div class="menu-desc">{{ $menu->description }}</div>
                        </div>
                    </div>
                </td>
                <td><span class="category-pill">{{ $menu->category }}</span></td>
                <td style="font-weight: 700; color: #E8572A;">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.menu.edit', $menu) }}" class="btn-edit">✏️ Edit</a>
                        <form method="POST" action="{{ route('admin.menu.destroy', $menu) }}" onsubmit="return confirm('Hapus menu \"{{ $menu->name }}\"? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">🗑️ Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center; padding: 3rem; color: #94a3b8;">Belum ada menu. <a href="{{ route('admin.menu.create') }}" style="color: #E8572A;">Tambah sekarang →</a></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// Flash toast
const flashToast = document.getElementById('flashToast');
if (flashToast) showToast(flashToast.textContent.trim());



// Search filter
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#stokTable tbody tr').forEach(row => {
        const name = row.dataset.name || '';
        row.style.display = name.includes(q) ? '' : 'none';
    });
}

// Category filter
let currentCategory = 'all';
function filterCategory(btn, cat) {
    document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentCategory = cat;
    document.querySelectorAll('#stokTable tbody tr').forEach(row => {
        const rowCat = (row.dataset.category || '').toUpperCase();
        const show = cat === 'all' || rowCat === cat.toUpperCase();
        row.style.display = show ? '' : 'none';
    });
}
</script>
@endsection
