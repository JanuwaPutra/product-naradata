@extends('layouts.app')

@section('title', 'Sale Details')

@section('header-buttons')
    <div>
        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Sales
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Sale Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>Sale #{{ $sale->id }}</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="badge bg-primary fs-6">{{ $sale->sale_date->format('F d, Y') }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Product:</strong>
                            <p class="mb-0">
                                <a href="{{ route('products.show', $sale->product) }}">
                                    {{ $sale->product->name }}
                                </a>
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Current Stock:</strong>
                            <p class="mb-0">
                                @if($sale->product->stock > 10)
                                    <span class="badge bg-success">{{ $sale->product->stock }} in stock</span>
                                @elseif($sale->product->stock > 0)
                                    <span class="badge bg-warning">{{ $sale->product->stock }} left (Low Stock)</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Quantity Sold:</strong>
                            <p class="mb-0">{{ $sale->quantity }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Price Per Item:</strong>
                            <p class="mb-0">${{ number_format($sale->price_per_item, 2) }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Total:</strong>
                            <p class="mb-0 fs-5 fw-bold">${{ number_format($sale->total_price, 2) }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Sale Date:</strong>
                            <p class="mb-0">{{ $sale->sale_date->format('F d, Y') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Recorded On:</strong>
                            <p class="mb-0">{{ $sale->created_at->format('F d, Y g:i A') }}</p>
                        </div>

                        @if($sale->created_at != $sale->updated_at)
                            <div class="col-md-12 mb-3">
                                <strong>Last Updated:</strong>
                                <p class="mb-0">{{ $sale->updated_at->format('F d, Y g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white">
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
                            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Sale
                            </a>
                            <a href="{{ route('products.show', $sale->product) }}" class="btn btn-primary">
                                <i class="fas fa-box"></i> View Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 