@extends('layouts.app')

@section('title', 'Menu Kami — Ketring Mama Iksan')

@section('styles')
<style>
    .menu-section { padding: 3rem 0 5rem; }
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
    .page-header p { color: #64748b; font-size: 1rem; }

    /* Category Filter */
    .category-bar {
        display: flex;
        justify-content: center;
        gap: 0.625rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }
    .cat-btn {
        padding: 0.5rem 1.25rem;
        border-radius: 30px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #64748b;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    .cat-btn.active { background: #E8572A; color: white; border-color: #E8572A; }
    .cat-btn:hover:not(.active) { border-color: #E8572A; color: #E8572A; }

    /* Menu Grid */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .food-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        transition: all 0.3s;
        border: 1px solid #f1f5f9;
        position: relative;
    }
    .food-card:hover { transform: translateY(-5px); box-shadow: 0 12px 35px rgba(0,0,0,0.12); }


    .food-img-wrapper { position: relative; overflow: hidden; }
    .food-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.4s;
    }
    .food-card:hover .food-img { transform: scale(1.05); }



    .food-info { padding: 1.25rem; }
    .food-category {
        background: #fef3f2;
        color: #E8572A;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        display: inline-block;
        margin-bottom: 0.625rem;
    }
    .food-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.375rem;
        line-height: 1.3;
    }
    .food-desc {
        color: #64748b;
        font-size: 0.82rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .food-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }
    .food-price {
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 800;
        color: #E8572A;
    }
    .food-price small { font-size: 0.7rem; color: #94a3b8; font-weight: 500; font-family: 'Inter', sans-serif; }

    .btn-add-cart {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
    }
    .btn-add-cart:hover { background: #E8572A; transform: scale(1.03); }
    .btn-add-cart:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-add-cart.adding { background: #16a34a; }
    .btn-add-cart.adding::after { content: ' ✓'; }

    /* Empty state */
    .empty-category {
        grid-column: 1/-1;
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }
</style>
@endsection

@section('content')
<section class="menu-section">
    <div class="container">
        <div class="page-header">
            <h1>🍛 Daftar Menu Kami</h1>
            <p>Pilih menu katering terbaik untuk acara istimewa Anda — segar, higienis, dan penuh cinta.</p>
        </div>

        <!-- Category Filters -->
        <div class="category-bar" id="categoryBar">
            <button class="cat-btn active" onclick="filterCat(this, 'all')">🍽️ Semua</button>
            @php
                $categories = $menus->pluck('category')->unique()->sort();
            @endphp
            @foreach($categories as $cat)
            <button class="cat-btn" onclick="filterCat(this, '{{ $cat }}')">{{ ucwords(strtolower($cat)) }}</button>
            @endforeach
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid" id="menuGrid">
            @forelse($menus as $menu)
            <div class="food-card"
                 data-category="{{ $menu->category }}"
                 id="menu-card-{{ $menu->id }}">

                <div class="food-img-wrapper">
                    <img src="{{ Str::startsWith($menu->image, 'http') ? $menu->image : asset($menu->image) }}"
                         alt="{{ $menu->name }}"
                         class="food-img"
                         onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80'">
                </div>

                <div class="food-info">
                    <span class="food-category">{{ $menu->category }}</span>
                    <h3 class="food-name">{{ $menu->name }}</h3>
                    <p class="food-desc">{{ $menu->description }}</p>
                    <div class="food-footer">
                        <div class="food-price">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                            <small>/ porsi</small>
                        </div>
                        <button
                            class="btn-add-cart"
                            id="cart-btn-{{ $menu->id }}"
                            onclick="handleAddToCart(this, {{ $menu->id }}, '{{ addslashes($menu->name) }}')">
                            🛒 Tambah
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-category">
                <div style="font-size: 3rem; margin-bottom: 1rem;">😔</div>
                <p>Belum ada menu tersedia saat ini.</p>
            </div>
            @endforelse
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script>
// Category Filter
function filterCat(btn, cat) {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.food-card').forEach(card => {
        const cardCat = card.dataset.category || '';
        const show = cat === 'all' || cardCat === cat;
        card.style.display = show ? '' : 'none';
    });
}

// Add to Cart with animation
function handleAddToCart(btn, menuId, menuName) {
    if (btn.disabled) return;
    btn.disabled = true;
    btn.classList.add('adding');
    btn.textContent = '✓ Ditambahkan!';

    addToCart(menuId, menuName);

    setTimeout(() => {
        btn.disabled = false;
        btn.classList.remove('adding');
        btn.textContent = '🛒 Tambah';
    }, 1800);
}
</script>
@endsection
