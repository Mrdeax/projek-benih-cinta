<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex ; align-items: center; justify-content: center; }
        .login-container { background: white; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); padding: 40px; max-width: 400px; width: 100%; }
        .login-container h1 { color: #333; margin-bottom: 30px; text-align: center; font-size: 28px; font-weight: bold; }
        .form-control { border-radius: 5px; padding: 10px 15px; border: 1px solid #ddd; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .btn-login { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 10px; font-weight: bold; border-radius: 5px; width: 100%; color: white; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); color: white; }
        .register-link { text-align: center; margin-top: 20px; }
        .register-link a { color: #667eea; text-decoration: none; font-weight: 600; }
        .alert { border-radius: 5px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Kasir App</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-login">Login</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>