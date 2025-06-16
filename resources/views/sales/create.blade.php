@extends('layouts.app')

@section('title', 'Catat Penjualan')

@section('header-buttons')
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-shopping-cart me-2 text-primary"></i>Form Penjualan Produk</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Produk <span class="text-danger">*</span></label>
                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->stock }} tersedia)
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Pilih produk yang akan dijual</div>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required>
                            <div class="form-text">Maksimal: <span id="max-stock" class="fw-medium">0</span> unit</div>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="sale_date" class="form-label">Tanggal Penjualan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('sale_date') is-invalid @enderror" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                            @error('sale_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Ringkasan Penjualan</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Produk:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext fw-medium" id="summary-product">-</p>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Harga Satuan:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext" id="summary-price">Rp 0</p>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Jumlah:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext" id="summary-quantity">0</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-0 row">
                                    <label class="col-sm-4 col-form-label fw-bold">Total:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext fw-bold fs-4 text-primary" id="summary-total">Rp 0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-light me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Penjualan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
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
            
            // Format price to IDR (Indonesian Rupiah)
            function formatRupiah(amount) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            }
            
            // Update summary
            document.getElementById('summary-product').textContent = productName;
            document.getElementById('summary-price').textContent = 'Rp ' + formatRupiah(price);
            document.getElementById('summary-quantity').textContent = quantity;
            document.getElementById('summary-total').textContent = 'Rp ' + formatRupiah(price * quantity);
        } else {
            // Clear summary if no product selected
            document.getElementById('max-stock').textContent = '0';
            document.getElementById('summary-product').textContent = '-';
            document.getElementById('summary-price').textContent = 'Rp 0';
            document.getElementById('summary-quantity').textContent = '0';
            document.getElementById('summary-total').textContent = 'Rp 0';
        }
    }
    
    // Event listeners
    document.getElementById('product_id').addEventListener('change', updateSummary);
    document.getElementById('quantity').addEventListener('input', updateSummary);
    document.addEventListener('DOMContentLoaded', updateSummary);
</script>
@endsection 