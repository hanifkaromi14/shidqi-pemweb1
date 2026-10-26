@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Rencana Target & Wishlist Belanja</h3>
            <p class="text-muted small mb-0">Daftar impian barang, servis kendaraan, estimasi biaya, dan pemantauan tabungan
            </p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahWishlist">
            <i class="bi bi-plus-lg me-1"></i> Tambah Item Target
        </button>
    </div>

    <!-- Kartu Akumulasi Kebutuhan Dana -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small">Total Estimasi Kebutuhan</span>
                <h4 class="fw-bold text-dark mt-2 mb-0">Rp
                    {{ number_format($wishlists->sum('estimated_cost'), 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small">Dana Terkumpul / Dialokasikan</span>
                <h4 class="fw-bold text-success mt-2 mb-0">Rp
                    {{ number_format($wishlists->sum('collected_amount'), 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <span class="text-muted small">Sisa Kebutuhan Dana</span>
                <h4 class="fw-bold text-danger mt-2 mb-0">
                    Rp
                    {{ number_format(max(0, $wishlists->sum('estimated_cost') - $wishlists->sum('collected_amount')), 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Wishlist -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Item Target</th>
                        <th>Prioritas</th>
                        <th>Estimasi Biaya</th>
                        <th>Alokasi Terkumpul</th>
                        <th style="min-width: 160px;">Progress Capaian</th>
                        <th>Status</th>
                        <th>Catatan / Rencana</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wishlists as $item)
                        @php
                            $percentage =
                                $item->estimated_cost > 0
                                    ? round(($item->collected_amount / $item->estimated_cost) * 100)
                                    : 0;
                            $percentage = min(100, $percentage);
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $item->item_name }}</td>
                            <td>
                                @if ($item->priority === 'Tinggi')
                                    <span class="badge bg-danger">Tinggi</span>
                                @elseif ($item->priority === 'Sedang')
                                    <span class="badge bg-warning text-dark">Sedang</span>
                                @else
                                    <span class="badge bg-secondary">Rendah</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($item->estimated_cost, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->collected_amount, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="small fw-bold text-muted">{{ $percentage }}%</span>
                                </div>
                            </td>
                            <td>
                                @if ($item->status === 'Tercapai')
                                    <span class="badge bg-success">Tercapai</span>
                                @elseif ($item->status === 'Sedang Dicicil')
                                    <span class="badge bg-primary">Sedang Dicicil</span>
                                @else
                                    <span class="badge bg-light text-muted border">Belum Mulai</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $item->notes ?? '-' }}</td>
                            <td class="text-center">
                                <form action="{{ route('wishlists.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus item wishlist ini?')">
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
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada item target wishlist yang
                                dicatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Target Wishlist -->
    <div class="modal fade" id="modalTambahWishlist" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('wishlists.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Target Belanja Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Item Target</label>
                        <input type="text" name="item_name" class="form-control" placeholder="Contoh: Power Bank"
                            required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Prioritas</label>
                            <select name="priority" class="form-select">
                                <option value="Tinggi">Tinggi</option>
                                <option value="Sedang" selected>Sedang</option>
                                <option value="Rendah">Rendah</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Status Capaian</label>
                            <select name="status" class="form-select">
                                <option value="Belum Mulai" selected>Belum Mulai</option>
                                <option value="Sedang Dicicil">Sedang Dicicil</option>
                                <option value="Tercapai">Tercapai</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Estimasi Biaya (Rp)</label>
                            <input type="number" name="estimated_cost" class="form-control" placeholder="350000"
                                min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Alokasi Awal Terkumpul (Rp)</label>
                            <input type="number" name="collected_amount" class="form-control" value="0"
                                min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Catatan / Rencana Eksekusi</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan peruntukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Target</button>
                </div>
            </form>
        </div>
    </div>
@endsection
