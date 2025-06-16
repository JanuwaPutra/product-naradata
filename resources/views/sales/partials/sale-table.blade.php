@forelse($sales as $sale)
    <tr>
        <td>{{ $sale->sale_date->format('d M Y') }}</td>
        <td>
            <a href="{{ route('products.show', $sale->product) }}" class="text-decoration-none fw-medium text-dark">
                {{ $sale->product->name }}
            </a>
        </td>
        <td class="text-center">{{ $sale->quantity }}</td>
        <td>
            <span class="fw-semibold small">Rp {{ number_format($sale->price_per_item, 0, ',', '.') }}</span>
        </td>
        <td class="text-end fw-bold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
        <td>
            <div class="d-flex gap-1 justify-content-center">
                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-primary btn-xs" data-bs-toggle="tooltip" title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('sales.edit', $sale) }}" class="btn btn-sm btn-outline-warning btn-xs" data-bs-toggle="tooltip" title="Edit Transaksi">
                    <i class="fas fa-edit"></i>
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger btn-xs" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $sale->id }}" title="Hapus Transaksi">
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
                                <p class="mb-0 small">Apakah Anda yakin ingin menghapus data transaksi ini? Stok barang akan dikembalikan.</p>
                            </div>
                            <div class="modal-footer py-1">
                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                <form action="{{ secure_url(route('sales.destroy', $sale, false)) }}" method="POST">
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
        <td colspan="6" class="text-center py-4">
            <div class="py-3">
                <i class="fas fa-exchange-alt fa-3x text-secondary mb-3"></i>
                <h6 class="fw-bold">Belum Ada Transaksi</h6>
                <p class="text-muted mb-3 small">Mulai dengan mencatat transaksi pertama Anda</p>
                <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm px-3">
                    <i class="fas fa-plus me-1"></i> Catat Transaksi
                </a>
            </div>
        </td>
    </tr>
@endforelse 