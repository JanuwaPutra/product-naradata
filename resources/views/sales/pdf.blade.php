<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Laporan Transaksi</h1>
    
    @if($startDate && $endDate)
        <div class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
    @elseif($startDate)
        <div class="subtitle">Periode: Dari {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</div>
    @elseif($endDate)
        <div class="subtitle">Periode: Sampai {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">ID</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Kasir</th>
                <th width="15%">Pelanggan</th>
                <th width="33%">Produk</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td class="text-center">{{ $sale->id }}</td>
                    <td>{{ $sale->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $sale->cashier_name }}</td>
                    <td>{{ $sale->customer_name }}</td>
                    <td>
                        @foreach($sale->saleDetails as $detail)
                            {{ $detail->product->name }} ({{ $detail->quantity }})
                            @if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            
            @if($sales->isNotEmpty())
                <tr class="total-row">
                    <td colspan="5" class="text-right">Total:</td>
                    <td class="text-right">Rp {{ number_format($sales->sum('total_amount'), 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 