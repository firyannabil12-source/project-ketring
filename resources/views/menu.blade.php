@extends('layouts.app')

@section('title', 'Menu Kami - Risha Catering')

@section('styles')
    <style>
        .menu-section {
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
            font-size: 1rem;
        }

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

        .cat-btn.active {
            background: #E8572A;
            color: white;
            border-color: #E8572A;
        }

        .cat-btn:hover:not(.active) {
            border-color: #E8572A;
            color: #E8572A;
        }

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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            transition: all 0.3s;
            border: 1px solid #f1f5f9;
            position: relative;
        }

        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }


        .food-img-wrapper {
            position: relative;
            overflow: hidden;
        }

        .food-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.4s;
        }

        .food-card:hover .food-img {
            transform: scale(1.05);
        }



        .food-info {
            padding: 1.25rem;
        }

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

        .food-price small {
            font-size: 0.7rem;
            color: #94a3b8;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }

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

        .btn-add-cart:hover {
            background: #E8572A;
            transform: scale(1.03);
        }

        .btn-add-cart:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-add-cart.adding {
            background: #16a34a;
        }

        .btn-add-cart.adding::after {
            content: '';
        }

        .food-card {
            cursor: pointer;
        }

        .menu-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2500;
            background: rgba(15, 23, 42, 0.52);
            padding: 0.4rem;
        }

        .menu-modal-overlay.open {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-modal {
            position: relative;
            width: min(1140px, 100%);
            max-height: calc(100vh - 0.8rem);
            overflow-y: auto;
            background: white;
            border-radius: 14px;
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.32);
            padding: 2rem;
        }

        .menu-modal-close {
            position: sticky;
            top: 0;
            margin-left: auto;
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 50%;
            background: #a3a3a3;
            color: white;
            font-size: 1.75rem;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .menu-modal-body {
            display: grid;
            grid-template-columns: minmax(280px, 340px) minmax(0, 1fr);
            gap: 3.75rem;
            margin-top: -0.25rem;
        }

        .menu-modal-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }

        .menu-modal-note {
            margin-top: 1rem;
            font-size: 0.72rem;
            line-height: 1.55;
            color: #3f3f46;
        }

        .menu-modal-note strong {
            display: block;
            color: #18181b;
        }

        .menu-modal-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            line-height: 1.15;
            color: #2f612d;
            margin-bottom: 0.25rem;
        }

        .menu-modal-price {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #44403c;
            margin-bottom: 1.75rem;
        }

        .menu-modal-desc {
            color: #3f3f46;
            line-height: 1.55;
            margin-bottom: 1.6rem;
        }

        .menu-modal-category {
            color: #52525b;
            margin-bottom: 1.5rem;
        }

        .menu-order-grid {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            gap: 1.5rem 3rem;
            align-items: center;
        }

        .menu-order-grid label,
        .menu-order-grid .menu-form-label {
            color: #52525b;
            font-weight: 500;
        }

        .required-dot {
            color: #ef4444;
        }

        .menu-modal-select,
        .menu-modal-qty,
        .menu-modal-note-input {
            width: 100%;
            border: 1px solid #d9dde3;
            border-radius: 7px;
            background: #f8f8f9;
            color: #52525b;
            padding: 0.65rem 0.8rem;
            font-family: 'Inter', sans-serif;
        }

        .menu-qty-row {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .menu-modal-qty {
            max-width: 102px;
        }

        .menu-unit {
            color: #52525b;
        }

        .menu-modal-subtotal {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #57534e;
        }

        .menu-modal-note-input {
            min-height: 86px;
            resize: vertical;
            background: white;
        }

        .menu-modal-submit {
            grid-column: 2;
            border: none;
            border-radius: 14px;
            padding: 1rem 1.5rem;
            background: #32652d;
            color: white;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
        }

        .menu-modal-submit:hover {
            background: #285322;
        }

        .menu-modal-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        @media (max-width: 820px) {
            .menu-modal {
                padding: 1rem;
            }

            .menu-modal-body {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .menu-modal-image {
                max-height: 360px;
            }

            .menu-order-grid {
                grid-template-columns: 1fr;
                gap: 0.7rem;
            }

            .menu-modal-submit {
                grid-column: 1;
            }
        }

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
                <h1>Daftar Menu Kami</h1>
                <p>Pilih menu katering terbaik untuk acara istimewa Anda - segar, higienis, dan penuh cinta.</p>
            </div>

            <!-- Category Filters -->
            <div class="category-bar" id="categoryBar">
                <button class="cat-btn active" onclick="filterCat(this, 'all')">Semua</button>
                @php
                    $categories = $menus->pluck('category')->unique()->sort();
                @endphp
                @foreach ($categories as $cat)
                    <button class="cat-btn"
                        onclick="filterCat(this, '{{ $cat }}')">{{ ucwords(strtolower($cat)) }}</button>
                @endforeach
            </div>

            <!-- Menu Grid -->
            <div class="menu-grid" id="menuGrid">
                @forelse($menus as $menu)
                    <div class="food-card" data-category="{{ $menu->category }}" data-menu-id="{{ $menu->id }}"
                        data-price="{{ (float) $menu->price }}" data-stock="{{ $menu->stock }}"
                        id="menu-card-{{ $menu->id }}" onclick="openMenuDetail(this)">

                        <div class="food-img-wrapper">
                            <img src="{{ Str::startsWith($menu->image, 'http') ? $menu->image : asset($menu->image) }}"
                                alt="{{ $menu->name }}" class="food-img"
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
                                <button class="btn-add-cart" id="cart-btn-{{ $menu->id }}" @disabled($menu->stock <= 0)
                                    onclick="event.stopPropagation(); handleAddToCart(this, {{ $menu->id }}, '{{ addslashes($menu->name) }}')">
                                    @if ($menu->stock > 0)
                                        <i data-lucide="shopping-cart"></i>
                                        <span>Tambah</span>
                                    @else
                                        <i data-lucide="circle-alert"></i>
                                        <span>Stok Habis</span>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-category">

                        <p>Belum ada menu tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <div class="menu-modal-overlay" id="menuDetailOverlay" onclick="closeMenuDetail(event)">
                <div class="menu-modal" role="dialog" aria-modal="true" aria-labelledby="menuModalTitle"
                    onclick="event.stopPropagation()">
                    <button type="button" class="menu-modal-close" onclick="closeMenuDetail()"
                        aria-label="Tutup">&times;</button>
                    <div class="menu-modal-body">
                        <div>
                            <img src="" alt="" class="menu-modal-image" id="menuModalImage">
                            <p class="menu-modal-note"><strong>Informasi :</strong>Foto hanya untuk ilustrasi. Produk asli
                                bisa berbeda dan tidak 100% sama.</p>
                        </div>
                        <div>
                            <h2 class="menu-modal-title" id="menuModalTitle"></h2>
                            <div class="menu-modal-price" id="menuModalPrice"></div>
                            <p class="menu-modal-desc" id="menuModalDesc"></p>
                            <p class="menu-modal-category" id="menuModalCategory"></p>
                            <div class="menu-order-grid">
                                <label for="menuDrink">Minuman : <span class="required-dot">*</span></label>
                                <select class="menu-modal-select" id="menuDrink">
                                    <option>Pilih Minuman :</option>
                                    <option>Air Mineral</option>
                                    <option>Teh Kotak</option>
                                    <option>Jus Buah</option>
                                </select>
                                <label for="menuFruit">Buah : <span class="required-dot">*</span></label>
                                <select class="menu-modal-select" id="menuFruit">
                                    <option>Pilih Buah :</option>
                                    <option>Pisang</option>
                                    <option>Jeruk</option>
                                    <option>Semangka</option>
                                </select>
                                <label for="menuPudding">Puding : <span class="required-dot">*</span></label>
                                <select class="menu-modal-select" id="menuPudding">
                                    <option>Pilih Puding :</option>
                                    <option>Cokelat</option>
                                    <option>Vanila</option>
                                    <option>Buah</option>
                                </select>
                                <label for="menuPackaging">Packaging : <span class="required-dot">*</span></label>
                                <select class="menu-modal-select" id="menuPackaging">
                                    <option>Pilih Packaging :</option>
                                    <option>Kotak</option>
                                    <option>Mika</option>
                                    <option>Besek</option>
                                </select>
                                <label for="menuQty">Jumlah Pesanan <small>(min. 10 porsi)</small></label>
                                <div class="menu-qty-row">
                                    <input type="number" min="10" value="10" class="menu-modal-qty"
                                        id="menuQty" oninput="updateMenuSubtotal()">
                                    <span class="menu-unit">Kotak</span>
                                </div>
                                <div class="menu-form-label">Subtotal Pesanan</div>
                                <div class="menu-modal-subtotal" id="menuModalSubtotal"></div>
                                <div></div>
                                <textarea class="menu-modal-note-input" id="menuOrderNote" placeholder="catatan untuk pesan ini"></textarea>
                                <button type="button" class="menu-modal-submit" id="menuModalSubmit"
                                    onclick="addMenuDetailToCart()">
                                    <span style="font-size:1.55rem;">&#128722;</span>
                                    <span>Tambahkan Pesanan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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
            btn.innerHTML = '<i data-lucide="check"></i><span>Ditambahkan!</span>';
            if (window.lucide) lucide.createIcons();

            addToCart(menuId, menuName);

            setTimeout(() => {
                btn.disabled = false;
                btn.classList.remove('adding');
                btn.innerHTML = '<i data-lucide="shopping-cart"></i><span>Tambah</span>';
                if (window.lucide) lucide.createIcons();
            }, 1800);
        }

        let activeMenuDetail = null;

        function formatRupiah(value, withPrefix = true) {
            const formatted = Number(value || 0).toLocaleString('id-ID');
            return withPrefix ? `Rp ${formatted}` : formatted;
        }

        function openMenuDetail(card) {
            const image = card.querySelector('.food-img');
            activeMenuDetail = {
                id: Number(card.dataset.menuId),
                price: Number(card.dataset.price),
                stock: Number(card.dataset.stock),
                name: card.querySelector('.food-name')?.textContent.trim() || '',
                desc: card.querySelector('.food-desc')?.textContent.trim() || '',
                category: card.querySelector('.food-category')?.textContent.trim() || '',
                image: image?.src || '',
                alt: image?.alt || ''
            };

            document.getElementById('menuModalImage').src = activeMenuDetail.image;
            document.getElementById('menuModalImage').alt = activeMenuDetail.alt;
            document.getElementById('menuModalTitle').textContent = activeMenuDetail.name;
            document.getElementById('menuModalPrice').textContent = formatRupiah(activeMenuDetail.price);
            document.getElementById('menuModalDesc').textContent = activeMenuDetail.desc;
            document.getElementById('menuModalCategory').textContent = activeMenuDetail.category;
            document.getElementById('menuQty').value = Math.min(activeMenuDetail.stock || 10, Math.max(10, activeMenuDetail.stock >= 10 ? 10 : activeMenuDetail.stock || 1));
            document.getElementById('menuQty').max = activeMenuDetail.stock || 10;
            document.getElementById('menuOrderNote').value = '';
            updateMenuSubtotal();

            document.getElementById('menuDetailOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMenuDetail(event) {
            if (event && event.target !== document.getElementById('menuDetailOverlay')) return;
            document.getElementById('menuDetailOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        function updateMenuSubtotal() {
            if (!activeMenuDetail) return;
            const qtyInput = document.getElementById('menuQty');
            const maxQty = activeMenuDetail.stock || 10;
            let qty = Math.max(10, Math.min(Number(qtyInput.value || 10), maxQty));
            qtyInput.value = qty;
            document.getElementById('menuModalSubtotal').textContent = formatRupiah(activeMenuDetail.price * qty, false);
        }

        function addMenuDetailToCart() {
            if (!activeMenuDetail) return;
            const btn = document.getElementById('menuModalSubmit');
            const qty = Number(document.getElementById('menuQty').value || 1);
            btn.disabled = true;

            fetch(CART_ROUTES.add, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        menu_id: activeMenuDetail.id,
                        quantity: qty
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        updateCartBadge(data.count);
                        showUserToast(data.message, 'success');
                        loadCart();
                        closeMenuDetail();
                    } else {
                        showUserToast(data.message, 'error');
                    }
                })
                .catch(() => showUserToast('Gagal menambahkan ke keranjang.', 'error'))
                .finally(() => {
                    btn.disabled = false;
                });
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeMenuDetail();
        });
    </script>
@endsection
