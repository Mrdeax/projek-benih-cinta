@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Transaksi Hari Ini</div>
            <div class="stat-value">{{ $totalSalesDay }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Pendapatan Hari Ini</div>
            <div class="stat-value">Rp {{ number_format($totalRevenueDay, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Total Barang</div>
            <div class="stat-value">{{ $totalProducts }}</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Mulai Transaksi</h5>
            </div>
            <div class="card-body">
                <p>Silakan mulai proses penjualan dengan menekan tombol di bawah:</p>
                <a href="{{ route('sales.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Transaksi Baru
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
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
                                    <th>Nama</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                    <tr class="table-warning">
                                        <td>{{ $product->name }}</td>
                                        <td><strong>{{ $product->stock }}</strong></td>
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