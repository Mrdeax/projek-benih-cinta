@extends('layouts.app')

@section('title', 'Laporan Transaksi Penjualan')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Laporan Transaksi Penjualan</h5>
        <form action="{{ route('reports.sales') }}" method="GET" class="d-flex gap-2" style="width: 500px;">
            <input type="date" name="start_date" class="form-control form-control-sm" placeholder="Dari Tanggal" value="{{ request('start_date') }}">
            <input type="date" name="end_date" class="form-control form-control-sm" placeholder="Sampai Tanggal" value="{{ request('end_date') }}">
            <select name="payment_method" class="form-control form-control-sm">
                <option value="">Semua Metode</option>
                <option value="cash" @if(request('payment_method') === 'cash') selected @endif>Tunai</option>
                <option value="card" @if(request('payment_method') === 'card') selected @endif>Kartu</option>
                <option value="transfer" @if(request('payment_method') === 'transfer') selected @endif>Transfer</option>
            </select>
            <button type="submit" class="btn btn-light btn-sm">Filter</button>
            <a href="{{ route('reports.sales-pdf', request()->except('page')) }}" class="btn btn-light btn-sm" target="_blank">
                <i class="bi bi-file-pdf"></i> PDF
            </a>
        </form>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value">{{ $totalSales }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-label">Total Diskon</div>
                    <div class="stat-value text-danger">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
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
                    @forelse($sales as $index => $sale)
                        <tr>
                            <td>{{ ($sales->currentPage() - 1) * $sales->perPage() + $index + 1 }}</td>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ $sale->user->name }}</td>
                            <td>{{ $sale->member ? $sale->member->user->name : '-' }}</td>
                            <td>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($sale->payment_method === 'cash')
                                    <span class="badge bg-success">Tunai</span>
                                @elseif($sale->payment_method === 'card')
                                    <span class="badge bg-info">Kartu</span>
                                @else
                                    <span class="badge bg-warning">Transfer</span>
                                @endif
                            </td>
                            <td>{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Tidak ada data transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection