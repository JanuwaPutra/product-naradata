@extends('layouts.app')

@section('title', 'Edit Barang')

@section('header-buttons')
    <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-edit me-1 text-primary"></i>Edit Barang
            </h5>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="card border bg-light bg-opacity-50 mb-3">
                            <div class="card-body p-3">
                                <h6 class="fw-semibold mb-2 small">Informasi Barang</h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium small">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="Masukkan nama barang" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="description" class="form-label fw-medium small">Deskripsi Barang</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Masukkan deskripsi barang (opsional)">{{ old('description', $product->description) }}</textarea>
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
                                        <input type="number" step="1" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" placeholder="0" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" style="font-size: 0.75rem;">Masukkan harga dalam Rupiah (tanpa titik atau koma)</small>
                                </div>

                                <div class="mb-0">
                                    <label for="stock" class="form-label fw-medium small">Stok <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" placeholder="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-eye me-1"></i> Lihat Detail
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 