@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('header-buttons')
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Catat Penjualan
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle border-bottom">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th>Harga Satuan</th>
                            <th class="text-end">Total</th>
                            <th width="120" class="text-center">Aksi</th>
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
                                <td>Rp {{ number_format($sale->price_per_item, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sales.destroy', $sale) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan penjualan ini? Stok produk akan dikembalikan.');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-shopping-cart fa-3x text-secondary mb-3"></i>
                                        <h5>Belum Ada Penjualan</h5>
                                        <p class="text-muted">Mulai dengan mencatat penjualan pertama Anda</p>
                                        <a href="{{ route('sales.create') }}" class="btn btn-primary mt-2">
                                            Catat Penjualan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($sales->isNotEmpty())
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <div class="mt-3">
                                    {{ $sales->links() }}
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection 