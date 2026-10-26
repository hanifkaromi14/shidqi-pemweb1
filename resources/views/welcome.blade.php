<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KeuanganApp - Kelola Finansial Harian Lebih Terencana</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .hero-section {
            padding: 90px 0;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: #fff;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }

        .card-feature {
            border-radius: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar Publik -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ url('/') }}">
                <i class="bi bi-wallet2 me-2"></i> KeuanganApp
            </a>
            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-speedometer2 me-1"></i> Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-light text-primary px-3 py-2 rounded-pill fw-semibold mb-3">
                        <i class="bi bi-stars me-1"></i> Sistem Pencatatan Multi-Rekening
                    </span>
                    <h1 class="display-5 fw-bold mb-3">Pencatatan Keuangan Harian yang Rinci, Transparan, & Terkendali
                    </h1>
                    <p class="lead opacity-75 mb-4">
                        Kelola kantong rekening kas, pantau arus kas masuk dan keluar secara riil, serta wujudkan target
                        belanja impian dalam satu aplikasi web.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg text-primary fw-semibold px-4">
                                Masuk ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                                class="btn btn-light btn-lg text-primary fw-semibold px-4 shadow-sm">
                                Mulai Sekarang Gratis
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                                Masuk ke Akun
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Utama -->
    <section class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase">Fitur Unggulan</h6>
                <h3 class="fw-bold">Solusi Finansial Berbasis Pembukuan Riil</h3>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-feature border-0 shadow-sm p-4 h-100 bg-white">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Multi-Rekening Kas</h5>
                        <p class="text-muted small mb-0">
                            Pantau pembagian saldo kas Anda secara terpisah mulai dari SeaBank, BSI, BRI, hingga uang
                            tunai di dompet fisik.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-feature border-0 shadow-sm p-4 h-100 bg-white">
                        <div class="feature-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Entri Transaksi Cepat</h5>
                        <p class="text-muted small mb-0">
                            Pencatatan langsung untuk pemasukan, pengeluaran, serta mutasi antar rekening dengan
                            pembaruan saldo secara otomatis.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-feature border-0 shadow-sm p-4 h-100 bg-white">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Target & Wishlist</h5>
                        <p class="text-muted small mb-0">
                            Susun target barang impian atau kebutuhan servis kendaraan lengkap dengan pemantauan
                            persentase progres dana yang terkumpul.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 text-center text-muted small">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} KeuanganApp. Sistem Pencatatan Keuangan Terintegrasi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
