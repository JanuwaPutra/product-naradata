@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('header-buttons')
    <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-plus-circle me-1 text-primary"></i>Form Tambah Produk
            </h5>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="card border bg-light bg-opacity-50 mb-3">
                            <div class="card-body p-3">
                                <h6 class="fw-semibold mb-2 small">Informasi Produk</h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium small">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama produk" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="description" class="form-label fw-medium small">Deskripsi Produk</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Masukkan deskripsi produk (opsional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border bg-light bg-opacity-50 mb-3">
                            <div class="card-body p-3">
                                <h6 class="fw-semibold mb-2 small">Detail Harga & Stok</h6>
                                
                                <div class="mb-3">
                                    <label for="price" class="form-label fw-medium small">Harga <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">Rp</span>
                                        <input type="number" step="1" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="0" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" style="font-size: 0.75rem;">Masukkan harga dalam Rupiah (tanpa titik atau koma)</small>
                                </div>

                                <div class="mb-0">
                                    <label for="stock" class="form-label fw-medium small">Stok <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" placeholder="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card border bg-light bg-opacity-50">
                            <div class="card-body p-3">
                                <h6 class="fw-semibold mb-2 small">Foto Produk</h6>
                                
                                <div class="mb-2">
                                    <div class="text-center mb-2" id="image-preview-container" style="display: none;">
                                        <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded border" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                    
                                    <div class="text-center mb-2" id="default-preview">
                                        <div class="bg-white border rounded py-3">
                                            <i class="fas fa-image fa-2x text-secondary mb-1"></i>
                                            <p class="text-muted small mb-0">Belum ada foto produk</p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <label for="image" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Pilih Foto
                                        </label>
                                        <input type="file" class="form-control d-none @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    </div>
                                    <div class="form-text text-center" style="font-size: 0.75rem;">Format: JPG, PNG, JPEG (Max: 2MB)</div>
                                    @error('image')
                                        <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="reset" class="btn btn-light btn-sm">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Image preview
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const defaultPreview = document.getElementById('default-preview');
        
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreviewContainer.style.display = 'block';
                    defaultPreview.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreviewContainer.style.display = 'none';
                defaultPreview.style.display = 'block';
            }
        });
    });
</script>
@endsection 