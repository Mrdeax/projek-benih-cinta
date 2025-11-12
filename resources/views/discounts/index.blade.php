@extends('layouts.app')

@section('title', 'Manajemen Diskon Member')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Diskon Member</h5>
        <a href="{{ route('discounts.create') }}" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Diskon
        </a>
    </div>
    <div class="card-body">
        <div class="alert alert-info" role="alert">
            <strong>Informasi:</strong> Diskon otomatis diberikan kepada member berdasarkan total pembelian mereka. Semakin tinggi pembelian, semakin besar diskonnya.
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Minimum Pembelian</th>
                        <th>Diskon (%)</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discounts as $index => $discount)
                        <tr>
                            <td>{{ ($discounts->currentPage() - 1) * $discounts->perPage() + $index + 1 }}</td>
                            <td>Rp {{ number_format($discount->minimum_purchase, 0, ',', '.') }}</td>
                            <td><strong>{{ $discount->discount_percentage }}%</strong></td>
                            <td>{{ $discount->description ?? '-' }}</td>
                            <td>
                                <a href="{{ route('discounts.edit', $discount->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('discounts.destroy', $discount->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada data diskon</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $discounts->links() }}
        </div>
    </div>
</div>
@endsection