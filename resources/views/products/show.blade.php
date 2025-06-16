@extends('layouts.app')

@section('title', 'Detail Produk')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-2">
                    <h5 class="mb-0 fw-semibold small">Informasi Produk</h5>
                </div>
                <div class="card-body p-3">
                    <h4 class="mb-3 fw-bold">{{ $product->name }}</h4>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-primary bg-opacity-10 rounded-3">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="fas fa-tag text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-0 small">Harga</h6>
                                            <h5 class="mb-0 fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 border-0 rounded-3 
                                @if($product->stock > 10) bg-success bg-opacity-10
                                @elseif($product->stock > 0) bg-warning bg-opacity-10
                                @else bg-danger bg-opacity-10 @endif">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box rounded-circle 
                                            @if($product->stock > 10) bg-success bg-opacity-10
                                            @elseif($product->stock > 0) bg-warning bg-opacity-10
                                            @else bg-danger bg-opacity-10 @endif p-2 me-2">
                                            <i class="fas fa-cubes 
                                                @if($product->stock > 10) text-success
                                                @elseif($product->stock > 0) text-warning
                                                @else text-danger @endif"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-0 small">Status Stok</h6>
                                            @if($product->stock > 10)
                                                <h5 class="mb-0 fw-bold text-success">{{ $product->stock }} tersedia</h5>
                                            @elseif($product->stock > 0)
                                                <h5 class="mb-0 fw-bold text-warning">{{ $product->stock }} tersisa</h5>
                                            @else
                                                <h5 class="mb-0 fw-bold text-danger">Habis</h5>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border bg-light bg-opacity-50 mb-3">
                        <div class="card-body p-3">
                            <h6 class="fw-semibold mb-2 small">Deskripsi Produk</h6>
                            <p class="mb-0 small">{{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}</p>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar-plus text-primary me-2 small"></i>
                                        <h6 class="mb-0 fw-semibold small">Dibuat Pada</h6>
                                    </div>
                                    <p class="mb-0 small">{{ $product->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-clock text-primary me-2 small"></i>
                                        <h6 class="mb-0 fw-semibold small">Terakhir Diperbarui</h6>
                                    </div>
                                    <p class="mb-0 small">{{ $product->updated_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold small">Riwayat Penjualan</h5>
                    <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary btn-xs">
                        <i class="fas fa-plus me-1"></i> Catat Penjualan
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($product->sales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kuantitas</th>
                                        <th>Harga</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->sales->sortByDesc('created_at')->take(5) as $sale)
                                        <tr>
                                            <td>{{ $sale->sale_date->format('d M Y') }}</td>
                                            <td>{{ $sale->quantity }}</td>
                                            <td>Rp {{ number_format($sale->price_per_item, 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-2 border-top text-center">
                            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary btn-xs">
                                <i class="fas fa-list me-1"></i> Lihat semua riwayat penjualan
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-shopping-cart fa-2x text-secondary mb-2"></i>
                            <h6 class="fw-bold">Belum Ada Penjualan</h6>
                            <p class="text-muted mb-3 small">Belum ada catatan penjualan untuk produk ini</p>
                            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm px-3">
                                <i class="fas fa-plus me-1"></i> Catat Penjualan Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="fas fa-trash me-1"></i> Hapus Produk
        </button>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('products.destroy', $product) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus Produk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .btn-xs {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
    </style>
@endsection 