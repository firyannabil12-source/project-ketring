@extends('layouts.admin')

@section('page-title', $menu->exists ? 'Edit Menu' : 'Tambah Menu Baru')
@section('breadcrumb', 'Kelola Stok')

@section('styles')
<style>
    .form-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        max-width: 720px;
    }
    .form-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .form-card-header h2 { font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 700; color: #0f172a; margin: 0; }
    .form-card-body { padding: 2rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label { font-size: 0.8rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; }
    .form-group input, .form-group select, .form-group textarea {
        padding: 0.75rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        color: #0f172a;
        outline: none;
        background: #f8fafc;
        transition: all 0.2s;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: #E8572A;
        box-shadow: 0 0 0 3px rgba(232,87,42,0.1);
        background: white;
    }
    .form-group textarea { resize: vertical; min-height: 90px; }
    .field-error { font-size: 0.78rem; color: #dc2626; margin-top: 2px; }

    .image-preview-box {
        border: 2px dashed #e2e8f0;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        background: #f8fafc;
    }
    .image-preview-box img { max-height: 120px; border-radius: 8px; object-fit: cover; margin-bottom: 0.5rem; }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        padding: 1.25rem 2rem;
        border-top: 1px solid #f1f5f9;
    }
    .btn-cancel {
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-cancel:hover { background: #e2e8f0; }
    .btn-save {
        padding: 0.7rem 1.75rem;
        border-radius: 10px;
        background: #E8572A;
        color: white;
        font-weight: 700;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-save:hover { background: #d14a20; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(232,87,42,0.35); }

    .category-options { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.25rem; }
    .cat-chip {
        padding: 4px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
        background: white;
        color: #64748b;
    }
    .cat-chip:hover, .cat-chip.selected { background: #E8572A; color: white; border-color: #E8572A; }
</style>
@endsection

@section('content')
<div class="form-card">
    <div class="form-card-header">
        <h2>{{ isset($menu) && $menu->exists ? '✏️ Edit Menu: ' . $menu->name : '➕ Tambah Menu Baru' }}</h2>
    </div>

    <form method="POST" action="{{ isset($menu) && $menu->exists ? route('admin.menu.update', $menu) : route('admin.menu.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($menu) && $menu->exists)
        @method('PUT')
        @endif

        <div class="form-card-body">
            <div class="form-grid">

                <!-- Nama Menu -->
                <div class="form-group">
                    <label>Nama Menu *</label>
                    <input type="text" name="name" value="{{ old('name', $menu->name ?? '') }}" placeholder="cth: Paket Nasi Kotak A" required>
                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <!-- Harga -->
                <div class="form-group">
                    <label>Harga per Porsi (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price', $menu->price ?? '') }}" placeholder="25000" min="0" required>
                    @error('price')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label>Stok Tersedia *</label>
                    <input type="number" name="stock" value="{{ old('stock', $menu->stock ?? 0) }}" placeholder="100" min="0" required>
                    @error('stock')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label>Kategori *</label>
                    <input type="text" name="category" id="categoryInput" value="{{ old('category', $menu->category ?? '') }}" placeholder="cth: NASI KOTAK" required>
                    <div class="category-options">
                        @foreach(['NASI KOTAK', 'TUMPENG', 'SNACK BOX', 'PRASMANAN', 'MINUMAN'] as $cat)
                        <span class="cat-chip {{ old('category', $menu->category ?? '') === $cat ? 'selected' : '' }}"
                              onclick="setCategory('{{ $cat }}')">{{ ucwords(strtolower($cat)) }}</span>
                        @endforeach
                    </div>
                    @error('category')<p class="field-error">{{ $message }}</p>@enderror
                </div>



                <!-- Deskripsi -->
                <div class="form-group full">
                    <label>Deskripsi Menu</label>
                    <textarea name="description" placeholder="Deskripsi singkat isi menu...">{{ old('description', $menu->description ?? '') }}</textarea>
                    @error('description')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <!-- Upload Gambar -->
                <div class="form-group full">
                    <label>Upload Gambar (.png)</label>
                    <input type="file" name="image" id="imageInput" accept=".png, image/png" onchange="previewImage()">
                    @error('image')<p class="field-error">{{ $message }}</p>@enderror
                    
                    @if(isset($menu) && $menu->exists && $menu->image)
                        <div style="font-size: 0.8rem; margin-top: 0.5rem; color: #64748b;">
                            Gambar saat ini: <a href="{{ Str::startsWith($menu->image, 'http') ? $menu->image : asset($menu->image) }}" target="_blank">Lihat</a>
                        </div>
                    @endif

                    <div class="image-preview-box" id="previewBox" style="display:none; margin-top: 1rem;">
                        <img id="imgPreview" src="" alt="Preview" style="max-width: 100%; height: auto;">
                        <div style="font-size: 0.78rem; color: #94a3b8;">Preview gambar</div>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.stok') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-save">
                {{ isset($menu) && $menu->exists ? '💾 Simpan Perubahan' : '➕ Tambah Menu' }}
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function setCategory(cat) {
    document.getElementById('categoryInput').value = cat;
    document.querySelectorAll('.cat-chip').forEach(c => {
        c.classList.toggle('selected', c.textContent.trim().toUpperCase().replace(/ /g, ' ') === cat);
    });
}
function previewImage() {
    const input = document.getElementById('imageInput');
    const box = document.getElementById('previewBox');
    const img = document.getElementById('imgPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            box.style.display = '';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        box.style.display = 'none';
        img.src = '';
    }
}
</script>
@endsection
