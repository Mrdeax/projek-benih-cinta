<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 24px; }
        .info { font-size: 12px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background: #f0f0f0; padding: 8px; text-align: left; border-bottom: 2px solid #000; font-weight: bold; }
        table td { padding: 8px; border-bottom: 1px dotted #ddd; }
        .summary { margin-top: 20px; }
        .summary-item { font-size: 14px; margin: 5px 0; }
        .warning { color: red; font-weight: bold; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN STOK BARANG</h2>
        <p>Kasir App</p>
    </div>

    <div class="info">
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    @if($lowStockProducts->count() > 0)
        <div style="background: #ffe6e6; padding: 10px; margin-bottom: 15px; border-left: 3px solid red;">
            <strong class="warning">PERINGATAN: Barang dengan Stok Rendah</strong>
            <ul style="margin: 10px 0;">
                @foreach($lowStockProducts as $product)
                    <li>{{ $product->name }} - Stok: {{ $product->stock }} (Min: {{ $product->minimum_stock }})</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Min</th>
                <th>Total Nilai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->minimum_stock }}</td>
                    <td>Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}</td>
                    <td>@if($product->isStockLow()) <span class="warning">RENDAH</span> @else Normal @endif</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item"><strong>Total Barang:</strong> {{ $products->count() }}</div>
        <div class="summary-item"><strong>Total Unit:</strong> {{ $products->sum('stock') }}</div>
        <div class="summary-item"><strong>Total Nilai Inventori:</strong> Rp {{ number_format($totalValue->total, 0, ',', '.') }}</div>
        <div class="summary-item"><strong>Barang Stok Rendah:</strong> {{ $lowStockProducts->count() }}</div>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>