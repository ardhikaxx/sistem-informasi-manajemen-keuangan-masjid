@extends('layouts.app')

@section('title', 'Manajemen Laporan - Keuangan Masjid')
@section('page-title', 'Manajemen Laporan')

@section('content')
    <!-- Header Section with Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-effect p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Laporan Keuangan Masjid</h5>
                        <p class="text-muted mb-0">Lihat dan cetak laporan keuangan masjid</p>
                    </div>
                    <!-- Tombol Export PDF -->
                    <form action="" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="jenis_laporan" value="{{ old('jenis_laporan', $jenisLaporan) }}">
                        <input type="hidden" name="bulan" value="{{ old('bulan', $bulan) }}">
                        <input type="hidden" name="tahun" value="{{ old('tahun', $tahun) }}">
                        <input type="hidden" name="start_date" value="{{ old('start_date', $startDate) }}">
                        <input type="hidden" name="end_date" value="{{ old('end_date', $endDate) }}">
                        <button type="submit" class="btn btn-outline-danger d-block">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-effect p-4">
                <form method="GET" action="{{ route('admins.manajemen-laporan') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="jenis_laporan" class="form-label">Jenis Laporan</label>
                            <select name="jenis_laporan" id="jenisLaporan" class="form-select">
                                <option value="bulanan" {{ old('jenis_laporan', $jenisLaporan) == 'bulanan' ? 'selected' : '' }}>Laporan Bulanan</option>
                                <option value="harian" {{ old('jenis_laporan', $jenisLaporan) == 'harian' ? 'selected' : '' }}>Laporan Harian</option>
                                <option value="mingguan" {{ old('jenis_laporan', $jenisLaporan) == 'mingguan' ? 'selected' : '' }}>Laporan Mingguan</option>
                                <option value="tahunan" {{ old('jenis_laporan', $jenisLaporan) == 'tahunan' ? 'selected' : '' }}>Laporan Tahunan</option>
                                <option value="custom" {{ old('jenis_laporan', $jenisLaporan) == 'custom' ? 'selected' : '' }}>Periode Custom</option>
                            </select>
                        </div>
                        <!-- Field Bulan: Ditampilkan jika jenis_laporan adalah bulanan atau custom -->
                        <div class="col-md-4" id="bulanFilterGroup" style="{{ in_array(old('jenis_laporan', $jenisLaporan), ['harian', 'mingguan', 'tahunan']) ? 'display:none;' : '' }}">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="filterBulan" class="form-select">
                                <option value="">Pilih Bulan</option>
                                <option value="01" {{ old('bulan', $bulan) == '01' ? 'selected' : '' }}>Januari</option>
                                <option value="02" {{ old('bulan', $bulan) == '02' ? 'selected' : '' }}>Februari</option>
                                <option value="03" {{ old('bulan', $bulan) == '03' ? 'selected' : '' }}>Maret</option>
                                <option value="04" {{ old('bulan', $bulan) == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ old('bulan', $bulan) == '05' ? 'selected' : '' }}>Mei</option>
                                <option value="06" {{ old('bulan', $bulan) == '06' ? 'selected' : '' }}>Juni</option>
                                <option value="07" {{ old('bulan', $bulan) == '07' ? 'selected' : '' }}>Juli</option>
                                <option value="08" {{ old('bulan', $bulan) == '08' ? 'selected' : '' }}>Agustus</option>
                                <option value="09" {{ old('bulan', $bulan) == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ old('bulan', $bulan) == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ old('bulan', $bulan) == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ old('bulan', $bulan) == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="tahunFilterGroup">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="filterTahun" class="form-select">
                                <option value="">Pilih Tahun</option>
                                @foreach($tahunList as $t)
                                    <option value="{{ $t }}" {{ old('tahun', $tahun) == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                                <!-- Opsi fallback jika tahun saat ini belum ada di database -->
                                @php $currentYear = \Carbon\Carbon::now()->year; @endphp
                                @if (!in_array($currentYear, $tahunList->toArray()))
                                    <option value="{{ $currentYear }}" {{ old('tahun', $tahun) == $currentYear ? 'selected' : '' }}>{{ $currentYear }}</option>
                                @endif
                            </select>
                        </div>
                        <!-- Field Tanggal Custom: Ditampilkan jika jenis_laporan adalah custom -->
                        <div class="col-md-4" id="customDateGroup" style="{{ old('jenis_laporan', $jenisLaporan) !== 'custom' ? 'display:none;' : '' }}">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="startDate" class="form-select" value="{{ old('start_date', $startDate) }}">
                        </div>
                        <div class="col-md-4" id="customEndDateGroup" style="{{ old('jenis_laporan', $jenisLaporan) !== 'custom' ? 'display:none;' : '' }}">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="endDate" class="form-select" value="{{ old('end_date', $endDate) }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Terapkan Filter
                        </button>
                        <!-- Tombol Reset -->
                        <a href="{{ route('admins.manajemen-laporan') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
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
                <h4 class="text-danger fw-bold">Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-effect p-4 text-center">
                <h6 class="text-muted mb-2">Saldo Akhir</h6>
                <h4 class="text-primary fw-bold">Rp {{ number_format($summary['saldo_akhir_periode'], 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="row fade-in-up mb-5">
        <div class="col-12">
            <div class="glass-effect p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Detail Transaksi ({{ $periodeLabel }})</h6>
                    <small class="text-muted">{{ $transaksis->total() }} transaksi ditemukan</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="reportTable">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold text-dark">No</th>
                                <th class="fw-semibold text-dark">Tanggal</th>
                                <th class="fw-semibold text-dark">Uraian</th>
                                <th class="fw-semibold text-dark text-end">Pemasukan</th>
                                <th class="fw-semibold text-dark text-end">Pengeluaran</th>
                                <th class="fw-semibold text-dark text-end">Saldo</th>
                                <th class="fw-semibold text-dark">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $index => $transaksi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-nowrap">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="fw-medium">{{ $transaksi->uraian }}</div>
                                    </td>
                                    <td class="text-end">
                                        @if ($transaksi->jenis_transaksi === 'pemasukan')
                                            <span class="text-success">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($transaksi->jenis_transaksi === 'pengeluaran')
                                            <span class="text-danger">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($transaksi->saldo_sesudah, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge 
                                            {{ $transaksi->jenis_transaksi === 'pemasukan' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-danger' }}">
                                            {{ $transaksi->keterangan }}
                                        </span>
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
                        @if($transaksis->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">TOTAL</th>
                                <th class="text-end text-success">Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</th>
                                <th class="text-end text-danger">Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</th>
                                <th class="text-end text-primary">Rp {{ number_format($summary['saldo_akhir_periode'], 0, ',', '.') }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if ($transaksis->hasPages())
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-4">
            <!-- Informasi Halaman -->
            <div class="text-muted small text-center text-lg-start">
                Menampilkan {{ $transaksis->firstItem() ?? 0 }} sampai {{ $transaksis->lastItem() ?? 0 }} dari
                {{ $transaksis->total() }} transaksi
            </div>
            <!-- Link Paginasi -->
            <nav aria-label="Navigasi halaman laporan keuangan">
                <ul class="pagination custom-pagination mb-0">
                    {{-- Tombol Sebelumnya --}}
                    @if ($transaksis->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="Sebelumnya">
                            <span class="page-link" aria-hidden="true">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $transaksis->previousPageUrl() }}" rel="prev"
                                aria-label="Sebelumnya">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif
                    {{-- Link Halaman --}}
                    @php
                        $start = max($transaksis->currentPage() - 1, 1);
                        $end = min($transaksis->currentPage() + 1, $transaksis->lastPage());
                        if ($start == 1) {
                            $end = min(3, $transaksis->lastPage());
                        }
                        if ($end == $transaksis->lastPage()) {
                            $start = max($end - 2, 1);
                        }
                    @endphp
                    @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $transaksis->url(1) }}">1</a>
                        </li>
                        @if ($start > 2)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endif
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $transaksis->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $transaksis->url($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endfor
                    @if ($end < $transaksis->lastPage())
                        @if ($end < $transaksis->lastPage() - 1)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $transaksis->url($transaksis->lastPage()) }}">{{ $transaksis->lastPage() }}</a>
                        </li>
                    @endif
                    {{-- Tombol Berikutnya --}}
                    @if ($transaksis->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $transaksis->nextPageUrl() }}" rel="next"
                                aria-label="Berikutnya">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="Berikutnya">
                            <span class="page-link" aria-hidden="true">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif

@endsection

@push('styles')
    <style>
        .glass-modal {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-outline-danger {
            background: linear-gradient(135deg, var(--danger-color), #dc3545);
            border: 2px solid #dc3545;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .signature-space {
            border-bottom: 2px solid #333;
        }

        #reportHeader img {
            max-height: 80px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisLaporanSelect = document.getElementById('jenisLaporan');
            const bulanGroup = document.getElementById('bulanFilterGroup');
            const tahunGroup = document.getElementById('tahunFilterGroup');
            const customDateGroup = document.getElementById('customDateGroup');
            const customEndDateGroup = document.getElementById('customEndDateGroup');

            // Function to show/hide fields based on report type
            function toggleFilterFields() {
                const selectedType = jenisLaporanSelect.value;

                if (selectedType === 'custom') {
                    bulanGroup.style.display = 'none';
                    tahunGroup.style.display = 'block';
                    customDateGroup.style.display = 'block';
                    customEndDateGroup.style.display = 'block';
                } else if (selectedType === 'tahunan') {
                    bulanGroup.style.display = 'none';
                    tahunGroup.style.display = 'block';
                    customDateGroup.style.display = 'none';
                    customEndDateGroup.style.display = 'none';
                } else { // harian, mingguan, bulanan
                    bulanGroup.style.display = 'block';
                    tahunGroup.style.display = 'block';
                    customDateGroup.style.display = 'none';
                    customEndDateGroup.style.display = 'none';
                }
            }

            // Initial call on page load
            toggleFilterFields();

            // Add change listener to select
            jenisLaporanSelect.addEventListener('change', toggleFilterFields);

            // Add animation
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

            // Set today's date as default for custom filter if empty
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const today = new Date().toISOString().split('T')[0];

            if (!startDateInput.value) {
                startDateInput.value = today;
            }
            if (!endDateInput.value) {
                endDateInput.value = today;
            }
        });
    </script>
@endpush