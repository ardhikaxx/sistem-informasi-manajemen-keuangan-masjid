@extends('layouts.app')

@section('title', 'Manajemen Laporan - Keuangan Masjid')
@section('page-title', 'Manajemen Laporan')

@section('content')
    <!-- Header Section with Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-effect p-4">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Laporan Keuangan Masjid</h5>
                        <p class="text-muted mb-0">Lihat dan cetak laporan keuangan masjid</p>
                    </div>
                    <button class="btn btn-outline-danger d-block">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-effect p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Laporan</label>
                        <select id="jenisLaporan" class="form-select">
                            <option value="bulanan">Laporan Bulanan</option>
                            <option value="harian">Laporan Harian</option>
                            <option value="mingguan">Laporan Mingguan</option>
                            <option value="tahunan">Laporan Tahunan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bulan</label>
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
                    <div class="col-md-4">
                        <label class="form-label">Tahun</label>
                        <select id="filterTahun" class="form-select">
                            <option value="">Pilih Tahun</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Preview -->
    <div class="row fade-in-up" id="reportPreview">
        <div class="col-12">
            <div class="glass-effect p-4">
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
                            @for ($i = 1; $i <= 8; $i++)
                                @php
                                    $isIncome = $i % 3 != 0;
                                    $amount = $isIncome ? rand(100000, 5000000) : rand(50000, 3000000);
                                    $balance = 25450000 + ($isIncome ? $amount : -$amount);
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td class="text-nowrap">{{ date('d/m/Y', strtotime("-$i days")) }}</td>
                                    <td>
                                        <div class="fw-medium">
                                            {{ $isIncome ? 'Donatur Rutin Bulanan' : 'Pembelian Perlengkapan' }}</div>
                                    </td>
                                    <td class="text-end">
                                        @if ($isIncome)
                                            <span class="text-success">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if (!$isIncome)
                                            <span class="text-warning">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($balance, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $isIncome ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                            {{ $isIncome ? 'Donasi' : 'Operasional' }}
                                        </span>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">TOTAL</th>
                                <th class="text-end text-success">Rp 12.750.000</th>
                                <th class="text-end text-warning">Rp 8.325.000</th>
                                <th class="text-end text-primary">Rp 25.450.000</th>
                                <th></th>
                            </tr>
                        </tfoot>
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
            border: 2px solid var(--danger-color);
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
            // Initialize date inputs
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDate').value = today;
            document.getElementById('endDate').value = today;

            // Set current year and month in filters
            const currentDate = new Date();
            document.getElementById('filterBulan').value = (currentDate.getMonth() + 1).toString().padStart(2, '0');
            document.getElementById('filterTahun').value = currentDate.getFullYear().toString();

            // Update report period text
            updateReportPeriodText();

            // Chart functionality
            const months = ['Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'];
            const incomeData = [9500000, 11000000, 12500000, 10500000, 12750000, 13250000];
            const expenseData = [6200000, 7500000, 8200000, 7800000, 8325000, 8450000];

            // Get colors from CSS variables
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color')
                .trim();
            const successColor = getComputedStyle(document.documentElement).getPropertyValue('--success-color')
                .trim();
            const warningColor = getComputedStyle(document.documentElement).getPropertyValue('--warning-color')
                .trim();

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    filterReportTable();
                });
            });

            // Update report period text
            function updateReportPeriodText() {
                const jenis = document.getElementById('jenisLaporan').value;
                const bulan = document.getElementById('filterBulan');
                const tahun = document.getElementById('filterTahun');
                const startDate = document.getElementById('startDate');
                const endDate = document.getElementById('endDate');

                let periodText = '';

                if (jenis === 'custom') {
                    if (startDate.value && endDate.value) {
                        const start = new Date(startDate.value);
                        const end = new Date(endDate.value);
                        periodText = `Periode: ${formatDate(start)} - ${formatDate(end)}`;
                    }
                } else if (bulan.value && tahun.value) {
                    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                    periodText = `Periode: ${monthNames[parseInt(bulan.value) - 1]} ${tahun.value}`;
                } else {
                    periodText =
                        `Periode: ${new Date().toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })}`;
                }

                document.getElementById('reportPeriodText').textContent = periodText;
            }

            function formatDate(date) {
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }

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
        });
    </script>
@endpush
