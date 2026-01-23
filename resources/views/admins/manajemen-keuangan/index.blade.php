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
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0"
                                placeholder="Cari transaksi...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="filterBulan" class="form-select">
                            <option value="">Pilih Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filterTahun" class="form-select">
                            <option value="">Pilih Tahun</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="all">
                                    Semua Transaksi
                                </button>
                                <button class="btn btn-sm btn-outline-success filter-btn" data-filter="pemasukan">
                                    <i class="fas fa-donate me-1"></i>Pemasukan
                                </button>
                                <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="pengeluaran">
                                    <i class="fas fa-shopping-cart me-1"></i>Pengeluaran
                                </button>
                            </div>
                            <div class="text-muted small">
                                Menampilkan <span id="totalRecords">25</span> transaksi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row fade-in-up">
        <div class="col-12">
            <div class="glass-effect p-4">
                <div class="table-responsive">
                    <table class="table table-hover" id="transactionsTable">
                        <thead>
                            <tr class="table-light">
                                <th class="fw-semibold text-dark">Tanggal</th>
                                <th class="fw-semibold text-dark">Uraian</th>
                                <th class="fw-semibold text-dark text-end">Pemasukan</th>
                                <th class="fw-semibold text-dark text-end">Pengeluaran</th>
                                <th class="fw-semibold text-dark text-end">Saldo</th>
                                <th class="fw-semibold text-dark">Keterangan</th>
                                <th class="fw-semibold text-dark text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 10; $i++)
                                @php
                                    $isIncome = $i % 3 != 0;
                                    $amount = $isIncome ? rand(100000, 5000000) : rand(50000, 3000000);
                                    $balance = 25450000 + ($isIncome ? $amount : -$amount);
                                @endphp
                                <tr class="{{ $isIncome ? 'table-success-light' : 'table-warning-light' }}">
                                    <td class="text-nowrap">{{ date('d/m/Y', strtotime("-$i days")) }}</td>
                                    <td>
                                        <div class="fw-medium">
                                            {{ $isIncome ? 'Donatur Rutin Bulanan' : 'Pembelian Perlengkapan' }}</div>
                                        <div class="text-muted small">{{ $isIncome ? 'Donasi' : 'Operasional' }}</div>
                                    </td>
                                    <td class="text-end">
                                        @if ($isIncome)
                                            <span class="text-success fw-bold">Rp
                                                {{ number_format($amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if (!$isIncome)
                                            <span class="text-warning fw-bold">Rp
                                                {{ number_format($amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold">Rp {{ number_format($balance, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $isIncome ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                            {{ $isIncome ? 'Donasi' : 'Operasional' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button class="btn btn-sm btn-outline-primary edit-btn"
                                                data-id="{{ $i }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn"
                                                data-id="{{ $i }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Menampilkan 1 sampai 10 dari 25 transaksi
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
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
                        <div class="mb-3">
                            <label for="tanggalPemasukan" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggalPemasukan" required>
                        </div>
                        <div class="mb-3">
                            <label for="uraianPemasukan" class="form-label">Uraian</label>
                            <input type="text" class="form-control" id="uraianPemasukan"
                                placeholder="Contoh: Donatur Rutin" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlahPemasukan" class="form-label">Jumlah Pemasukan</label>
                            <input type="number" class="form-control" id="jumlahPemasukan" placeholder="0"
                                min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="keteranganPemasukan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keteranganPemasukan" rows="3"
                                placeholder="Tambahkan keterangan (opsional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" id="kategoriPemasukan">
                                <option value="donasi">Donasi</option>
                                <option value="infak">Infak</option>
                                <option value="zakat">Zakat</option>
                                <option value="sedekah">Sedekah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
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
                        <div class="mb-3">
                            <label for="tanggalPengeluaran" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggalPengeluaran" required>
                        </div>
                        <div class="mb-3">
                            <label for="uraianPengeluaran" class="form-label">Uraian</label>
                            <input type="text" class="form-control" id="uraianPengeluaran"
                                placeholder="Contoh: Pembelian Perlengkapan" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlahPengeluaran" class="form-label">Jumlah Pengeluaran</label>
                            <input type="number" class="form-control" id="jumlahPengeluaran" placeholder="0"
                                min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="keteranganPengeluaran" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keteranganPengeluaran" rows="3"
                                placeholder="Tambahkan keterangan (opsional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" id="kategoriPengeluaran">
                                <option value="operasional">Operasional</option>
                                <option value="listrik">Listrik & Air</option>
                                <option value="kebersihan">Kebersihan</option>
                                <option value="perawatan">Perawatan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning" id="simpanPengeluaran">
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
                        <div class="mb-3">
                            <label for="editTanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editTanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUraian" class="form-label">Uraian</label>
                            <input type="text" class="form-control" id="editUraian" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editPemasukan" class="form-label">Pemasukan</label>
                                <input type="number" class="form-control" id="editPemasukan" placeholder="0"
                                    min="0">
                            </div>
                            <div class="col-md-6">
                                <label for="editPengeluaran" class="form-label">Pengeluaran</label>
                                <input type="number" class="form-control" id="editPengeluaran" placeholder="0"
                                    min="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editKeterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="editKeterangan" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Transaksi</label>
                            <select class="form-select" id="editJenis">
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
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

        .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date inputs with today's date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggalPemasukan').value = today;
            document.getElementById('tanggalPengeluaran').value = today;
            document.getElementById('editTanggal').value = today;

            // Set current year and month in filters
            const currentDate = new Date();
            document.getElementById('filterBulan').value = (currentDate.getMonth() + 1).toString().padStart(2, '0');
            document.getElementById('filterTahun').value = currentDate.getFullYear().toString();

            // Filter functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('searchInput');
            const filterBulan = document.getElementById('filterBulan');
            const filterTahun = document.getElementById('filterTahun');
            const tableRows = document.querySelectorAll('#transactionsTable tbody tr');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedMonth = filterBulan.value;
                const selectedYear = filterTahun.value;
                const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;

                let visibleCount = 0;

                tableRows.forEach(row => {
                    const dateCell = row.cells[0].textContent;
                    const [day, month, year] = dateCell.split('/');
                    const uraian = row.cells[1].textContent.toLowerCase();
                    const isIncome = row.cells[2].textContent !== '-';

                    // Check filter type
                    let passesFilter = true;
                    if (activeFilter === 'pemasukan' && !isIncome) passesFilter = false;
                    if (activeFilter === 'pengeluaran' && isIncome) passesFilter = false;

                    // Check month and year filter
                    if (selectedMonth && month !== selectedMonth) passesFilter = false;
                    if (selectedYear && year !== selectedYear) passesFilter = false;

                    // Check search term
                    if (searchTerm && !uraian.includes(searchTerm)) passesFilter = false;

                    // Show/hide row
                    if (passesFilter) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update count
                document.getElementById('totalRecords').textContent = visibleCount;
            }

            // Event listeners for filters
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    filterTable();
                });
            });

            searchInput.addEventListener('input', filterTable);
            filterBulan.addEventListener('change', filterTable);
            filterTahun.addEventListener('change', filterTable);

            // Modal handling
            document.getElementById('simpanPemasukan').addEventListener('click', function() {
                const form = document.getElementById('formPemasukan');
                if (form.checkValidity()) {
                    // Here you would typically submit to server
                    alert('Pemasukan berhasil disimpan!');
                    $('#tambahPemasukanModal').modal('hide');
                    form.reset();
                    document.getElementById('tanggalPemasukan').value = today;
                } else {
                    form.reportValidity();
                }
            });

            document.getElementById('simpanPengeluaran').addEventListener('click', function() {
                const form = document.getElementById('formPengeluaran');
                if (form.checkValidity()) {
                    // Here you would typically submit to server
                    alert('Pengeluaran berhasil disimpan!');
                    $('#tambahPengeluaranModal').modal('hide');
                    form.reset();
                    document.getElementById('tanggalPengeluaran').value = today;
                } else {
                    form.reportValidity();
                }
            });

            // Edit button functionality
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    // In a real application, you would fetch the data from server
                    // For now, we'll populate with sample data
                    document.getElementById('editUraian').value = 'Donatur Rutin Bulanan';
                    document.getElementById('editPemasukan').value = '2500000';
                    document.getElementById('editPengeluaran').value = '';
                    document.getElementById('editKeterangan').value = 'Donasi rutin dari jamaah';
                    document.getElementById('editJenis').value = 'pemasukan';

                    $('#editModal').modal('show');
                });
            });

            document.getElementById('simpanEdit').addEventListener('click', function() {
                alert('Perubahan berhasil disimpan!');
                $('#editModal').modal('hide');
            });

            // Delete button functionality
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                        // Here you would typically delete from server
                        alert('Transaksi berhasil dihapus!');
                    }
                });
            });

            // Format currency input
            const currencyInputs = document.querySelectorAll('input[type="number"]');
            currencyInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value) {
                        this.value = parseInt(this.value).toLocaleString('id-ID');
                    }
                });

                input.addEventListener('focus', function() {
                    this.value = this.value.replace(/[^\d]/g, '');
                });
            });

            // Add animation to table
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
@endpush
