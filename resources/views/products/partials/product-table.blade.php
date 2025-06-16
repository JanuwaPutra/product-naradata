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
                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning btn-xs" data-bs-toggle="tooltip" title="Edit Barang">
                    <i class="fas fa-edit"></i>
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger btn-xs" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}" title="Hapus Barang">
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
                                <p class="mb-0 small">Apakah Anda yakin ingin menghapus barang ini?</p>
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
                <i class="fas fa-boxes fa-3x text-secondary mb-3"></i>
                <h6 class="fw-bold">Tidak Ada Barang</h6>
                <p class="text-muted mb-3 small">Belum ada barang yang tersedia di gudang</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm px-3">
                    <i class="fas fa-plus me-1"></i> Tambah Barang
                </a>
            </div>
        </td>
    </tr>
@endforelse 