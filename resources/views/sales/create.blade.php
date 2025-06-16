@extends('layouts.app')

@section('title', 'Catat Penjualan')

@section('header-buttons')
    <a href="{{ route('sales.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <h5 class="card-title mb-0 fw-semibold small">
                <i class="fas fa-plus-circle me-1 text-primary"></i>Tambah Data Penjualan
            </h5>
        </div>
        <div class="card-body p-3">
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="product_id" class="form-label fw-medium small">Produk <span class="text-danger">*</span></label>
                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->stock }} tersedia)
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small">Pilih produk yang akan dijual</div>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-medium small">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required>
                            <div class="form-text small">Maksimal tersedia: <span id="max-stock">0</span></div>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="sale_date" class="form-label fw-medium small">Tanggal Penjualan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('sale_date') is-invalid @enderror" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
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
                
                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan Penjualan
                    </button>
                </div>
            </form>
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