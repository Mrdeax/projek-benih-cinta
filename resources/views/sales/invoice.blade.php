<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Faktur - {{ $sale->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .invoice-container { max-width: 400px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .invoice-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .invoice-header h2 { margin: 0; font-size: 24px; }
        .invoice-header p { margin: 5px 0; font-size: 12px; }
        .invoice-info { font-size: 12px; margin-bottom: 15px; }
        .invoice-info p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        table th { background: #f0f0f0; padding: 5px; text-align: left; border-bottom: 1px solid #000; }
        table td { padding: 5px; border-bottom: 1px dotted #ddd; }
        table.total { margin-top: 15px; }
        table.total td { border: none; }
        .total-row { font-weight: bold; border-top: 1px solid #000; padding-top: 10px; }
        .footer { text-align: center; margin-top: 20px; font-size: 11px; }
        @media print { body { margin: 0; padding: 0; } }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h2>KASIR APP</h2>
            <p>Toko Retail</p>
        </div>

        <div class="invoice-info">
            <p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
            <p><strong>Tanggal:</strong> {{ $sale->created_at->format('d-m-Y H:i') }}</p>
            <p><strong>Petugas:</strong> {{ $sale->user->name }}</p>
            @if($sale->member)
                <p><strong>Member:</strong> {{ $sale->member->user->name }}</p>
                <p><strong>Member ID:</strong> {{ $sale->member->member_code }}</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th style="text-align: right;">Qty</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td style="text-align: right;">{{ $detail->quantity }}</td>
                        <td style="text-align: right;">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="total">
            <tr>
                <td>Subtotal</td>
                <td style="text-align: right;">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($sale->discount_amount > 0)
                <tr>
                    <td>Diskon</td>
                    <td style="text-align: right;">Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>TOTAL</td>
                <td style="text-align: right;">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">Metode: 
                    @if($sale->payment_method === 'cash') Tunai @elseif($sale->payment_method === 'card') Kartu @else Transfer @endif
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Terima kasih atas pembelian Anda</p>
            <p>{{ $sale->created_at->format('d-m-Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>