@extends('layouts.app')

@section('title', 'Kasir')

@section('header-buttons')
<div class="d-flex gap-2">
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pilih Produk</h5>
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" id="productSearch" class="form-control" placeholder="Cari produk...">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="productContainer">
                    @foreach($products as $product)
                    <div class="col-md-4 col-sm-6 mb-3 product-item" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                        <div class="card h-100 product-card">
                            <div class="card-body text-center">
                                <h6 class="card-title">{{ $product->name }}</h6>
                                <p class="card-text text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="card-text small">Stok: {{ $product->stock }}</p>
                                <button type="button" class="btn btn-sm btn-primary add-to-cart w-100">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 80px;">
            <div class="card-header">
                <h5 class="mb-0">Keranjang</h5>
            </div>
            <div class="card-body">
                <form id="saleForm" action="{{ route('cashier.process') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="cashier_name" class="form-label">Nama Kasir <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cashier_name') is-invalid @enderror" id="cashier_name" name="cashier_name" value="{{ old('cashier_name') }}" required>
                        @error('cashier_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}">
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="cart-items mb-3">
                        <div class="alert alert-info text-center" id="emptyCartMessage">
                            Keranjang kosong
                        </div>
                        <div id="cartItemsContainer" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-center" width="40">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartItems">
                                        <!-- Cart items will be added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>Total:</h5>
                        <h5 id="totalAmount">Rp 0</h5>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100" id="processButton" disabled>
                        <i class="fas fa-check-circle me-2"></i>Proses Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cart = [];
        const cartItems = document.getElementById('cartItems');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const cartItemsContainer = document.getElementById('cartItemsContainer');
        const totalAmountElement = document.getElementById('totalAmount');
        const processButton = document.getElementById('processButton');
        const productSearch = document.getElementById('productSearch');
        const productContainer = document.getElementById('productContainer');
        const productItems = document.querySelectorAll('.product-item');
        
        // Add to cart buttons
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productCard = this.closest('.product-item');
                const productId = productCard.dataset.id;
                const productName = productCard.dataset.name;
                const productPrice = parseFloat(productCard.dataset.price);
                const productStock = parseInt(productCard.dataset.stock);
                
                // Check if product is already in cart
                const existingItem = cart.find(item => item.id === productId);
                
                if (existingItem) {
                    // Check if there's enough stock
                    if (existingItem.quantity < productStock) {
                        existingItem.quantity += 1;
                        existingItem.subtotal = existingItem.quantity * productPrice;
                    } else {
                        alert('Stok tidak cukup!');
                        return;
                    }
                } else {
                    // Add new item to cart
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        quantity: 1,
                        subtotal: productPrice
                    });
                }
                
                updateCart();
            });
        });
        
        // Search functionality
        productSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            productItems.forEach(item => {
                const productName = item.dataset.name.toLowerCase();
                if (productName.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Update cart display
        function updateCart() {
            // Clear current items
            cartItems.innerHTML = '';
            
            if (cart.length === 0) {
                emptyCartMessage.classList.remove('d-none');
                cartItemsContainer.classList.add('d-none');
                processButton.disabled = true;
                totalAmountElement.textContent = 'Rp 0';
            } else {
                emptyCartMessage.classList.add('d-none');
                cartItemsContainer.classList.remove('d-none');
                processButton.disabled = false;
                
                // Add cart items to table
                let total = 0;
                
                cart.forEach((item, index) => {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td>
                            ${item.name}
                            <div class="text-muted small">Rp ${numberFormat(item.price)}</div>
                            <input type="hidden" name="products[${index}][id]" value="${item.id}">
                        </td>
                        <td class="text-end">
                            <div class="input-group input-group-sm d-inline-flex" style="width: 100px;">
                                <button type="button" class="btn btn-outline-secondary btn-sm decrease-qty" data-index="${index}">-</button>
                                <input type="number" class="form-control text-center item-qty" name="products[${index}][quantity]" value="${item.quantity}" min="1" data-index="${index}">
                                <button type="button" class="btn btn-outline-secondary btn-sm increase-qty" data-index="${index}">+</button>
                            </div>
                        </td>
                        <td class="text-end">Rp ${numberFormat(item.subtotal)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-index="${index}" style="width: 32px; height: 32px; padding: 4px 0;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    
                    cartItems.appendChild(row);
                    total += item.subtotal;
                });
                
                // Update total
                totalAmountElement.textContent = `Rp ${numberFormat(total)}`;
                
                // Add event listeners to quantity buttons
                addQuantityEventListeners();
            }
        }
        
        // Add event listeners to quantity buttons and inputs
        function addQuantityEventListeners() {
            // Decrease quantity
            document.querySelectorAll('.decrease-qty').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    if (cart[index].quantity > 1) {
                        cart[index].quantity -= 1;
                        cart[index].subtotal = cart[index].quantity * cart[index].price;
                        updateCart();
                    }
                });
            });
            
            // Increase quantity
            document.querySelectorAll('.increase-qty').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    const productId = cart[index].id;
                    const productItem = document.querySelector(`.product-item[data-id="${productId}"]`);
                    const productStock = parseInt(productItem.dataset.stock);
                    
                    if (cart[index].quantity < productStock) {
                        cart[index].quantity += 1;
                        cart[index].subtotal = cart[index].quantity * cart[index].price;
                        updateCart();
                    } else {
                        alert('Stok tidak cukup!');
                    }
                });
            });
            
            // Manual quantity input
            document.querySelectorAll('.item-qty').forEach(input => {
                input.addEventListener('change', function() {
                    const index = parseInt(this.dataset.index);
                    const newQuantity = parseInt(this.value);
                    
                    if (isNaN(newQuantity) || newQuantity < 1) {
                        this.value = 1;
                        cart[index].quantity = 1;
                    } else {
                        const productId = cart[index].id;
                        const productItem = document.querySelector(`.product-item[data-id="${productId}"]`);
                        const productStock = parseInt(productItem.dataset.stock);
                        
                        if (newQuantity > productStock) {
                            this.value = productStock;
                            cart[index].quantity = productStock;
                            alert('Stok tidak cukup!');
                        } else {
                            cart[index].quantity = newQuantity;
                        }
                    }
                    
                    cart[index].subtotal = cart[index].quantity * cart[index].price;
                    updateCart();
                });
            });
            
            // Remove item
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    cart.splice(index, 1);
                    updateCart();
                });
            });
        }
        
        // Format number to currency
        function numberFormat(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });
</script>
@endsection 