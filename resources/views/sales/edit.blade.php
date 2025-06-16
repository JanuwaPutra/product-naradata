@extends('layouts.app')

@section('title', 'Edit Penjualan')

@section('header-buttons')
    <a href="{{ route('sales.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <h5 class="card-title mb-0 fw-semibold small">
                <i class="fas fa-edit me-1 text-primary"></i>Edit Data Penjualan
            </h5>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('sales.update', $sale) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="product_id" class="form-label fw-medium small">Produk <span class="text-danger">*</span></label>
                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    @php
                                        // If this is the current product, use its stock + the sale quantity
                                        // Otherwise, just use its stock
                                        $availableStock = $product->id == $sale->product_id ? 
                                            $product->stock + $sale->quantity : 
                                            $product->stock;
                                    @endphp
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->price }}" 
                                            data-stock="{{ $availableStock }}" 
                                            {{ old('product_id', $sale->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $availableStock }} tersedia)
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-medium small">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $sale->quantity) }}" required>
                            <div class="form-text small">Maksimal tersedia: <span id="max-stock">0</span></div>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="sale_date" class="form-label fw-medium small">Tanggal Penjualan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('sale_date') is-invalid @enderror" id="sale_date" name="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" required>
                            @error('sale_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-header small fw-medium">Ringkasan Penjualan</div>
                            <div class="card-body p-3">
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label small">Produk:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext small" id="summary-product">-</p>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label small">Harga Per Item:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext small" id="summary-price">Rp 0</p>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label small">Jumlah:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext small" id="summary-quantity">0</p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="mb-0 row">
                                    <label class="col-sm-4 col-form-label fw-bold small">Total:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext fw-bold" id="summary-total">Rp 0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">
                
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Hapus Penjualan
                    </button>

                    <div>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-info text-white btn-sm me-2">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
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

@section('scripts')
<script>
    // Format number to Indonesian Rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }
    
    // Calculate and update summary
    function updateSummary() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        
        if (productSelect.selectedIndex > 0) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productName = selectedOption.text.split(' (')[0];
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            const stock = parseInt(selectedOption.getAttribute('data-stock'));
            const quantity = parseInt(quantityInput.value) || 0;
            
            // Update max stock info
            document.getElementById('max-stock').textContent = stock;
            
            // Prevent quantity from exceeding stock
            if (quantity > stock) {
                quantityInput.value = stock;
                updateSummary();
                return;
            }
            
            // Update summary
            document.getElementById('summary-product').textContent = productName;
            document.getElementById('summary-price').textContent = formatRupiah(price);
            document.getElementById('summary-quantity').textContent = quantity;
            document.getElementById('summary-total').textContent = formatRupiah(price * quantity);
        } else {
            // Clear summary if no product selected
            document.getElementById('max-stock').textContent = '0';
            document.getElementById('summary-product').textContent = '-';
            document.getElementById('summary-price').textContent = formatRupiah(0);
            document.getElementById('summary-quantity').textContent = '0';
            document.getElementById('summary-total').textContent = formatRupiah(0);
        }
    }
    
    // Event listeners
    document.getElementById('product_id').addEventListener('change', updateSummary);
    document.getElementById('quantity').addEventListener('input', updateSummary);
    document.addEventListener('DOMContentLoaded', updateSummary);
</script>
@endsection 