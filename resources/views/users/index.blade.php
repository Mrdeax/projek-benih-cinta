@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Pengguna</h5>
        <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Pengguna
        </a>
    </div>

    <div class="card-body">

        <!-- 🔍 Form Pencarian dan Filter -->
        <form method="GET" action="{{ route('users.index') }}" class="row mb-3 g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Cari nama, username, atau email...">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">-- Semua Role --</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Cari
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
        </form>

        <!-- Tabel Data Pengguna -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif($user->role === 'petugas')
                                    <span class="badge bg-warning text-dark">Petugas</span>
                                @else
                                    <span class="badge bg-success">Member</span>
                                @endif
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
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
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data pengguna</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
