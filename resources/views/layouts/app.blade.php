<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Aplikasi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; --dark: #2c3e50; --light: #f8f9fa; }
        body { background-color: var(--light); }
        .sidebar { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); height: 100vh; position: fixed; left: 0; top: 0; padding-top: 20px; overflow-y: auto; }
        .sidebar .brand { color: white; font-size: 22px; font-weight: bold; padding: 0 20px 30px; border-bottom: 1px solid rgba(255,255,255,0.2); margin-bottom: 20px; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; display: flex; align-items: center; gap: 10px; transition: all 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
        .sidebar .nav-link i { font-size: 18px; }
        .main-content { margin-left: 250px; padding-top: 20px; }
        .topbar { background: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .topbar .user-info { display: flex; align-items: center; gap: 10px; }
        .content { padding: 0 30px 30px; }
        .card { box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: none; border-radius: 8px; }
        .stat-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .stat-card .stat-value { font-size: 32px; font-weight: bold; color: var(--primary); margin: 10px 0; }
        .stat-card .stat-label { color: #666; font-size: 14px; }
        .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); border: none; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); color: white; }
        .table { background: white; border-radius: 8px; }
        .table-responsive { border-radius: 8px; }
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; padding-bottom: 20px; }
            .main-content { margin-left: 0; }
            .topbar { flex-direction: column; gap: 15px; text-align: center; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <div class="sidebar">
            <div class="brand">
                <i class="bi bi-receipt"></i> Kasir App
            </div>
            <nav class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                @if (Auth::user()->isAdmin() || Auth::user()->isOfficer())
                    <a href="{{ route('products.index') }}" class="nav-link @if(request()->routeIs('products.*')) active @endif">
                        <i class="bi bi-box"></i> Data Barang
                    </a>
                    <a href="{{ route('sales.index') }}" class="nav-link @if(request()->routeIs('sales.*')) active @endif">
                        <i class="bi bi-cart-check"></i> Transaksi Penjualan
                    </a>
                    <a href="{{ route('reports.stock') }}" class="nav-link @if(request()->routeIs('reports.stock')) active @endif">
                        <i class="bi bi-graph-up"></i> Laporan Stok
                    </a>
                    <a href="{{ route('reports.sales') }}" class="nav-link @if(request()->routeIs('reports.sales')) active @endif">
                        <i class="bi bi-file-earmark"></i> Laporan Penjualan
                    </a>
                @endif

                @if (Auth::user()->isAdmin())
                    <a href="{{ route('users.index') }}" class="nav-link @if(request()->routeIs('users.*')) active @endif">
                        <i class="bi bi-people"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('discounts.index') }}" class="nav-link @if(request()->routeIs('discounts.*')) active @endif">
                        <i class="bi bi-percent"></i> Diskon Member
                    </a>
                @endif
            </nav>
        </div>

        <div class="main-content w-100">
            <div class="topbar">
                <div>
                    <h5>Selamat datang, <strong>{{ Auth::user()->name }}</strong></h5>
                    <small class="text-muted">{{ Auth::user()->role == 'admin' ? 'Administrator' : (Auth::user()->role == 'petugas' ? 'Petugas' : 'Member') }}</small>
                </div>
                <div class="user-info">
                    <span class="badge bg-primary">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Sukses!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>