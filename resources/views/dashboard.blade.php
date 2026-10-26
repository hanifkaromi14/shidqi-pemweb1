@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Dashboard Ringkasan Finansial</h3>
            <p class="text-muted small mb-0">Pemantauan saldo kas riil, arus kas bulanan, dan status rekening</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Entri Transaksi Cepat
        </a>
    </div>

    <!-- Zona 1: Kartu Saldo Kas & Kondisi Finansial Likuid -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small opacity-75">TOTAL SALDO KAS LIKUID</span>
                    <i class="bi bi-wallet2 fs-4 opacity-75"></i>
                </div>
                <h3 class="fw-bold my-2">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h3>
                <span class="small opacity-75">Akumulasi seluruh kantong kas riil</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small opacity-75">PEMASUKAN BULAN INI</span>
                    <i class="bi bi-arrow-down-left-circle fs-4 opacity-75"></i>
                </div>
                <h3 class="fw-bold my-2">+ Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
                <span class="small opacity-75">Arus kas masuk periode berjalan</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-danger text-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small opacity-75">PENGELUARAN BULAN INI</span>
                    <i class="bi bi-arrow-up-right-circle fs-4 opacity-75"></i>
                </div>
                <h3 class="fw-bold my-2">- Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</h3>
                <span class="small opacity-75">Arus belanja & kewajiban keluar</span>
            </div>
        </div>
    </div>

    <!-- Zona 2: Rincian Saldo Per Rekening / Kantong -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold mb-0">Pos Saldo Per Rekening & Kantong Kas</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row g-3">
                @forelse ($accounts as $acc)
                    <div class="col-md-3 col-sm-6">
                        <div class="border rounded-3 p-3 bg-light h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary">{{ $acc->type }}</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">{{ $acc->name }}</h6>
                            <h5 class="fw-bold text-primary mb-2">Rp {{ number_format($acc->current_balance, 0, ',', '.') }}
                            </h5>
                            <p class="text-muted small mb-0">{{ $acc->purpose ?? 'Kantong Operasional' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-3 text-muted">Belum ada akun rekening yang terdaftar.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Zona 3: Riwayat 5 Transaksi Terbaru -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Transaksi Terakhir</h5>
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua Buku
                Transaksi</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Kategori</th>
                        <th>Rek. Terkait</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentTransactions as $tx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tx->date)->translatedFormat('d M Y') }}</td>
                            <td>
                                @if ($tx->type === 'Pemasukan')
                                    <span class="badge bg-success">Pemasukan</span>
                                @elseif ($tx->type === 'Pengeluaran')
                                    <span class="badge bg-danger">Pengeluaran</span>
                                @else
                                    <span class="badge bg-info text-dark">{{ $tx->type }}</span>
                                @endif
                            </td>
                            <td>{{ $tx->category?->name ?? '-' }}</td>
                            <td>
                                @if ($tx->type === 'Pemasukan')
                                    <i class="bi bi-arrow-right-short text-success"></i>
                                    {{ $tx->destinationAccount?->name }}
                                @elseif ($tx->type === 'Pengeluaran')
                                    <i class="bi bi-arrow-left-short text-danger"></i> {{ $tx->sourceAccount?->name }}
                                @else
                                    {{ $tx->sourceAccount?->name }} <i class="bi bi-arrow-right"></i>
                                    {{ $tx->destinationAccount?->name }}
                                @endif
                            </td>
                            <td
                                class="fw-bold {{ $tx->type === 'Pemasukan' ? 'text-success' : ($tx->type === 'Pengeluaran' ? 'text-danger' : 'text-primary') }}">
                                {{ $tx->type === 'Pemasukan' ? '+' : ($tx->type === 'Pengeluaran' ? '-' : '') }} Rp
                                {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td>{{ $tx->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada aktivitas transaksi tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
