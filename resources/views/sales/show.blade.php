@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detail Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
                        <p><strong>Tanggal:</strong> {{ $sale->created_at->format('d-m-Y H:i:s') }}</p>
                        <p><strong>Petugas:</strong> {{ $sale->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Member:</strong> {{ $sale->member ? $sale->member->user->name : 'Tidak Ada' }}</p>
                        <p><strong>Metode Pembayaran:</strong> 
                            @if($sale->payment_method === 'cash') Tunai @elseif($sale->payment_method === 'card') Kartu @else Transfer @endif
                        </p>
                        <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($sale->status) }}</span></p>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->details as $detail)
                            <tr>
                                <td>{{ $detail->product->name }} ({{ $detail->product->code }})</td>
                                <td>Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Ringkasan Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                    <span>Diskon:</span>
                    <strong>Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3" style="font-size: 20px;">
                    <span>Total:</span>
                    <strong style="color: #667eea;">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong>
                </div>

                <a href="{{ route('sales.invoice', $sale->id) }}" class="btn btn-info w-100 mb-2" target="_blank">
                    <i class="bi bi-printer"></i> Cetak Faktur
                </a>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection