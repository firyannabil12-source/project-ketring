<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Ketring Mama Iksan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #E8572A;
            --secondary: #1e293b;
            --dark: #0f172a;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--dark);
            overflow: hidden;
        }

        /* Left — branding panel */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(232,87,42,0.15) 0%, transparent 70%);
            top: -100px; left: -100px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(232,87,42,0.1) 0%, transparent 70%);
            bottom: -80px; right: -80px;
        }
        .brand-logo {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            position: relative; z-index: 1;
        }
        .brand-logo span { color: var(--primary); }
        .brand-tagline {
            color: rgba(255,255,255,0.5);
            font-size: 1rem;
            text-align: center;
            max-width: 300px;
            line-height: 1.6;
            position: relative; z-index: 1;
        }
        .brand-badge {
            margin-top: 3rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 1.5rem 2rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.875rem;
            position: relative; z-index: 1;
            text-align: center;
        }
        .brand-badge strong { color: white; display: block; margin-bottom: 4px; font-size: 1rem; }

        /* Right — login form */
        .right-panel {
            width: 480px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
        }
        .login-header { margin-bottom: 2.5rem; }
        .login-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        .login-header p { color: #64748b; font-size: 0.9rem; }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            background: #f8fafc;
        }
        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(232,87,42,0.1);
            background: white;
        }
        .form-group input.is-invalid { border-color: #ef4444; }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            color: #dc2626;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            color: #16a34a;
            font-size: 0.875rem;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }
        .btn-login:hover {
            background: #d14a20;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(232,87,42,0.35);
        }
        .btn-login:active { transform: translateY(0); }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.875rem;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--primary); }

        .field-error {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.375rem;
        }

        @media (max-width: 768px) {
            body { flex-direction: column; overflow: auto; }
            .left-panel { padding: 2rem; min-height: 200px; flex: none; }
            .brand-logo { font-size: 2rem; }
            .brand-badge { display: none; }
            .right-panel { width: 100%; padding: 2rem; }
        }
    </style>
</head>
<body>

    <!-- Left Branding Panel -->
    <div class="left-panel">
        <div class="brand-logo"><span>Ketring</span> Mama Iksan</div>
        <p class="brand-tagline">Panel Admin — Kelola menu, stok, dan pesanan katering Anda dengan mudah.</p>
        <div class="brand-badge">
            <strong>🔒 Area Terbatas</strong>
            Halaman ini hanya untuk administrator yang berwenang.
        </div>
    </div>

    <!-- Right Login Form -->
    <div class="right-panel">
        <div class="login-header">
            <h1>Selamat Datang 👋</h1>
            <p>Masuk ke dashboard admin Ketring Mama Iksan</p>
        </div>

        @if(session('error'))
        <div class="error-message">⚠️ {{ session('error') }}</div>
        @endif

        @if(session('success'))
        <div class="success-message">✅ {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@ketring.com"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    autocomplete="email"
                    autofocus
                >
                @error('email')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                    autocomplete="current-password"
                >
                @error('password')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-login">Masuk ke Dashboard →</button>
        </form>

        <a href="{{ route('home') }}" class="back-link">← Kembali ke Website Utama</a>
    </div>

</body>
</html>
