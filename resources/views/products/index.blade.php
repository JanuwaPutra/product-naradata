@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('header-buttons')
    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Tambah Produk
    </a>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6 col-lg-3 mb-2 mb-lg-0">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 rounded p-2 me-2">
                            <i class="fas fa-box text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">Total Produk</h6>
                            <h5 class="fw-bold mb-0">{{ $products->total() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success bg-opacity-10 rounded p-2 me-2">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">Produk Tersedia</h6>
                            <h5 class="fw-bold mb-0">{{ $products->where('stock', '>', 0)->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-semibold">Daftar Produk</h5>
                </div>
                <div class="col-auto">
                    <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari produk..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary btn-sm" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th width="100" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="text-center">{{ $product->id }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none fw-medium text-dark">
                                        {{ $product->name }}
                                    </a>
                                    <div class="small text-muted text-truncate" style="max-width: 200px; font-size: 0.8rem;">
                                        {{ Str::limit($product->description ?? 'Tidak ada deskripsi', 40) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold small">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @if($product->stock > 10)
                                        <span class="badge bg-success-subtle text-success rounded-pill">{{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                        <span class="badge bg-warning-subtle text-warning rounded-pill">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary btn-xs" data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning btn-xs" data-bs-toggle="tooltip" title="Edit Produk">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-xs" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}" title="Hapus Produk">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title small">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0 small">Apakah Anda yakin ingin menghapus produk ini?</p>
                                                    </div>
                                                    <div class="modal-footer py-1">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('products.destroy', $product) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="py-3">
                                        <i class="fas fa-box-open fa-3x text-secondary mb-3"></i>
                                        <h6 class="fw-bold">Tidak Ada Produk</h6>
                                        <p class="text-muted mb-3 small">Belum ada produk yang tersedia saat ini</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm px-3">
                                            <i class="fas fa-plus me-1"></i> Tambah Produk
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-2 border-top">
                <div class="mb-2 mb-md-0">
                    <small class="text-muted" style="font-size: 0.8rem;">Menampilkan {{ $products->firstItem() ?? 0 }} sampai {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} data</small>
                </div>
                <div class="pagination-container">
                    {{ $products->links() }}
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

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection 