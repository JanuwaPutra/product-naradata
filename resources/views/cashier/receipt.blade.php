@extends('layouts.app')

@section('title', 'Struk Transaksi')

@section('header-buttons')
<div class="d-flex gap-2">
    <a href="{{ route('cashier.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Kasir
    </a>
    <button class="btn btn-primary" onclick="window.print()">
        <i class="fas fa-print me-2"></i>Cetak Struk
    </button>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Struk Transaksi #{{ $sale->id }}</h5>
            </div>
            <div class="card-body">
                <div class="receipt-content">
                    <div class="text-center mb-4">
                        <h4>Naradata</h4>
                        <p class="mb-0">Sistem Manajemen Gudang</p>
                        <p class="mb-0">Jl. Contoh No. 123, Jakarta</p>
                        <p>Telp: 021-1234567</p>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-1"><strong>No. Transaksi:</strong> #{{ $sale->id }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ $sale->transaction_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-1"><strong>Kasir:</strong> {{ $sale->cashier_name }}</p>
                            <p class="mb-1"><strong>Pelanggan:</strong> {{ $sale->customer_name }}</p>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->saleDetails as $detail)
                                <tr>
                                    <td>{{ $detail->product->name }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p>Terima kasih telah berbelanja di Naradata</p>
                        <p class="mb-0">Barang yang sudah dibeli tidak dapat dikembalikan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        
        .receipt-content, .receipt-content * {
            visibility: visible;
        }
        
        .receipt-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        .navbar, .sidebar, .mobile-bottom-nav, footer, .header-buttons {
            display: none !important;
        }
    }
</style>
@endsection 