<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Inventaris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
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
        .badge-success {
            color: green;
        }
        .badge-warning {
            color: orange;
        }
        .badge-danger {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Daftar Inventaris</h1>
    
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">ID</th>
                <th width="25%">Nama Barang</th>
                <th width="30%">Deskripsi</th>
                <th width="15%">Harga</th>
                <th width="10%" class="text-center">Stok</th>
                <th width="15%">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="text-center">{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description ?? 'Tidak ada deskripsi' }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($product->stock > 10)
                            <span class="badge-success">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                            <span class="badge-warning">{{ $product->stock }}</span>
                        @else
                            <span class="badge-danger">Habis</span>
                        @endif
                    </td>
                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 