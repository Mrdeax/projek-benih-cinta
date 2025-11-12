@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $totalSales }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Barang</div>
            <div class="stat-value">{{ $totalProducts }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Pengguna</div>
            <div class="stat-value">{{ $totalUsers }}</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Transaksi Terbaru</h5>
            </div>
            <div class="card-body">
                @if($recentSales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice</th>
                                    <th>Petugas</th>
                                    <th>Total</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSales as $sale)
                                    <tr>
                                        <td><a href="{{ route('sales.show', $sale->id) }}">{{ $sale->invoice_number }}</a></td>
                                        <td>{{ $sale->user->name }}</td>
                                        <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                        <td><small>{{ $sale->created_at->diffForHumans() }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada transaksi</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Barang Stok Rendah</h5>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Stok</th>
                                    <th>Min</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                    <tr class="table-warning">
                                        <td>{{ $product->code }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td><strong>{{ $product->stock }}</strong></td>
                                        <td>{{ $product->minimum_stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Semua stok normal</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection