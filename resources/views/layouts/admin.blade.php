<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard — Ketring Mama Iksan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f0f2f5; color: #1e293b; }

        /* ── Sidebar ── */
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
        .sidebar-link .icon { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-link:hover { background: rgba(255,255,255,0.07); color: white; }
        .sidebar-link.active { background: rgba(232,87,42,0.2); color: #f97316; }
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
        .btn-logout:hover { background: rgba(239,68,68,0.2); color: #f87171; }

        /* ── Main Content ── */
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

        /* ── Toast ── */
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
    </style>
    @yield('styles')
</head>
<body>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="sidebar-brand">
            <h2><span>Ketring</span> Mama Iksan</h2>
            <small>Panel Administrasi</small>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>

            <div class="nav-section-label">Manajemen</div>
            <a href="{{ route('admin.stok') }}" class="sidebar-link {{ request()->routeIs('admin.stok') || request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                <span class="icon">📋</span> Daftar Menu
            </a>
            <a href="{{ route('admin.pesanan') }}" class="sidebar-link {{ request()->routeIs('admin.pesanan') ? 'active' : '' }}">
                <span class="icon">🛒</span> Pesanan Masuk
                <span class="badge-pending" id="pendingBadge">0</span>
            </a>

            <div class="nav-section-label">Lainnya</div>
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <span class="icon">🌐</span> Lihat Website
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="admin-user-info">
                <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-logout">🚪 Keluar</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-topbar">
            <div>
                <h1>@yield('page-title', 'Dashboard')</h1>
                <span class="breadcrumb">Admin / @yield('breadcrumb', 'Overview')</span>
            </div>
            <div style="font-size: 0.8rem; color: #94a3b8;">
                {{ now()->translatedFormat('l, d F Y') }} — {{ now()->format('H:i') }}
            </div>
        </div>

        @if(session('success'))
        <div id="sessionToast" style="display:none">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <script>
        // Toast notification system
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type === 'error' ? 'error' : ''}`;
            toast.innerHTML = `<span>${type === 'success' ? '✅' : '❌'}</span><span class="toast-text">${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // Show session toast
        const sessionToastEl = document.getElementById('sessionToast');
        if (sessionToastEl) showToast(sessionToastEl.textContent.trim());

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
    </script>
    @yield('scripts')
</body>
</html>
