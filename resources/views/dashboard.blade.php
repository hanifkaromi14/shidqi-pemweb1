<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KeuanganApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">KeuanganApp</a>

            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <h1 class="h3">Dashboard</h1>
    </main>
</body>
</html>