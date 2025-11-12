@extends('layouts.app')

@section('title', 'Tambah Diskon')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Form Tambah Diskon Member</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('discounts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="minimum_purchase" class="form-label">Minimum Pembelian (Rp)</label>
                <input type="number" class="form-control @error('minimum_purchase') is-invalid @enderror" id="minimum_purchase" name="minimum_purchase" value="{{ old('minimum_purchase') }}" step="1" required>
                @error('minimum_purchase') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="discount_percentage" class="form-label">Diskon (%)</label>
                <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}" step="0.1" min="0" max="100" required>
                @error('discount_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Simpan
                </button>
                <a href="{{ route('discounts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection