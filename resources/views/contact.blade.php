@extends('layouts.app')

@section('styles')
    <style>
        .contact-list {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.85rem;
            border-radius: var(--radius-md);
            color: inherit;
            text-decoration: none;
            transition: var(--transition);
        }

        .contact-item:hover {
            background: white;
            box-shadow: var(--shadow-md);
            transform: translateX(4px);
        }

        .contact-icon {
            width: 44px;
            height: 44px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .contact-item:hover .contact-icon,
        .contact-form .btn:hover svg {
            transform: scale(1.08) rotate(-6deg);
        }

        .contact-icon svg,
        .form-icon svg,
        .hours-card svg {
            width: 21px;
            height: 21px;
            stroke-width: 2.35;
        }

        .hours-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .hours-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .hours-title {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin-bottom: 1rem;
        }

        .form-field {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-field label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-field input,
        .form-field textarea {
            width: 100%;
            padding: 0.8rem 0.8rem 0.8rem 2.75rem;
            border: 1px solid #ddd;
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        .form-field textarea {
            padding-top: 0.9rem;
        }

        .form-field input:focus,
        .form-field textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(230, 126, 34, 0.12);
        }

        .form-icon {
            position: absolute;
            left: 0.85rem;
            top: 2.65rem;
            color: var(--primary);
            pointer-events: none;
        }

        .contact-form .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
        }

        @media (max-width: 768px) {
            .contact-item {
                text-align: left;
            }
        }
    </style>
@endsection

@section('content')
    <section class="section" style="padding-top: 4rem;">
        <div class="container">
            <div class="hero-grid">
                <div class="contact-info">
                    <h1 style="margin-bottom: 1.5rem;">Hubungi Kami</h1>
                    <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 1.125rem;">Punya pertanyaan atau
                        ingin melakukan pemesanan khusus? Tim kami siap membantu Anda.</p>

                    <div class="contact-list">
                        <a class="contact-item"
                            href="https://www.google.com/maps/search/?api=1&query=Jl.%20Mawar%20No.%20123%2C%20Jakarta%20Selatan"
                            target="_blank" rel="noopener">
                            <span class="contact-icon"><i data-lucide="map-pin"></i></span>
                            <p><strong>Alamat:</strong> Jl. Mawar No. 123, Jakarta Selatan</p>
                        </a>
                        <a class="contact-item" href="https://wa.me/628123456789" target="_blank" rel="noopener">
                            <span class="contact-icon"><i data-lucide="message-circle"></i></span>
                            <p><strong>WhatsApp:</strong> +62 812-3456-789</p>
                        </a>
                        <a class="contact-item" href="mailto:halo@rishacatering.com">
                            <span class="contact-icon"><i data-lucide="mail"></i></span>
                            <p><strong>Email:</strong> halo@rishacatering.com</p>
                        </a>
                    </div>

                    <div class="hours-card">
                        <h3 class="hours-title"><i data-lucide="clock-3"></i>Jam Operasional</h3>
                        <p>Senin - Jumat: 08:00 - 17:00</p>
                        <p>Sabtu - Minggu: 08:00 - 15:00</p>
                    </div>
                </div>

                <div class="contact-form"
                    style="background: white; padding: 2.5rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
                    <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.65rem;"><i
                            data-lucide="send"></i>Kirim Pesan</h3>
                    <form action="#">
                        <div class="form-field">
                            <label>Nama Lengkap</label>
                            <span class="form-icon"><i data-lucide="user-round"></i></span>
                            <input type="text" placeholder="Masukkan nama Anda">
                        </div>
                        <div class="form-field">
                            <label>Nomor WhatsApp</label>
                            <span class="form-icon"><i data-lucide="phone"></i></span>
                            <input type="tel" placeholder="0812xxxxxx">
                        </div>
                        <div class="form-field" style="margin-bottom: 1.5rem;">
                            <label>Pesan</label>
                            <span class="form-icon"><i data-lucide="message-square-text"></i></span>
                            <textarea rows="4" placeholder="Tuliskan detail pesanan atau pertanyaan Anda"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;"><i
                                data-lucide="send-horizontal"></i>Kirim Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
