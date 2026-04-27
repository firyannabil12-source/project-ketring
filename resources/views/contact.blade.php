@extends('layouts.app')

@section('content')
<section class="section" style="padding-top: 4rem;">
    <div class="container">
        <div class="hero-grid">
            <div class="contact-info">
                <h1 style="margin-bottom: 1.5rem;">Hubungi Kami</h1>
                <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 1.125rem;">Punya pertanyaan atau ingin melakukan pemesanan khusus? Tim kami siap membantu Anda.</p>
                
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">📍</div>
                        <p><strong>Alamat:</strong> Jl. Mawar No. 123, Jakarta Selatan</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">📞</div>
                        <p><strong>WhatsApp:</strong> +62 812-3456-789</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">📧</div>
                        <p><strong>Email:</strong> halo@ketringmamaiksan.com</p>
                    </div>
                </div>

                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md);">
                    <h3 style="margin-bottom: 1rem;">Jam Operasional</h3>
                    <p>Senin - Jumat: 08:00 - 17:00</p>
                    <p>Sabtu - Minggu: 08:00 - 15:00</p>
                </div>
            </div>

            <div class="contact-form" style="background: white; padding: 2.5rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
                <h3 style="margin-bottom: 1.5rem;">Kirim Pesan</h3>
                <form action="#">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Lengkap</label>
                        <input type="text" placeholder="Masukkan nama Anda" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: var(--radius-md);">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nomor WhatsApp</label>
                        <input type="tel" placeholder="0812xxxxxx" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: var(--radius-md);">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Pesan</label>
                        <textarea rows="4" placeholder="Tuliskan detail pesanan atau pertanyaan Anda" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: var(--radius-md);"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Kirim Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
