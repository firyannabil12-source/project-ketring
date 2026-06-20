<!DOCTYPE html>
<html lang="id">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>Admin Dashboard - Risha Catering</title>
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="{{ asset('css/style.css') }}">
 <script src="https://unpkg.com/lucide@latest"></script>
 <style>
 *, *::before, *::after { box-sizing: border-box; }
 body { margin: 0; font-family: 'Inter', sans-serif; background: #f0f2f5; color: #1e293b; }

 /* Sidebar */
 .admin-sidebar {
 width: 260px;
 background: #0f172a;
 height: 100vh;
 position: fixed;
 left: 0; top: 0;
 color: white;
 display: flex;
 flex-direction: column;
 padding: 0;
 z-index: 100;
 overflow-y: auto;
 }
 .sidebar-brand {
 padding: 1.75rem 1.5rem 1.5rem;
 border-bottom: 1px solid rgba(255,255,255,0.06);
 }
 .sidebar-brand h2 {
 font-family: 'Outfit', sans-serif;
 font-size: 1.2rem;
 font-weight: 700;
 color: white;
 margin: 0 0 4px;
 }
 .sidebar-brand h2 span { color: #E8572A; }
 .sidebar-brand small { color: rgba(255,255,255,0.35); font-size: 0.75rem; }
 .sidebar-nav { flex: 1; padding: 1rem 0.75rem; }
 .nav-section-label {
 font-size: 0.65rem;
 font-weight: 700;
 letter-spacing: 0.1em;
 text-transform: uppercase;
 color: rgba(255,255,255,0.25);
 padding: 0 0.75rem;
 margin: 1rem 0 0.5rem;
 }
 .sidebar-link {
 display: flex;
 align-items: center;
 gap: 0.75rem;
 color: rgba(255,255,255,0.6);
 text-decoration: none;
 padding: 0.7rem 0.75rem;
 border-radius: 8px;
 margin-bottom: 2px;
 font-size: 0.875rem;
 font-weight: 500;
 transition: all 0.2s;
 position: relative;
 }
 .sidebar-link .icon {
 width: 32px;
 height: 32px;
 border-radius: 8px;
 display: inline-flex;
 align-items: center;
 justify-content: center;
 background: rgba(255,255,255,0.05);
 color: rgba(255,255,255,0.72);
 transition: all 0.2s;
 flex-shrink: 0;
 }
 .sidebar-link .icon svg {
 width: 17px;
 height: 17px;
 stroke-width: 2.1;
 }
 .sidebar-link:hover { background: rgba(255,255,255,0.07); color: white; transform: translateX(2px); }
 .sidebar-link:hover .icon {
 background: rgba(232,87,42,0.18);
 color: #fb923c;
 }
 .sidebar-link.active { background: rgba(232,87,42,0.2); color: #f97316; }
 .sidebar-link.active .icon {
 background: #E8572A;
 color: white;
 box-shadow: 0 8px 18px rgba(232,87,42,0.24);
 }
 .sidebar-link.active::before {
 content: '';
 position: absolute;
 left: 0; top: 50%;
 transform: translateY(-50%);
 width: 3px; height: 60%;
 background: #E8572A;
 border-radius: 0 3px 3px 0;
 }
 .badge-pending {
 margin-left: auto;
 background: #E8572A;
 color: white;
 font-size: 0.7rem;
 font-weight: 700;
 padding: 2px 7px;
 border-radius: 20px;
 display: none;
 }
 .sidebar-bottom {
 padding: 1rem 0.75rem;
 border-top: 1px solid rgba(255,255,255,0.06);
 }
 .admin-user-info {
 display: flex;
 align-items: center;
 gap: 0.75rem;
 padding: 0.75rem;
 margin-bottom: 0.5rem;
 }
 .admin-avatar {
 width: 36px; height: 36px;
 background: linear-gradient(135deg, #E8572A, #f97316);
 border-radius: 50%;
 display: flex;
 align-items: center;
 justify-content: center;
 font-weight: 700;
 font-size: 0.9rem;
 color: white;
 flex-shrink: 0;
 }
 .admin-user-info .user-name { font-size: 0.8rem; font-weight: 600; color: white; }
 .admin-user-info .user-role { font-size: 0.7rem; color: rgba(255,255,255,0.35); }
 .btn-logout {
 display: flex;
 align-items: center;
 gap: 0.5rem;
 width: 100%;
 padding: 0.65rem 0.75rem;
 background: rgba(239,68,68,0.1);
 border: none;
 border-radius: 8px;
 color: #fca5a5;
 font-size: 0.8rem;
 font-weight: 600;
 cursor: pointer;
 text-decoration: none;
 transition: all 0.2s;
 }
 .btn-logout svg,
 .admin-topbar svg,
 .toast svg {
 width: 18px;
 height: 18px;
 stroke-width: 2.2;
 flex-shrink: 0;
 }
 .btn-logout:hover { background: rgba(239,68,68,0.2); color: #f87171; transform: translateY(-1px); }

 /* Main Content */
 .admin-main {
 margin-left: 260px;
 padding: 2rem;
 min-height: 100vh;
 }
 .admin-topbar {
 background: white;
 border-radius: 12px;
 padding: 1rem 1.5rem;
 display: flex;
 justify-content: space-between;
 align-items: center;
 margin-bottom: 2rem;
 box-shadow: 0 1px 3px rgba(0,0,0,0.06);
 }
 .admin-topbar h1 { font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 700; color: #0f172a; margin: 0; }
 .admin-topbar .breadcrumb { font-size: 0.8rem; color: #94a3b8; }
 .topbar-title {
 display: flex;
 align-items: center;
 gap: 0.75rem;
 }
 .topbar-icon {
 width: 40px;
 height: 40px;
 border-radius: 10px;
 display: flex;
 align-items: center;
 justify-content: center;
 background: #fff7ed;
 color: #E8572A;
 }
 .topbar-date {
 display: inline-flex;
 align-items: center;
 gap: 0.45rem;
 font-size: 0.8rem;
 color: #94a3b8;
 }

 /* Toast */
 .toast-container {
 position: fixed;
 top: 1.5rem; right: 1.5rem;
 z-index: 9999;
 display: flex;
 flex-direction: column;
 gap: 0.75rem;
 }
 .toast {
 background: white;
 border-radius: 10px;
 padding: 1rem 1.25rem;
 box-shadow: 0 4px 20px rgba(0,0,0,0.12);
 display: flex;
 align-items: center;
 gap: 0.75rem;
 animation: slideIn 0.3s ease;
 min-width: 280px;
 border-left: 4px solid #22c55e;
 }
 .toast.error { border-left-color: #ef4444; }
 .toast-text { font-size: 0.875rem; font-weight: 500; color: #1e293b; }
 @keyframes slideIn {
 from { transform: translateX(100%); opacity: 0; }
 to { transform: translateX(0); opacity: 1; }
 }
 @keyframes slideOut {
 from { transform: translateX(0); opacity: 1; }
 to { transform: translateX(100%); opacity: 0; }
 }

 /* Laravel Pagination CSS Fixes */
 div:has(> nav[role="navigation"]) {
   justify-content: flex-end !important;
 }
 nav[role="navigation"] {
   display: flex !important;
   align-items: center;
   justify-content: space-between !important;
   width: 100% !important;
   margin-top: 1.5rem;
   font-family: 'Inter', sans-serif;
 }
 nav[role="navigation"] svg {
   width: 1rem !important;
   height: 1rem !important;
   display: inline-block;
   vertical-align: middle;
 }
 nav[role="navigation"] .hidden {
   display: none !important;
 }
 @media (min-width: 640px) {
   nav[role="navigation"] .sm\:flex {
     display: flex !important;
   }
   nav[role="navigation"] .sm\:hidden {
     display: none !important;
   }
   nav[role="navigation"] .sm\:flex-1 {
     flex: 1 1 0% !important;
     display: flex !important;
     align-items: center;
     justify-content: space-between;
     width: 100%;
   }
 }
 nav[role="navigation"] .flex-1.flex.justify-between.sm\:hidden {
   display: flex !important;
   justify-content: space-between;
   width: 100%;
 }
 nav[role="navigation"] p.text-sm {
   margin: 0;
   font-size: 0.8rem;
   color: #64748b;
 }
 nav[role="navigation"] p.text-sm span {
   font-weight: 600;
   color: #0f172a;
 }
 nav[role="navigation"] .inline-flex {
   display: inline-flex;
   border-radius: 8px;
   box-shadow: 0 1px 3px rgba(0,0,0,0.05);
   background: white;
   border: 1px solid #e2e8f0;
   overflow: hidden;
 }
 nav[role="navigation"] a,
 nav[role="navigation"] span[aria-current="page"],
 nav[role="navigation"] span[aria-disabled="true"] {
   display: inline-flex;
   align-items: center;
   justify-content: center;
   padding: 0.45rem 0.75rem;
   font-size: 0.8rem;
   font-weight: 600;
   text-decoration: none;
   color: #475569;
   border-right: 1px solid #e2e8f0;
   background: white;
   transition: all 0.15s ease;
 }
 nav[role="navigation"] a:last-child,
 nav[role="navigation"] span:last-child {
   border-right: none;
 }
 nav[role="navigation"] a:hover {
   background: #f8fafc;
   color: #E8572A;
 }
 nav[role="navigation"] span[aria-current="page"] {
   background: #E8572A;
   color: white !important;
   border-color: #E8572A;
   cursor: default;
 }
 nav[role="navigation"] span[aria-disabled="true"] {
   color: #cbd5e1;
   background: #f8fafc;
   cursor: not-allowed;
 }
 </style>
 @yield('styles')
</head>
<body>

 <!-- Toast Container -->
 <div class="toast-container" id="toastContainer"></div>

 <!-- Sidebar -->
 <div class="admin-sidebar">
 <div class="sidebar-brand">
 <h2><span>Risha</span> Catering</h2>
 <small>Panel Administrasi</small>
 </div>

 <nav class="sidebar-nav">
 <div class="nav-section-label">Menu Utama</div>
 <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
 <span class="icon"><i data-lucide="layout-dashboard"></i></span> Dashboard
 </a>

 <div class="nav-section-label">Manajemen</div>
 <a href="{{ route('admin.stok') }}" class="sidebar-link {{ request()->routeIs('admin.stok') || request()->routeIs('admin.menu.*') ? 'active' : '' }}">
 <span class="icon"><i data-lucide="utensils"></i></span> Daftar Menu
 </a>
 <a href="{{ route('admin.pesanan') }}" class="sidebar-link {{ request()->routeIs('admin.pesanan') ? 'active' : '' }}">
 <span class="icon"><i data-lucide="receipt-text"></i></span> Pesanan Masuk
 <span class="badge-pending" id="pendingBadge">0</span>
 </a>
 <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
 <span class="icon"><i data-lucide="users-round"></i></span> Daftar Pengguna
 </a>

 <div class="nav-section-label">Lainnya</div>
 <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
 <span class="icon"><i data-lucide="external-link"></i></span> Lihat Website
 </a>
 </nav>

 <div class="sidebar-bottom">
 <div class="admin-user-info">
 <div class="admin-avatar">{{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}</div>
 <div>
 <div class="user-name">{{ Auth::guard('admin')->user()->name }}</div>
 <div class="user-role">Administrator</div>
 </div>
 </div>
 <form method="POST" action="{{ route('admin.logout') }}">
 @csrf
 <button type="submit" class="btn-logout"><i data-lucide="log-out"></i>Keluar</button>
 </form>
 </div>
 </div>

 <!-- Main Content -->
 <main class="admin-main">
 <div class="admin-topbar">
 <div class="topbar-title">
 <span class="topbar-icon"><i data-lucide="panel-top"></i></span>
 <div>
 <h1>@yield('page-title', 'Dashboard')</h1>
 <span class="breadcrumb">Admin / @yield('breadcrumb', 'Overview')</span>
 </div>
 </div>
 <div class="topbar-date">
 <i data-lucide="calendar-days"></i>
 {{ now()->translatedFormat('l, d F Y') }} - {{ now()->format('H:i') }}
 </div>
 </div>

 @if(session('success'))
 <div id="sessionToast" style="display:none">{{ session('success') }}</div>
 @endif
 @if(session('error'))
 <div id="sessionErrorToast" style="display:none">{{ session('error') }}</div>
 @endif

 @yield('content')
 </main>

 <script>
 // Toast notification system
 function showToast(message, type = 'success') {
 const container = document.getElementById('toastContainer');
 const toast = document.createElement('div');
 toast.className = `toast ${type === 'error' ? 'error' : ''}`;
 toast.innerHTML = `<i data-lucide="${type === 'success' ? 'check-circle-2' : 'circle-alert'}"></i><span class="toast-text">${message}</span>`;
 container.appendChild(toast);
 if (window.lucide) lucide.createIcons();
 setTimeout(() => {
 toast.style.animation = 'slideOut 0.3s ease forwards';
 setTimeout(() => toast.remove(), 300);
 }, 3500);
 }

 // Show session toast
 const sessionToastEl = document.getElementById('sessionToast');
 if (sessionToastEl) showToast(sessionToastEl.textContent.trim());
 const sessionErrorToastEl = document.getElementById('sessionErrorToast');
 if (sessionErrorToastEl) showToast(sessionErrorToastEl.textContent.trim(), 'error');

 // Poll pending orders count for sidebar badge
 function updatePendingBadge() {
 fetch('{{ route("admin.api.pending") }}')
 .then(r => r.json())
 .then(data => {
 const badge = document.getElementById('pendingBadge');
 if (data.pending > 0) {
 badge.textContent = data.pending;
 badge.style.display = 'inline-block';
 } else {
 badge.style.display = 'none';
 }
 }).catch(() => {});
 }
 updatePendingBadge();
 setInterval(updatePendingBadge, 30000);
 document.addEventListener('DOMContentLoaded', () => {
 if (window.lucide) lucide.createIcons();
 });
 </script>
 @yield('scripts')
</body>
</html>
