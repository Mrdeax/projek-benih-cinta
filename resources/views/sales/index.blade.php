@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Transaksi Penjualan</h5>
        <a href="{{ route('sales.create') }}" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Transaksi Baru
        </a>
    </div>
    <div class="card-body">
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
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $index => $sale)
                        <tr>
                            <td>{{ ($sales->currentPage() - 1) * $sales->perPage() + $index + 1 }}</td>
                            <td><strong>{{ $sale->invoice_number }}</strong></td>
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
                            <td>
                                <span class="badge bg-success">{{ ucfirst($sale->status) }}</span>
                            </td>
                            <td><small>{{ $sale->created_at->format('d-m-Y H:i') }}</small></td>
                            <td>
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <a href="{{ route('sales.invoice', $sale->id) }}" class="btn btn-sm btn-secondary" target="_blank">
                                    <i class="bi bi-printer"></i> Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">Belum ada transaksi</td>
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