@extends('layouts.app')

@section('title', 'Dashboard Member')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-label">Total Pembelian</div>
            <div class="stat-value">Rp {{ number_format($totalPurchase, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-label">Status Member</div>
            <div class="stat-value" style="font-size: 24px;">
                <span class="badge bg-success">Aktif</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Riwayat Pembelian</h5>
    </div>
    <div class="card-body">
        @if($recentPurchases->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Diskon</th>
                            <th>Final Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPurchases as $purchase)
                            <tr>
                                <td><a href="{{ route('sales.show', $purchase->id) }}">{{ $purchase->invoice_number }}</a></td>
                                <td>{{ $purchase->created_at->format('d-m-Y H:i') }}</td>
                                <td>Rp {{ number_format($purchase->subtotal, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($purchase->discount_amount, 0, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center py-4">Belum ada riwayat pembelian</p>
        @endif
    </div>
</div>
@endsection