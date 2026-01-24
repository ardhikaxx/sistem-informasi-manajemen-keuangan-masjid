@extends('layouts.app')
@section('title', 'Manajemen Keuangan - Keuangan Masjid')
@section('page-title', 'Manajemen Keuangan')
@section('content')
    <!-- Header Section with Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-effect p-4">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Transaksi Keuangan Masjid</h5>
                        <p class="text-muted mb-0">Kelola pemasukan dan pengeluaran keuangan masjid</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahPemasukanModal">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Pemasukan
                        </button>
                        <button class="btn btn-secondary text-white" data-bs-toggle="modal"
                            data-bs-target="#tambahPengeluaranModal">
                            <i class="fas fa-minus-circle me-2"></i>Tambah Pengeluaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Filter and Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-effect p-4">
                <form method="GET" action="{{ route('admins.manajemen-keuangan') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control border-start-0" placeholder="Cari transaksi...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="jenis" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Jenis</option>
                                <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan
                                </option>
                                <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>
                                    Pengeluaran</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="bulan" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Bulan</option>
                                @foreach (['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $key => $month)
                                    <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                        {{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="tahun" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach ($tahunList as $year)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                                <i class="fas fa-redo"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="glass-effect p-4 text-center">
                <h6 class="text-muted mb-2">Total Pemasukan</h6>
                <h4 class="text-success fw-bold">Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-effect p-4 text-center">
                <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                <h4 class="text-warning fw-bold">Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-effect p-4 text-center">
                <h6 class="text-muted mb-2">Saldo Akhir</h6>
                <h4 class="text-primary fw-bold">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <!-- Transactions Table -->
    <div class="row fade-in-up mb-5">
        <div class="col-12">
            <div class="glass-effect p-4">
                <div class="table-responsive">
                    <table class="table table-hover" id="transactionsTable">
                        <thead>
                            <tr class="table-light">
                                <th class="fw-semibold text-dark">Tanggal</th>
                                <th class="fw-semibold text-dark">Uraian</th>
                                <th class="fw-semibold text-dark">Jenis</th>
                                <th class="fw-semibold text-dark text-end">Jumlah</th>
                                <th class="fw-semibold text-dark text-end">Saldo</th>
                                <th class="fw-semibold text-dark">Keterangan</th>
                                <th class="fw-semibold text-dark text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $transaksi)
                                <tr
                                    class="{{ $transaksi->jenis_transaksi == 'pemasukan' ? 'table-success-light' : 'table-warning-light' }}">
                                    <td class="text-nowrap">{{ $transaksi->formatted_tanggal }}</td>
                                    <td>
                                        <div class="fw-medium">{{ $transaksi->uraian }}</div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $transaksi->jenis_transaksi == 'pemasukan' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $transaksi->jenis_transaksi == 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span
                                            class="{{ $transaksi->jenis_transaksi == 'pemasukan' ? 'text-success' : 'text-warning' }} fw-bold">
                                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold">Rp
                                            {{ number_format($transaksi->saldo_sesudah, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if ($transaksi->keterangan)
                                            <span
                                                class="text-muted small">{{ Str::limit($transaksi->keterangan, 50) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn btn-sm btn-outline-primary edit-btn"
                                                data-id="{{ $transaksi->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn"
                                                data-id="{{ $transaksi->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p>Tidak ada transaksi ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if ($transaksis->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Menampilkan {{ $transaksis->firstItem() }} sampai {{ $transaksis->lastItem() }} dari
                            {{ $transaksis->total() }} transaksi
                        </div>
                        <div>
                            {{ $transaksis->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Modals -->
    <!-- Tambah Pemasukan Modal -->
    <div class="modal fade" id="tambahPemasukanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title text-success">
                        <i class="fas fa-donate me-2"></i>Tambah Pemasukan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPemasukan">
                        @csrf
                        <input type="hidden" name="jenis_transaksi" value="pemasukan">
                        <div class="mb-3">
                            <label for="tanggalPemasukan" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggalPemasukan" name="tanggal"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="uraianPemasukan" class="form-label">Uraian</label>
                            <input type="text" class="form-control" id="uraianPemasukan" name="uraian"
                                placeholder="Contoh: Donatur Rutin" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlahPemasukan" class="form-label">Jumlah Pemasukan</label>
                            <input type="text" class="form-control currency-input" id="jumlahPemasukan"
                                name="jumlah" placeholder="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="keteranganPemasukan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keteranganPemasukan" name="keterangan" rows="3"
                                placeholder="Tambahkan keterangan (opsional)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="simpanPemasukan">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Tambah Pengeluaran Modal -->
    <div class="modal fade" id="tambahPengeluaranModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title text-warning">
                        <i class="fas fa-shopping-cart me-2"></i>Tambah Pengeluaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPengeluaran">
                        @csrf
                        <input type="hidden" name="jenis_transaksi" value="pengeluaran">
                        <div class="mb-3">
                            <label for="tanggalPengeluaran" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggalPengeluaran" name="tanggal"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="uraianPengeluaran" class="form-label">Uraian</label>
                            <input type="text" class="form-control" id="uraianPengeluaran" name="uraian"
                                placeholder="Contoh: Pembelian Perlengkapan" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlahPengeluaran" class="form-label">Jumlah Pengeluaran</label>
                            <input type="text" class="form-control currency-input" id="jumlahPengeluaran"
                                name="jumlah" placeholder="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="keteranganPengeluaran" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keteranganPengeluaran" name="keterangan" rows="3"
                                placeholder="Tambahkan keterangan (opsional)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning text-white" id="simpanPengeluaran">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">
                        <i class="fas fa-edit me-2"></i>Edit Transaksi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEdit">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editTanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editTanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUraian" class="form-label">Uraian</label>
                            <input type="text" class="form-control" id="editUraian" name="uraian" required>
                        </div>
                        <div class="mb-3">
                            <label for="editJenis" class="form-label">Jenis Transaksi</label>
                            <select class="form-select" id="editJenis" name="jenis_transaksi" required>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editJumlah" class="form-label">Jumlah</label>
                            <input type="text" class="form-control currency-input" id="editJumlah" name="jumlah"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editKeterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="editKeterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="simpanEdit">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content glass-modal">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
                    <p class="text-muted small">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .glass-modal {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-success-light {
            background-color: rgba(25, 135, 84, 0.05);
        }

        .table-warning-light {
            background-color: rgba(255, 193, 7, 0.05);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #28a745);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #fd7e14);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.1);
        }
    </style>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let deleteId = null;
            // Inisialisasi instance modal di awal
            const tambahPemasukanModalEl = document.getElementById('tambahPemasukanModal');
            const tambahPengeluaranModalEl = document.getElementById('tambahPengeluaranModal');
            const editModalEl = document.getElementById('editModal');

            const tambahPemasukanModalInstance = new bootstrap.Modal(tambahPemasukanModalEl);
            const tambahPengeluaranModalInstance = new bootstrap.Modal(tambahPengeluaranModalEl);
            const editModalInstance = new bootstrap.Modal(editModalEl);

            // Helper functions for currency formatting
            function formatNumber(num) {
                if (!num) return '0';
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function parseNumber(str) {
                if (!str) return 0;
                // Remove all non-numeric characters except decimal point
                let cleanStr = str.toString().replace(/[^\d]/g, '');
                return parseInt(cleanStr) || 0;
            }

            // Setup currency inputs
            const currencyInputs = document.querySelectorAll('.currency-input');
            currencyInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value) {
                        const num = parseNumber(this.value);
                        if (num > 0) {
                            this.value = formatNumber(num);
                        }
                    }
                });
                input.addEventListener('focus', function() {
                    if (this.value) {
                        this.value = parseNumber(this.value);
                    }
                });
                input.addEventListener('input', function() {
                    // Allow only numbers
                    this.value = this.value.replace(/[^\d]/g, '');
                });
            });

            // Reset filter
            window.resetFilter = function() {
                window.location.href = "{{ route('admins.manajemen-keuangan') }}";
            };

            // Save Pemasukan
            document.getElementById('simpanPemasukan').addEventListener('click', savePemasukan);

            // Save Pengeluaran
            document.getElementById('simpanPengeluaran').addEventListener('click', savePengeluaran);

            function savePemasukan() {
                const form = document.getElementById('formPemasukan');
                saveTransaction(form, tambahPemasukanModalInstance, 'pemasukan');
            }

            function savePengeluaran() {
                const form = document.getElementById('formPengeluaran');
                saveTransaction(form, tambahPengeluaranModalInstance, 'pengeluaran');
            }

            // Main save transaction function
            function saveTransaction(form, modalInstance, type) {
                // Validate form
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                const formData = new FormData(form);
                // Format jumlah
                const jumlahInput = form.querySelector('input[name="jumlah"]');
                const jumlahValue = parseNumber(jumlahInput.value);
                if (jumlahValue <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Jumlah harus lebih dari 0',
                        background: '#f8f9fa',
                        backdrop: 'rgba(0,0,0,0.1)'
                    });
                    return;
                }
                formData.set('jumlah', jumlahValue);
                const url = "{{ route('admins.manajemen-keuangan.store') }}";
                const modalElement = modalInstance._element; // Dapatkan elemen DOM modal
                const saveButton = modalElement.querySelector(
                '.btn-success, .btn-warning'); // Ambil tombol simpan dari elemen modal
                // Tampilkan loading state
                const originalText = saveButton.innerHTML;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                saveButton.disabled = true;

                // Create object from FormData
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });
                // Add CSRF token
                data._token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': data._token
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        // Periksa status HTTP response sebelum membaca JSON
                        if (!response.ok) {
                            // Jika status bukan 2xx, anggap sebagai network/server error
                            throw new Error(`HTTP error! status: ${response.status}. ${response.statusText}`);
                        }
                        return response.json(); // Lanjutkan membaca JSON hanya jika status OK
                    })
                    .then(data => {
                        // Reset button state
                        saveButton.innerHTML = originalText;
                        saveButton.disabled = false;
                        // Sekarang periksa apakah data dari server memiliki field 'success'
                        if (data && typeof data === 'object' && data.success) {
                            // SUCCESS - Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Transaksi berhasil disimpan!',
                                timer: 2000,
                                showConfirmButton: false,
                                background: '#f8f9fa',
                                backdrop: 'rgba(0,0,0,0.1)'
                            }).then(() => {
                                // Close modal and reset form
                                modalInstance.hide(); // Gunakan instance untuk menyembunyikan modal
                                form.reset();
                                // Reset tanggal ke hari ini
                                const dateInput = form.querySelector('input[type="date"]');
                                if (dateInput) {
                                    dateInput.value = new Date().toISOString().split('T')[0];
                                }
                                // Reload page after success
                                window.location.reload();
                            });
                        } else {
                            // ERROR dari server (data.success === false)
                            let errorMessage = data?.message || 'Terjadi kesalahan saat menyimpan transaksi.';
                            if (data?.errors) {
                                errorMessage = '';
                                Object.values(data.errors).forEach(errors => {
                                    if (Array.isArray(errors)) {
                                        errors.forEach(error => {
                                            errorMessage += error + '<br>';
                                        });
                                    }
                                });
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                html: errorMessage,
                                background: '#f8f9fa',
                                backdrop: 'rgba(0,0,0,0.1)'
                            });
                        }
                    })
                    .catch(error => {
                        // Reset button state
                        saveButton.innerHTML = originalText;
                        saveButton.disabled = false;
                        console.error('Fetch Error Details:', error); // Log error untuk debugging
                        // Tampilkan SweetAlert untuk error jaringan atau parsing JSON
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan Jaringan',
                            text: 'Terjadi kesalahan jaringan atau server tidak merespons dengan benar. Silakan coba lagi.',
                            background: '#f8f9fa',
                            backdrop: 'rgba(0,0,0,0.1)'
                        });
                    });
            }

            // Edit button functionality
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    loadTransactionForEdit(id);
                });
            });

            function loadTransactionForEdit(id) {
                fetch(`/admin/manajemen-keuangan/edit/${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const transaksi = data.data;
                            document.getElementById('editId').value = transaksi.id;
                            document.getElementById('editTanggal').value = transaksi.tanggal.split(' ')[0];
                            document.getElementById('editUraian').value = transaksi.uraian;
                            document.getElementById('editJenis').value = transaksi.jenis_transaksi;
                            document.getElementById('editJumlah').value = formatNumber(transaksi.jumlah);
                            document.getElementById('editKeterangan').value = transaksi.keterangan || '';
                            editModalInstance.show(); // Gunakan instance untuk menampilkan modal
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Memuat',
                                text: data.message || 'Gagal mengambil data transaksi',
                                background: '#f8f9fa',
                                backdrop: 'rgba(0,0,0,0.1)'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan saat mengambil data transaksi',
                            background: '#f8f9fa',
                            backdrop: 'rgba(0,0,0,0.1)'
                        });
                    });
            }

            // Save Edit
            document.getElementById('simpanEdit').addEventListener('click', function() {
                const form = document.getElementById('formEdit');
                const id = document.getElementById('editId').value;
                // Validate form
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                const formData = new FormData(form);
                const saveButton = this;
                // Format jumlah
                const jumlahInput = document.getElementById('editJumlah');
                const jumlahValue = parseNumber(jumlahInput.value);
                if (jumlahValue <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Jumlah harus lebih dari 0',
                        background: '#f8f9fa',
                        backdrop: 'rgba(0,0,0,0.1)'
                    });
                    return;
                }
                formData.set('jumlah', jumlahValue);
                formData.set('_method', 'PUT');
                const url = `/admin/manajemen-keuangan/update/${id}`;
                // Tampilkan loading state
                const originalText = saveButton.innerHTML;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                saveButton.disabled = true;

                // Create object from FormData
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });
                // Add CSRF token
                data._token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(url, {
                        method: 'POST', // Tetap POST karena menggunakan _method PUT
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': data._token
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Jika status bukan 2xx, anggap sebagai network/server error
                            throw new Error(
                                `HTTP error! status: ${response.status}. ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Reset button state
                        saveButton.innerHTML = originalText;
                        saveButton.disabled = false;
                        if (data && typeof data === 'object' && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Transaksi berhasil diperbarui!',
                                timer: 2000,
                                showConfirmButton: false,
                                background: '#f8f9fa',
                                backdrop: 'rgba(0,0,0,0.1)'
                            }).then(() => {
                                // Tutup modal edit setelah sukses
                                editModalInstance
                            .hide(); // Gunakan instance untuk menyembunyikan modal
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            });
                        } else {
                            let errorMessage = data?.message || 'Gagal memperbarui transaksi.';
                            if (data?.errors) {
                                errorMessage = '';
                                Object.values(data.errors).forEach(errors => {
                                    if (Array.isArray(errors)) {
                                        errors.forEach(error => {
                                            errorMessage += error + '<br>';
                                        });
                                    }
                                });
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Memperbarui',
                                html: errorMessage,
                                background: '#f8f9fa',
                                backdrop: 'rgba(0,0,0,0.1)'
                            });
                        }
                    })
                    .catch(error => {
                        // Reset button state
                        saveButton.innerHTML = originalText;
                        saveButton.disabled = false;
                        console.error('Fetch Error Details (Update):', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan jaringan atau server saat memperbarui transaksi',
                            background: '#f8f9fa',
                            backdrop: 'rgba(0,0,0,0.1)'
                        });
                    });
            });

            // Delete button functionality
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-id');
                    $('#deleteModal').modal('show'); // Modal delete tetap bisa pakai jQuery
                });
            });

            // Confirm Delete
            document.getElementById('confirmDelete').addEventListener('click', function() {
                if (!deleteId) return;
                const deleteButton = this;
                const originalText = deleteButton.innerHTML;
                deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...';
                deleteButton.disabled = true;

                const url = `/admin/manajemen-keuangan/delete/${deleteId}`;

                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Jika status bukan 2xx, anggap sebagai network/server error
                            throw new Error(
                                `HTTP error! status: ${response.status}. ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Reset button state
                        deleteButton.innerHTML = originalText;
                        deleteButton.disabled = false;
                        if (data && typeof data === 'object' && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Transaksi berhasil dihapus!',
                                timer: 2000,
                                showConfirmButton: false,
                                background: '#f8f9fa',
                                backdrop: 'rgba(0,0,0,0.1)'
                            }).then(() => {
                                $('#deleteModal').modal(
                                'hide'); // Modal delete tetap bisa pakai jQuery
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menghapus',
                                text: data?.message || 'Gagal menghapus transaksi',
                                background: '#f8f9fa',
                                backdrop: 'rgba(0,0,0,0.1)'
                            });
                        }
                    })
                    .catch(error => {
                        // Reset button state
                        deleteButton.innerHTML = originalText;
                        deleteButton.disabled = false;
                        console.error('Fetch Error Details (Delete):', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan jaringan atau server saat menghapus transaksi',
                            background: '#f8f9fa',
                            backdrop: 'rgba(0,0,0,0.1)'
                        });
                    });
            });

            // Reset modal forms when hidden (gunakan event listener DOM)
            tambahPemasukanModalEl.addEventListener('hidden.bs.modal', function() {
                const form = this.querySelector('form');
                if (form) {
                    form.reset();
                    // Reset tanggal ke hari ini
                    const dateInput = this.querySelector('input[type="date"]');
                    if (dateInput) {
                        dateInput.value = new Date().toISOString().split('T')[0];
                    }
                }
            });

            tambahPengeluaranModalEl.addEventListener('hidden.bs.modal', function() {
                const form = this.querySelector('form');
                if (form) {
                    form.reset();
                    // Reset tanggal ke hari ini
                    const dateInput = this.querySelector('input[type="date"]');
                    if (dateInput) {
                        dateInput.value = new Date().toISOString().split('T')[0];
                    }
                }
            });

            editModalEl.addEventListener('hidden.bs.modal', function() {
                const form = document.getElementById('formEdit');
                if (form) {
                    form.reset();
                }
            });
        });
    </script>
@endpush
