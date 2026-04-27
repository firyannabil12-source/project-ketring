@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <h1>Cita Rasa <span class="logo-accent">Rumahan</span>, Kualitas Restoran.</h1>
                <p>Ketring Mama Iksan menghadirkan hidangan lezat dan higienis untuk menyempurnakan setiap momen berharga Anda. Dari Nasi Kotak hingga Tumpeng Mewah.</p>
                <div class="hero-btns">
                    <a href="{{ route('menu') }}" class="btn btn-primary">Lihat Menu Kami</a>
                    <a href="{{ route('contact') }}" class="btn" style="color: var(--secondary); margin-left: 1rem; border: 1px solid var(--secondary);">Hubungi Kami</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/hero.png') }}" alt="Catering Spread">
                <div class="floating-card floating-card-1">
                    <span style="font-size: 1.5rem;">⭐</span>
                    <div>
                        <p style="font-weight: 700; margin-bottom: 0;">4.9/5 Rating</p>
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0;">Dari 500+ Pelanggan</p>
                    </div>
                </div>
                <div class="floating-card floating-card-2">
                    <span style="font-size: 1.5rem;">🥘</span>
                    <div>
                        <p style="font-weight: 700; margin-bottom: 0;">Menu Variatif</p>
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0;">Selalu Fresh Setiap Hari</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section" style="background-color: white;">
    <div class="container">
        <div class="section-title">
            <h2>Kenapa Memilih Kami?</h2>
            <p>Kami menjamin kualitas terbaik untuk setiap hidangan yang kami sajikan.</p>
        </div>
        <div class="menu-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🥬</div>
                <h3>Bahan Segar</h3>
                <p>Kami hanya menggunakan bahan baku segar dan berkualitas tinggi setiap harinya.</p>
            </div>
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👨‍🍳</div>
                <h3>Koki Berpengalaman</h3>
                <p>Dimasak oleh tenaga ahli dengan resep rahasia keluarga yang autentik.</p>
            </div>
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🧼</div>
                <h3>Higienis & Halal</h3>
                <p>Proses memasak yang bersih dan terjamin kehalalannya untuk ketenangan Anda.</p>
            </div>
        </div>
    </div>
</section>


@endsection
