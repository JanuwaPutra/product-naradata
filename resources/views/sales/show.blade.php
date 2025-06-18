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
                            <span class="badge bg-primary">{{ $sale->transaction_date->format('d F Y') }}</span>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <strong class="small">Kasir:</strong>
                            <p class="mb-0">{{ $sale->cashier_name }}</p>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong class="small">Pelanggan:</strong>
                            <p class="mb-0">{{ $sale->customer_name }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->saleDetails as $detail)
                                <tr>
                                    <td>
                                        <a href="{{ route('products.show', $detail->product) }}" class="text-decoration-none">
                                            {{ $detail->product->name }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong class="small">Tanggal Penjualan:</strong>
                            <p class="mb-0">{{ $sale->transaction_date->format('d F Y') }}</p>
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
                            @if($sale->saleDetails->isNotEmpty())
                            <a href="{{ route('products.show', $sale->saleDetails->first()->product) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-box me-1"></i> Lihat Produk
                            </a>
                            @endif
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