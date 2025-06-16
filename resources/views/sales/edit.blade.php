@extends('layouts.app')

@section('title', 'Edit Sale')

@section('header-buttons')
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Sales
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.update', $sale) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                <option value="">-- Select Product --</option>
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
                                        {{ $product->name }} ({{ $availableStock }} available)
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $sale->quantity) }}" required>
                            <div class="form-text">Max available: <span id="max-stock">0</span></div>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="sale_date" class="form-label">Sale Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('sale_date') is-invalid @enderror" id="sale_date" name="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" required>
                            @error('sale_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-header">Sale Summary</div>
                            <div class="card-body">
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Product:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext" id="summary-product">-</p>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Price Per Item:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext" id="summary-price">$0.00</p>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label">Quantity:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext" id="summary-quantity">0</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label fw-bold">Total:</label>
                                    <div class="col-sm-8">
                                        <p class="form-control-plaintext fw-bold fs-5" id="summary-total">$0.00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="d-flex justify-content-between">
                    <form action="{{ route('sales.destroy', $sale) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this sale record? This will restore the stock to the product.');"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Sale
                        </button>
                    </form>

                    <div>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-info text-white me-2">View Details</a>
                        <button type="submit" class="btn btn-primary">Update Sale</button>
                    </div>
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
            
            // Update summary
            document.getElementById('summary-product').textContent = productName;
            document.getElementById('summary-price').textContent = '$' + price.toFixed(2);
            document.getElementById('summary-quantity').textContent = quantity;
            document.getElementById('summary-total').textContent = '$' + (price * quantity).toFixed(2);
        } else {
            // Clear summary if no product selected
            document.getElementById('max-stock').textContent = '0';
            document.getElementById('summary-product').textContent = '-';
            document.getElementById('summary-price').textContent = '$0.00';
            document.getElementById('summary-quantity').textContent = '0';
            document.getElementById('summary-total').textContent = '$0.00';
        }
    }
    
    // Event listeners
    document.getElementById('product_id').addEventListener('change', updateSummary);
    document.getElementById('quantity').addEventListener('input', updateSummary);
    document.addEventListener('DOMContentLoaded', updateSummary);
</script>
@endsection 