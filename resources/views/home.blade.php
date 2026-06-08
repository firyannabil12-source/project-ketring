@extends('layouts.app')

@section('styles')
    <style>
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }

        .feature-icon {
            width: 74px;
            height: 74px;
            margin: 0 auto 1.25rem;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(230, 126, 34, 0.14), rgba(241, 196, 15, 0.18));
            color: var(--primary);
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon {
            background: var(--primary);
            color: white;
            transform: rotate(-4deg) scale(1.06);
        }

        .feature-icon svg {
            width: 36px;
            height: 36px;
            stroke-width: 2.05;
        }

        .hero-btns .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
        }

        .floating-card-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(230, 126, 34, 0.12);
            color: var(--primary);
        }

        .floating-card-icon svg {
            width: 19px;
            height: 19px;
        }

        @media (max-width: 768px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }

            .feature-card {
                padding: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <h1>Cita Rasa <span class="logo-accent">Rumahan</span>, Kualitas Restoran.</h1>
                    <p>Risha Catering menghadirkan hidangan lezat dan higienis untuk menyempurnakan setiap momen berharga
                        Anda. Dari Nasi Kotak hingga Tumpeng Mewah.</p>
                    <div class="hero-btns">
                        <a href="{{ route('menu') }}" class="btn btn-primary"><i data-lucide="utensils"></i>Lihat Menu Kami</a>
                        <a href="{{ route('contact') }}" class="btn"
                            style="color: var(--secondary); margin-left: 1rem; border: 1px solid var(--secondary);"><i
                                data-lucide="message-circle"></i>Hubungi Kami</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="{{ asset('images/hero.png') }}" alt="Catering Spread">
                    <div class="floating-card floating-card-1">
                        <span class="floating-card-icon"><i data-lucide="star"></i></span>
                        <div>
                            <p style="font-weight: 700; margin-bottom: 0;">4.9/5 Rating</p>
                            <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0;">Dari 500+ Pelanggan
                            </p>
                        </div>
                    </div>
                    <div class="floating-card floating-card-2">
                        <span class="floating-card-icon"><i data-lucide="sparkles"></i></span>
                        <div>
                            <p style="font-weight: 700; margin-bottom: 0;">Menu Variatif</p>
                            <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0;">Selalu Fresh Setiap
                                Hari</p>
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
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i data-lucide="leaf"></i></div>
                    <h3>Bahan Segar</h3>
                    <p>Kami hanya menggunakan bahan baku segar dan berkualitas tinggi setiap harinya.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-lucide="chef-hat"></i></div>
                    <h3>Koki Berpengalaman</h3>
                    <p>Dimasak oleh tenaga ahli dengan resep rahasia keluarga yang autentik.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-lucide="shield-check"></i></div>
                    <h3>Higienis & Halal</h3>
                    <p>Proses memasak yang bersih dan terjamin kehalalannya untuk ketenangan Anda.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
