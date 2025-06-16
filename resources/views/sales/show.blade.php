@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('sales.index') }}" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-2">
                    <h5 class="mb-0 fw-semibold small"><i class="fas fa-receipt me-1 text-primary"></i> Informasi Penjualan</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Penjualan #{{ $sale->id }}</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="badge bg-primary">{{ $sale->sale_date->format('d F Y') }}</span>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="small">Produk:</strong>
                            <p class="mb-0">
                                <a href="{{ route('products.show', $sale->product) }}" class="text-decoration-none">
                                    {{ $sale->product->name }}
                                </a>
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong class="small">Stok Saat Ini:</strong>
                            <p class="mb-0">
                                @if($sale->product->stock > 10)
                                    <span class="badge bg-success-subtle text-success">{{ $sale->product->stock }} tersedia</span>
                                @elseif($sale->product->stock > 0)
                                    <span class="badge bg-warning-subtle text-warning">{{ $sale->product->stock }} tersisa (Stok Menipis)</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Habis</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong class="small">Jumlah Terjual:</strong>
                            <p class="mb-0">{{ $sale->quantity }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong class="small">Harga Per Item:</strong>
                            <p class="mb-0">Rp {{ number_format($sale->price_per_item, 0, ',', '.') }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong class="small">Total:</strong>
                            <p class="mb-0 fw-bold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="small">Tanggal Penjualan:</strong>
                            <p class="mb-0">{{ $sale->sale_date->format('d F Y') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong class="small">Dicatat Pada:</strong>
                            <p class="mb-0">{{ $sale->created_at->format('d F Y H:i') }}</p>
                        </div>

                        @if($sale->created_at != $sale->updated_at)
                            <div class="col-md-12 mb-0">
                                <strong class="small">Terakhir Diperbarui:</strong>
                                <p class="mb-0">{{ $sale->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white py-2">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Hapus Penjualan
                        </button>

                        <div>
                            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('products.show', $sale->product) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-box me-1"></i> Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data penjualan ini? Stok produk akan dikembalikan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('sales.destroy', $sale) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus Penjualan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 