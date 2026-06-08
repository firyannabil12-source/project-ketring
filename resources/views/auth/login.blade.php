@extends('layouts.app')

@section('title', 'Login - Risha Catering')

@section('content')
    <div class="container" style="max-width: 500px; margin: 4rem auto;">
        <div
            style="background: white; border-radius: 14px; padding: 2.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);">
            <h2 style="text-align: center; margin-bottom: 2rem; color: #0f172a;">Masuk ke Akun Anda</h2>

            @if ($errors->any())
                <div
                    style="background: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.875rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label for="email"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155; font-size: 0.875rem;">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label for="password"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155; font-size: 0.875rem;">Password</label>
                    <input type="password" id="password" name="password" required
                        style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 1rem; outline: none; transition: border-color 0.2s;">
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width: 100%; justify-content: center; padding: 0.875rem; font-size: 1rem;">Masuk</button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: #64748b;">
                Belum punya akun? <a href="{{ route('register') }}"
                    style="color: #E8572A; font-weight: 600; text-decoration: none;">Daftar di sini</a>
            </div>
        </div>
    </div>
@endsection
