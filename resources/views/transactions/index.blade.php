@extends('layouts.app')

@section('content')
    <!-- Formulir Entri Transaksi Cepat -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title fw-bold mb-0">
                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Formulir Input Transaksi Cepat
            </h5>
            <small class="text-muted">Pencatatan langsung untuk mengupdate saldo kas secara otomatis</small>
        </div>
        <div class="card-body">
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Tanggal</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                            value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Jenis Transaksi</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" id="txType"
                            required>
                            <option value="Pengeluaran" {{ old('type') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran (-)
                            </option>
                            <option value="Pemasukan" {{ old('type') == 'Pemasukan' ? 'selected' : '' }}>Pemasukan (+)
                            </option>
                            <option value="Transfer Antar Rekening"
                                {{ old('type') == 'Transfer Antar Rekening' ? 'selected' : '' }}>Transfer Antar Rekening
                            </option>
                            <option value="Tarik Tunai" {{ old('type') == 'Tarik Tunai' ? 'selected' : '' }}>Tarik Tunai
                            </option>
                            <option value="Setor Tunai" {{ old('type') == 'Setor Tunai' ? 'selected' : '' }}>Setor Tunai
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Kategori Transaksi</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    [{{ $cat->group_type }}] {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Rekening Asal (Keluar)</label>
                        <select name="source_account_id"
                            class="form-select @error('source_account_id') is-invalid @enderror">
                            <option value="">-</option>
                            @foreach ($accounts as $acc)
                                <option value="{{ $acc->id }}"
                                    {{ old('source_account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Rekening Tujuan (Masuk)</label>
                        <select name="destination_account_id"
                            class="form-select @error('destination_account_id') is-invalid @enderror">
                            <option value="">-</option>
                            @foreach ($accounts as $acc)
                                <option value="{{ $acc->id }}"
                                    {{ old('destination_account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Nominal (Rp)</label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                            value="{{ old('amount') }}" placeholder="Contoh: 15000" min="500" required>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small fw-semibold">Keterangan Transaksi</label>
                        <input type="text" name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            value="{{ old('description') }}" placeholder="Contoh: Makan Siang di Kantin Kampus">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Buku Transaksi -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Buku Catatan Transaksi Keuangan</h5>
            <span class="badge bg-secondary">Total: {{ $transactions->total() }} Data</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Rek. Asal</th>
                        <th>Rek. Tujuan</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $tx)
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
                            <td>{{ $tx->sourceAccount?->name ?? '-' }}</td>
                            <td>{{ $tx->destinationAccount?->name ?? '-' }}</td>
                            <td
                                class="fw-bold {{ $tx->type === 'Pemasukan' ? 'text-success' : ($tx->type === 'Pengeluaran' ? 'text-danger' : 'text-primary') }}">
                                {{ $tx->type === 'Pemasukan' ? '+' : ($tx->type === 'Pengeluaran' ? '-' : '') }} Rp
                                {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td>{{ $tx->description ?? '-' }}</td>
                            <td class="text-center">
                                <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo rekening akan disesuaikan kembali.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat transaksi yang
                                tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($transactions->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
