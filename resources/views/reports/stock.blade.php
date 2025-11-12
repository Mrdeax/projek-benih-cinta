@extends('layouts.app')

@section('title', 'Laporan Stok Barang')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Laporan Stok Barang</h5>
        <a href="{{ route('reports.stock-pdf') }}" class="btn btn-light btn-sm" target="_blank">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Total Barang</div>
                    <div class="stat-value">{{ $products->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Stok Rendah</div>
                    <div class="stat-value text-danger">{{ $lowStockProducts->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Total Unit</div>
                    <div class="stat-value">{{ $products->sum('stock') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Total Nilai</div>
                    <div class="stat-value">Rp {{ number_format($totalValue->total, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        @if($lowStockProducts->count() > 0)
            <div class="alert alert-warning" role="alert">
                <h6 class="alert-heading">Barang dengan Stok Rendah</h6>
                <ul class="mb-0">
                    @foreach($lowStockProducts as $product)
                        <li>{{ $product->name }} - Stok: {{ $product->stock }} (Min: {{ $product->minimum_stock }})</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Stok</th>
                        <th>Min Stok</th>
                        <th>Total Nilai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->minimum_stock }}</td>
                            <td>Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}</td>
                            <td>
                                @if($product->isStockLow())
                                    <span class="badge bg-danger">Rendah</span>
                                @else
                                    <span class="badge bg-success">Normal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection