<!DOCTYPE html>
<html lang="id">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>@yield('title', 'Risha Catering - Katering Lezat & Higienis')</title>
 <meta name="description" content="Risha Catering menyediakan layanan katering terbaik dengan cita rasa rumahan yang lezat, sehat, dan higienis untuk berbagai acara Anda.">

 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="{{ asset('css/style.css') }}">
 <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
 <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
 <script src="https://unpkg.com/lucide@latest"></script>

 <style>
 /* Cart Button in Navbar */
 .cart-btn {
 position: relative;
 background: transparent;
 border: 2px solid var(--primary, #E8572A);
 color: var(--primary, #E8572A);
 border-radius: 8px;
 padding: 0.45rem 0.875rem;
 font-size: 0.9rem;
 cursor: pointer;
 transition: all 0.2s;
 display: flex;
 align-items: center;
 gap: 0.375rem;
 font-family: 'Inter', sans-serif;
 font-weight: 600;
 }
 .cart-btn:hover { background: var(--primary, #E8572A); color: white; transform: translateY(-1px); }
 .cart-btn svg,
 .nav-links svg,
 .mobile-nav-links svg,
 .footer svg,
 .btn svg {
 width: 18px;
 height: 18px;
 stroke-width: 2.2;
 flex-shrink: 0;
 transition: transform 0.2s ease, color 0.2s ease;
 }
 .nav-links a,
 .mobile-nav-links a,
 .footer-links a,
 .footer-contact p {
 display: inline-flex;
 align-items: center;
 gap: 0.45rem;
 }
 .nav-links a:hover svg,
 .nav-links a.active svg,
 .mobile-nav-links a:hover svg,
 .mobile-nav-links a.active svg,
 .footer-links a:hover svg {
 transform: translateY(-1px) scale(1.08);
 }
 .cart-count {
 background: var(--primary, #E8572A);
 color: white;
 font-size: 0.68rem;
 font-weight: 800;
 width: 18px; height: 18px;
 border-radius: 50%;
 display: flex;
 align-items: center;
 justify-content: center;
 position: absolute;
 top: -7px; right: -7px;
 transition: transform 0.2s;
 }
 .cart-btn:hover .cart-count { background: white; color: var(--primary, #E8572A); }
 .cart-count.bounce { animation: bounce 0.4s ease; }
 @keyframes bounce { 0%,100% { transform: scale(1); } 50% { transform: scale(1.35); } }

 /* Cart Drawer */
 .cart-overlay {
 position: fixed;
 inset: 0;
 background: rgba(0,0,0,0.5);
 z-index: 1000;
 opacity: 0;
 pointer-events: none;
 transition: opacity 0.3s;
 backdrop-filter: blur(2px);
 }
 .cart-overlay.open { opacity: 1; pointer-events: all; }

 .cart-drawer {
 position: fixed;
 top: 0; right: 0;
 width: 420px;
 max-width: 95vw;
 height: 100vh;
 background: white;
 z-index: 1001;
 box-shadow: -8px 0 40px rgba(0,0,0,0.15);
 display: flex;
 flex-direction: column;
 transform: translateX(100%);
 transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
 }
 .cart-drawer.open { transform: translateX(0); }

 .cart-drawer-header {
 padding: 1.5rem;
 border-bottom: 1px solid #f1f5f9;
 display: flex;
 justify-content: space-between;
 align-items: center;
 flex-shrink: 0;
 }
 .cart-drawer-header h3 {
 font-family: 'Outfit', sans-serif;
 font-size: 1.1rem;
 font-weight: 700;
 color: #0f172a;
 margin: 0;
 }
 .cart-close {
 background: #f1f5f9;
 border: none;
 width: 32px; height: 32px;
 border-radius: 8px;
 cursor: pointer;
 font-size: 1rem;
 display: flex;
 align-items: center;
 justify-content: center;
 transition: all 0.15s;
 }
 .cart-close:hover { background: #e2e8f0; transform: rotate(90deg); }

 .cart-items-list {
 flex: 1;
 overflow-y: auto;
 padding: 1rem 1.5rem;
 }

 .cart-empty {
 display: flex;
 flex-direction: column;
 align-items: center;
 justify-content: center;
 height: 100%;
 color: #94a3b8;
 text-align: center;
 gap: 0.75rem;
 }
 .cart-empty .empty-icon { width: 52px; height: 52px; color: #cbd5e1; }
 .cart-empty p { font-size: 0.9rem; }
 .cart-empty a {
 color: #E8572A;
 font-weight: 600;
 text-decoration: none;
 font-size: 0.875rem;
 }

 .cart-item {
 display: flex;
 gap: 0.875rem;
 padding: 0.875rem 0;
 border-bottom: 1px solid #f1f5f9;
 align-items: center;
 }
 .cart-item:last-child { border-bottom: none; }
 .cart-item-img {
 width: 52px; height: 52px;
 border-radius: 10px;
 object-fit: cover;
 flex-shrink: 0;
 background: #f1f5f9;
 }
 .cart-item-info { flex: 1; min-width: 0; }
 .cart-item-name { font-weight: 700; font-size: 0.875rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
 .cart-item-price { font-size: 0.8rem; color: #64748b; margin-top: 2px; }
 .cart-item-subtotal { font-weight: 700; font-size: 0.875rem; color: #E8572A; }

 .cart-qty-ctrl {
 display: flex;
 align-items: center;
 gap: 0.375rem;
 margin-top: 0.375rem;
 }
 .qty-btn {
 width: 26px; height: 26px;
 border: 1.5px solid #e2e8f0;
 background: white;
 border-radius: 6px;
 font-size: 0.9rem;
 font-weight: 700;
 cursor: pointer;
 display: flex;
 align-items: center;
 justify-content: center;
 transition: all 0.15s;
 line-height: 1;
 }
 .qty-btn:hover { background: #E8572A; color: white; border-color: #E8572A; }
 .qty-display {
 min-width: 28px;
 text-align: center;
 font-weight: 700;
 font-size: 0.875rem;
 color: #0f172a;
 }

 .cart-item-remove {
 background: none;
 border: none;
 color: #cbd5e1;
 cursor: pointer;
 font-size: 1.1rem;
 padding: 4px;
 border-radius: 6px;
 transition: all 0.15s;
 flex-shrink: 0;
 }
 .cart-item-remove:hover { color: #ef4444; background: #fee2e2; transform: scale(1.05); }

 .cart-drawer-footer {
 padding: 1.25rem 1.5rem;
 border-top: 1px solid #f1f5f9;
 flex-shrink: 0;
 }
 .cart-total-row {
 display: flex;
 justify-content: space-between;
 align-items: center;
 margin-bottom: 1rem;
 }
 .cart-total-label { font-size: 0.875rem; color: #64748b; font-weight: 600; }
 .cart-total-value { font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 700; color: #0f172a; }
 .btn-checkout {
 display: block;
 width: 100%;
 padding: 0.875rem;
 background: linear-gradient(135deg, #E8572A, #f97316);
 color: white;
 border: none;
 border-radius: 12px;
 font-size: 0.95rem;
 font-weight: 700;
 cursor: pointer;
 text-align: center;
 text-decoration: none;
 transition: all 0.2s;
 font-family: 'Inter', sans-serif;
 }
 .btn-checkout:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(232,87,42,0.4); }
 .btn-clear-cart {
 display: block;
 width: 100%;
 padding: 0.6rem;
 background: transparent;
 color: #94a3b8;
 border: none;
 font-size: 0.78rem;
 cursor: pointer;
 text-align: center;
 margin-top: 0.5rem;
 transition: color 0.15s;
 }
 .btn-clear-cart:hover { color: #ef4444; }

 /* Toast */
 .user-toast-container {
 position: fixed;
 bottom: 1.5rem;
 right: 1.5rem;
 z-index: 9999;
 display: flex;
 flex-direction: column;
 gap: 0.5rem;
 }
 .user-toast {
 background: #0f172a;
 color: white;
 border-radius: 12px;
 padding: 0.875rem 1.25rem;
 box-shadow: 0 8px 30px rgba(0,0,0,0.2);
 display: flex;
 align-items: center;
 gap: 0.625rem;
 min-width: 260px;
 max-width: 350px;
 animation: toastIn 0.3s ease;
 font-size: 0.875rem;
 font-weight: 500;
 }
 .user-toast.success .toast-icon { color: #4ade80; }
 .user-toast.error { background: #7f1d1d; }
 .user-toast.error .toast-icon { color: #fca5a5; }
 @keyframes toastIn {
 from { transform: translateY(20px); opacity: 0; }
 to { transform: translateY(0); opacity: 1; }
 }

 /* Mobile Menu Toggle CSS */
 .mobile-menu-toggle {
 display: none;
 flex-direction: column;
 justify-content: space-between;
 width: 26px;
 height: 18px;
 background: transparent;
 border: none;
 cursor: pointer;
 padding: 0;
 z-index: 1002;
 transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
 }
 .mobile-menu-toggle .bar {
 width: 100%;
 height: 3px;
 background-color: var(--secondary, #2C3E50);
 border-radius: 4px;
 transition: all 0.3s ease;
 }
 @media (max-width: 768px) {
 .mobile-menu-toggle {
 display: flex;
 }
 }
 .mobile-menu-toggle.open .bar:nth-child(1) {
 transform: translateY(7.5px) rotate(45deg);
 background-color: var(--primary, #E67E22);
 }
 .mobile-menu-toggle.open .bar:nth-child(2) {
 opacity: 0;
 }
 .mobile-menu-toggle.open .bar:nth-child(3) {
 transform: translateY(-7.5px) rotate(-45deg);
 background-color: var(--primary, #E67E22);
 }

 /* Mobile Navigation Drawer CSS */
 .mobile-nav-overlay {
 position: fixed;
 inset: 0;
 background: rgba(15, 23, 42, 0.3);
 z-index: 1000;
 opacity: 0;
 pointer-events: none;
 transition: opacity 0.35s ease;
 backdrop-filter: blur(4px);
 }
 .mobile-nav-overlay.open {
 opacity: 1;
 pointer-events: all;
 }
 .mobile-nav-drawer {
 position: fixed;
 top: 0;
 left: 0;
 width: 300px;
 max-width: 85vw;
 height: 100vh;
 background: rgba(255, 255, 255, 0.95);
 backdrop-filter: blur(20px);
 z-index: 1001;
 box-shadow: 8px 0 45px rgba(15, 23, 42, 0.08);
 display: flex;
 flex-direction: column;
 transform: translateX(-100%);
 transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
 }
 .mobile-nav-drawer.open {
 transform: translateX(0);
 }
 .mobile-nav-header {
 padding: 1.5rem;
 border-bottom: 1px solid rgba(15, 23, 42, 0.06);
 display: flex;
 justify-content: space-between;
 align-items: center;
 flex-shrink: 0;
 }
 .mobile-nav-close {
 background: #f1f5f9;
 border: none;
 width: 32px; height: 32px;
 border-radius: 8px;
 cursor: pointer;
 font-size: 1.25rem;
 display: flex;
 align-items: center;
 justify-content: center;
 color: var(--secondary, #2C3E50);
 transition: all 0.2s;
 }
 .mobile-nav-close:hover {
 background: #e2e8f0;
 }
 .mobile-nav-links {
 display: flex;
 flex-direction: column;
 padding: 1.5rem;
 gap: 1.25rem;
 flex: 1;
 overflow-y: auto;
 }
 .mobile-nav-links a {
 text-decoration: none;
 color: var(--secondary, #2C3E50);
 font-weight: 600;
 font-size: 1.05rem;
 transition: all 0.25s ease;
 display: block;
 padding: 0.5rem 0.75rem;
 border-radius: 8px;
 }
 .mobile-nav-links a:hover,
 .mobile-nav-links a.active {
 color: var(--primary, #E67E22);
 background: rgba(230, 126, 34, 0.05);
 padding-left: 1.25rem;
 }
 .mobile-nav-divider {
 height: 1px;
 background-color: rgba(15, 23, 42, 0.06);
 margin: 0.5rem 0;
 }
 .btn-mobile-login {
 border: 2px solid var(--primary, #E67E22);
 color: var(--primary, #E67E22) !important;
 text-align: center;
 border-radius: 8px;
 padding: 0.65rem !important;
 margin-top: 0.5rem;
 font-weight: 700 !important;
 }
 .btn-mobile-login:hover {
 background: var(--primary, #E67E22);
 color: white !important;
 }
 .btn-mobile-logout {
 border: 2px solid #ef4444;
 color: #ef4444 !important;
 text-align: center;
 border-radius: 8px;
 padding: 0.65rem !important;
 margin-top: 0.5rem;
 font-weight: 700 !important;
 }
 .btn-mobile-logout:hover {
 background: #ef4444;
 color: white !important;
 }
 </style>

 @yield('styles')
</head>
<body>
 <!-- Toast Container -->
 <div class="user-toast-container" id="userToastContainer"></div>

 <!-- Cart Overlay -->
 <div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>

 <!-- Cart Drawer -->
 <div class="cart-drawer" id="cartDrawer">
 <div class="cart-drawer-header">
 <h3>Keranjang Belanja</h3>
 <button class="cart-close" onclick="closeCart()" aria-label="Tutup keranjang"><i data-lucide="x"></i></button>
 </div>
 <div class="cart-items-list" id="cartItemsList">
 <div class="cart-empty">
 <i class="empty-icon" data-lucide="shopping-bag"></i>
 <p>Keranjang Anda kosong.<br>Tambah menu untuk mulai memesan.</p>
 <a href="{{ route('menu') }}" onclick="closeCart()">Lihat Menu</a>
 </div>
 </div>
 <div class="cart-drawer-footer" id="cartFooter" style="display:none">
 <div class="cart-total-row">
 <span class="cart-total-label">Total</span>
 <span class="cart-total-value" id="cartTotalDisplay">Rp 0</span>
 </div>
 <a href="{{ route('orders') }}" class="btn-checkout" onclick="closeCart()">
 Lanjut ke Pemesanan
 </a>
 <button class="btn-clear-cart" onclick="clearCart()">Kosongkan Keranjang</button>
 </div>
 </div>

 <!-- Navbar -->
 <header class="navbar">
 <div class="container">
 <a href="{{ route('home') }}" class="logo">
 <span class="logo-accent">Risha</span> Catering
 </a>
 <nav class="nav-links">
 <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i data-lucide="home"></i>Beranda</a>
 <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}"><i data-lucide="utensils"></i>Menu</a>
 <a href="{{ route('orders') }}" class="{{ request()->routeIs('orders') ? 'active' : '' }}"><i data-lucide="clipboard-list"></i>Pesanan</a>
 <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i data-lucide="message-circle"></i>Kontak</a>
 
 @auth
 @if(Auth::user()->role === 'admin')
 <a href="{{ route('admin.dashboard') }}" style="color: var(--primary);">Admin Panel</a>
 @endif
 <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ef4444;">Logout</a>
 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
 @csrf
 </form>
 @else
 <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
 @endauth
 </nav>
 <div style="display: flex; align-items: center; gap: 0.75rem;">
 <button class="cart-btn" onclick="openCart()" id="cartNavBtn">
 <i data-lucide="shopping-cart"></i>Keranjang
 <span class="cart-count" id="cartNavCount" style="display:none">0</span>
 </button>
 <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle Menu" id="mobileMenuToggle">
 <span class="bar"></span>
 <span class="bar"></span>
 <span class="bar"></span>
 </button>
 </div>
 </div>
 </header>

 <!-- Mobile Navigation Drawer -->
 <div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="closeMobileMenu()"></div>
 <div class="mobile-nav-drawer" id="mobileNavDrawer">
 <div class="mobile-nav-header">
 <a href="{{ route('home') }}" class="logo" onclick="closeMobileMenu()">
 <span class="logo-accent">Risha</span> Catering
 </a>
 <button class="mobile-nav-close" onclick="closeMobileMenu()" aria-label="Tutup menu"><i data-lucide="x"></i></button>
 </div>
 <nav class="mobile-nav-links">
 <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}" onclick="closeMobileMenu()"><i data-lucide="home"></i>Beranda</a>
 <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') ? 'active' : '' }}" onclick="closeMobileMenu()"><i data-lucide="utensils"></i>Menu</a>
 <a href="{{ route('orders') }}" class="{{ request()->routeIs('orders') ? 'active' : '' }}" onclick="closeMobileMenu()"><i data-lucide="clipboard-list"></i>Pesanan</a>
 <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}" onclick="closeMobileMenu()"><i data-lucide="message-circle"></i>Kontak</a>
 <div class="mobile-nav-divider"></div>
 @auth
 @if(Auth::user()->role === 'admin')
 <a href="{{ route('admin.dashboard') }}" style="color: var(--primary);" onclick="closeMobileMenu()">Admin Panel</a>
 @endif
 <a href="{{ route('logout') }}" class="btn-mobile-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
 @else
 <a href="{{ route('login') }}" class="btn-mobile-login {{ request()->routeIs('login') ? 'active' : '' }}" onclick="closeMobileMenu()">Login</a>
 @endauth
 </nav>
 </div>

 <main>
 @if(session('success'))
 <div id="sessionSuccessMsg" style="display:none">{{ session('success') }}</div>
 @endif
 @if(session('error'))
 <div id="sessionErrorMsg" style="display:none">{{ session('error') }}</div>
 @endif
 @yield('content')
 </main>

 <footer class="footer">
 <div class="container">
 <div class="footer-grid">
 <div class="footer-info">
 <h3>Risha Catering</h3>
 <p>Lezat, Higienis, dan Penuh Cinta. Teman setia untuk setiap momen istimewa Anda.</p>
 </div>
 <div class="footer-links">
 <h4>Tautan</h4>
 <a href="{{ route('home') }}"><i data-lucide="home"></i>Beranda</a>
 <a href="{{ route('menu') }}"><i data-lucide="utensils"></i>Menu</a>
 <a href="{{ route('orders') }}"><i data-lucide="clipboard-list"></i>Pesanan</a>
 <a href="{{ route('contact') }}"><i data-lucide="message-circle"></i>Kontak</a>
 </div>
 <div class="footer-contact">
 <h4>Hubungi Kami</h4>
 <p><i data-lucide="map-pin"></i>Perumahan Mutiara Sawangan No.10 Blok A3, Pengasinan, Sawangan, Depok City, West Java 16518, Indonesia</p>
 <p><i data-lucide="phone"></i>0895404575481</p>
 <p><i data-lucide="mail"></i>info@rishacatering.com</p>
 </div>
 </div>
 <div class="footer-bottom">
 <p>&copy; {{ date('Y') }} Risha Catering. All rights reserved.</p>
 </div>
 </div>
 </footer>

 <script src="{{ asset('js/main.js') }}"></script>
 <script>
 const CART_ROUTES = {
 get: '{{ route("cart.get") }}',
 add: '{{ route("cart.add") }}',
 remove: '{{ route("cart.remove") }}',
 update: '{{ route("cart.update") }}',
 };
 const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

 // Toast 
 function showUserToast(message, type = 'success') {
 const container = document.getElementById('userToastContainer');
 const toast = document.createElement('div');
 toast.className = `user-toast ${type}`;
 toast.innerHTML = `<i class="toast-icon" data-lucide="${type === 'success' ? 'check-circle-2' : 'circle-alert'}"></i><span>${message}</span>`;
 container.appendChild(toast);
 if (window.lucide) lucide.createIcons();
 setTimeout(() => {
 toast.style.opacity = '0';
 toast.style.transform = 'translateY(10px)';
 toast.style.transition = 'all 0.3s';
 setTimeout(() => toast.remove(), 300);
 }, 3000);
 }

 // Session Toasts 
 document.addEventListener('DOMContentLoaded', () => {
 const successMsg = document.getElementById('sessionSuccessMsg');
 const errorMsg = document.getElementById('sessionErrorMsg');
 if (successMsg) showUserToast(successMsg.textContent.trim(), 'success');
 if (errorMsg) showUserToast(errorMsg.textContent.trim(), 'error');
 loadCart();
 });

 // Cart State 
 let cartData = { cart: [], count: 0, total: 0 };

 function loadCart() {
 fetch(CART_ROUTES.get)
 .then(r => r.json())
 .then(data => {
 cartData = data;
 renderCart();
 updateCartBadge(data.count);
 if (typeof renderCheckoutCart === 'function') {
 renderCheckoutCart(data);
 }
 });
 }

 function updateCartBadge(count) {
 const badge = document.getElementById('cartNavCount');
 if (count > 0) {
 badge.textContent = count > 99 ? '99+' : count;
 badge.style.display = 'flex';
 badge.classList.add('bounce');
 setTimeout(() => badge.classList.remove('bounce'), 400);
 } else {
 badge.style.display = 'none';
 }
 }

 function renderCart() {
 const list = document.getElementById('cartItemsList');
 const footer = document.getElementById('cartFooter');
 const total = document.getElementById('cartTotalDisplay');

 if (!cartData.cart || cartData.cart.length === 0) {
 list.innerHTML = `
 <div class="cart-empty">
 <i class="empty-icon" data-lucide="shopping-bag"></i>
 <p>Keranjang Anda kosong.<br>Tambah menu untuk mulai memesan.</p>
 <a href="{{ route('menu') }}" onclick="closeCart()">Lihat Menu</a>
 </div>`;
 footer.style.display = 'none';
 if (window.lucide) lucide.createIcons();
 return;
 }

 list.innerHTML = cartData.cart.map(item => {
 const imgSrc = item.image && item.image.startsWith('http')
 ? item.image
 : '/' + (item.image || 'images/placeholder.png');
 return `
 <div class="cart-item">
 <img src="${imgSrc}" alt="${item.name}" class="cart-item-img"
 onerror="this.src='https://via.placeholder.com/52x52/E8572A/white?text=M'">
 <div class="cart-item-info">
 <div class="cart-item-name">${item.name}</div>
 <div class="cart-item-price">Rp ${Number(item.price).toLocaleString('id-ID')} / porsi</div>
 <div class="cart-qty-ctrl">
 <button class="qty-btn" onclick="updateQty(${item.menu_id}, ${item.quantity - 1})" aria-label="Kurangi jumlah"><i data-lucide="minus"></i></button>
 <span class="qty-display">${item.quantity}</span>
 <button class="qty-btn" onclick="updateQty(${item.menu_id}, ${item.quantity + 1})" aria-label="Tambah jumlah"><i data-lucide="plus"></i></button>
 </div>
 </div>
 <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.5rem;">
 <div class="cart-item-subtotal">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</div>
 <button class="cart-item-remove" onclick="removeFromCart(${item.menu_id})" title="Hapus" aria-label="Hapus item"><i data-lucide="trash-2"></i></button>
 </div>
 </div>`;
 }).join('');

 total.textContent = 'Rp ' + Number(cartData.total).toLocaleString('id-ID');
 footer.style.display = 'block';
 if (window.lucide) lucide.createIcons();
 }

 // Cart Actions 
 function addToCart(menuId, menuName) {
 fetch(CART_ROUTES.add, {
 method: 'POST',
 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
 body: JSON.stringify({ menu_id: menuId, quantity: 1 })
 })
 .then(r => r.json())
 .then(data => {
 if (data.success) {
 cartData.count = data.count;
 cartData.total = data.total;
 updateCartBadge(data.count);
 showUserToast(data.message, 'success');
 loadCart(); // reload to get full cart data
 } else {
 showUserToast(data.message, 'error');
 }
 })
 .catch(() => showUserToast('Gagal menambahkan ke keranjang.', 'error'));
 }

 function removeFromCart(menuId) {
 fetch(CART_ROUTES.remove, {
 method: 'POST',
 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
 body: JSON.stringify({ menu_id: menuId })
 })
 .then(r => r.json())
 .then(data => {
 if (data.success) {
 updateCartBadge(data.count);
 loadCart();
 }
 });
 }

 function updateQty(menuId, newQty) {
 if (newQty < 0) return;
 fetch(CART_ROUTES.update, {
 method: 'POST',
 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
 body: JSON.stringify({ menu_id: menuId, quantity: newQty })
 })
 .then(r => r.json())
 .then(data => {
 if (data.success) {
 updateCartBadge(data.count);
 loadCart();
 } else {
 showUserToast(data.message, 'error');
 }
 });
 }

 function clearCart() {
 if (!confirm('Kosongkan semua item dari keranjang?')) return;
 const promises = (cartData.cart || []).map(item =>
 fetch(CART_ROUTES.remove, {
 method: 'POST',
 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
 body: JSON.stringify({ menu_id: item.menu_id })
 })
 );
 Promise.all(promises).then(() => loadCart()).then(() => updateCartBadge(0));
 }

 // Cart Drawer Toggle 
 function openCart() {
 document.getElementById('cartDrawer').classList.add('open');
 document.getElementById('cartOverlay').classList.add('open');
 document.body.style.overflow = 'hidden';
 loadCart();
 }
 function closeCart() {
 document.getElementById('cartDrawer').classList.remove('open');
 document.getElementById('cartOverlay').classList.remove('open');
 document.body.style.overflow = '';
 }
 document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeCart(); closeMobileMenu(); } });
 document.addEventListener('DOMContentLoaded', () => {
 if (window.lucide) lucide.createIcons();
 });

 // Mobile Menu Drawer Toggle
 function toggleMobileMenu() {
 const toggleBtn = document.getElementById('mobileMenuToggle');
 const drawer = document.getElementById('mobileNavDrawer');
 const overlay = document.getElementById('mobileNavOverlay');
 const isOpen = drawer.classList.toggle('open');
 toggleBtn.classList.toggle('open', isOpen);
 overlay.classList.toggle('open', isOpen);
 document.body.style.overflow = isOpen ? 'hidden' : '';
 }
 function closeMobileMenu() {
 const toggleBtn = document.getElementById('mobileMenuToggle');
 const drawer = document.getElementById('mobileNavDrawer');
 const overlay = document.getElementById('mobileNavOverlay');
 if (toggleBtn) toggleBtn.classList.remove('open');
 if (drawer) drawer.classList.remove('open');
 if (overlay) overlay.classList.remove('open');
 document.body.style.overflow = '';
 }
 </script>
 <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
 <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
 @yield('scripts')
</body>
</html>
