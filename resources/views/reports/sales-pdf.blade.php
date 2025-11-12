<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 24px; }
        .info { font-size: 12px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background: #f0f0f0; padding: 8px; text-align: left; border-bottom: 2px solid #000; font-weight: bold; }
        table td { padding: 8px; border-bottom: 1px dotted #ddd; }
        .summary { margin-top: 20px; text-align: right; }
        .summary-row { font-size: 14px; margin: 5px 0; }
        .summary-total { font-size: 16px; font-weight: bold; border-top: 2px solid #000; padding-top: 10px; margin-top: 10px; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI PENJUALAN</h2>
        <p>Kasir App</p>
    </div>

    <div class="info">
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Petugas</th>
                <th>Member</th>
                <th>Subtotal</th>
                <th>Diskon</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ $sale->member ? $sale->member->user->name : '-' }}</td>
                    <td>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    <td>
                        @if($sale->payment_method === 'cash') Tunai @elseif($sale->payment_method === 'card') Kartu @else Transfer @endif
                    </td>
                    <td>{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">Subtotal: Rp {{ number_format($sales->sum('subtotal'), 0, ',', '.') }}</div>
        <div class="summary-row">Total Diskon: Rp {{ number_format($totalDiscount, 0, ',', '.') }}</div>
        <div class="summary-row summary-total">TOTAL PENDAPATAN: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>