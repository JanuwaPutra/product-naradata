@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('header-buttons')
    <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Catat Penjualan
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <h5 class="card-title mb-0 fw-semibold small">
                <i class="fas fa-shopping-cart me-1 text-primary"></i> Data Penjualan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle border-bottom mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th>Harga Satuan</th>
                            <th class="text-end">Total</th>
                            <th width="100" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td class="text-center">{{ $sale->id }}</td>
                                <td>{{ $sale->sale_date->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('products.show', $sale->product) }}" class="text-decoration-none fw-medium">
                                        {{ $sale->product->name }}
                                    </a>
                                </td>
                                <td class="text-center">{{ $sale->quantity }}</td>
                                <td>{{ number_format($sale->price_per_item, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $sale->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $sale->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title small">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0 small">Apakah Anda yakin ingin menghapus data penjualan ini? Stok produk akan dikembalikan.</p>
                                                    </div>
                                                    <div class="modal-footer py-1">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST">
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
                                <td colspan="7" class="text-center py-4">
                                    <div class="py-3">
                                        <i class="fas fa-shopping-cart fa-3x text-secondary mb-3"></i>
                                        <h6 class="fw-semibold">Belum Ada Penjualan</h6>
                                        <p class="text-muted small">Mulai dengan mencatat penjualan pertama Anda</p>
                                        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i> Catat Penjualan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sales->isNotEmpty())
        <div class="card-footer bg-white py-2">
            <div class="d-flex justify-content-center">
                {{ $sales->links() }}
            </div>
        </div>
        @endif
    </div>
@endsection 