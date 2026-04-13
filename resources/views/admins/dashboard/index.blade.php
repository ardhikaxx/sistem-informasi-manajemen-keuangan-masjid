@extends('layouts.app')

@section('title', 'Dashboard Admin - Keuangan Masjid')
@section('page-title', 'Dashboard')

@section('content')
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Month Comparison Cards (Mobile First) -->
    <div class="row g-3 mb-4">
        <!-- Saldo Card -->
        <div class="col-12 fade-in-up">
            <div class="glass-effect p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-2 small">Saldo Saat Ini</h6>
                        <h3 class="fw-bold text-primary mb-2" style="font-size: 1.5rem;">Rp {{ number_format($saldoSaatIni, 0, ',', '.') }}</h3>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="badge bg-{{ $arahPemasukan == 'naik' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $arahPemasukan == 'naik' ? 'success' : 'danger' }}">
                                <i class="fas fa-arrow-{{ $arahPemasukan == 'naik' ? 'up' : 'down' }} me-1"></i>{{ round($perubahanPemasukan, 1) }}%
                            </span>
                            <span class="text-muted small">Dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 p-md-3">
                        <i class="fas fa-wallet fa-lg fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasukan vs Pengeluaran Comparison -->
        <div class="col-6 fade-in-up" style="animation-delay: 0.1s;">
            <div class="glass-effect p-3 h-100">
                <div class="rounded-circle bg-success bg-opacity-10 p-2 mb-2 d-inline-block">
                    <i class="fas fa-arrow-trend-up text-success"></i>
                </div>
                <h6 class="text-muted mb-1 small">Bulan Ini</h6>
                <h5 class="fw-bold text-success mb-1" style="font-size: 1.1rem;">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h5>
                <p class="text-muted mb-0 small" style="font-size: 0.7rem;">Pemasukan</p>
            </div>
        </div>

        <div class="col-6 fade-in-up" style="animation-delay: 0.15s;">
            <div class="glass-effect p-3 h-100">
                <div class="rounded-circle bg-danger bg-opacity-10 p-2 mb-2 d-inline-block">
                    <i class="fas fa-arrow-trend-down text-danger"></i>
                </div>
                <h6 class="text-muted mb-1 small">Bulan Ini</h6>
                <h5 class="fw-bold text-danger mb-1" style="font-size: 1.1rem;">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h5>
                <p class="text-muted mb-0 small" style="font-size: 0.7rem;">Pengeluaran</p>
            </div>
        </div>

        <!-- Bulan Lalu Comparison -->
        <div class="col-6 fade-in-up" style="animation-delay: 0.2s;">
            <div class="glass-effect p-3 h-100 opacity-75">
                <div class="rounded-circle bg-info bg-opacity-10 p-2 mb-2 d-inline-block">
                    <i class="fas fa-calendar text-info"></i>
                </div>
                <h6 class="text-muted mb-1 small">Bulan Lalu</h6>
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Rp {{ number_format($pemasukanBulanLalu, 0, ',', '.') }}</h5>
                <p class="text-muted mb-0 small" style="font-size: 0.7rem;">Pemasukan</p>
            </div>
        </div>

        <div class="col-6 fade-in-up" style="animation-delay: 0.25s;">
            <div class="glass-effect p-3 h-100 opacity-75">
                <div class="rounded-circle bg-warning bg-opacity-10 p-2 mb-2 d-inline-block">
                    <i class="fas fa-calendar text-warning"></i>
                </div>
                <h6 class="text-muted mb-1 small">Bulan Lalu</h6>
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Rp {{ number_format($pengeluaranBulanLalu, 0, ',', '.') }}</h5>
                <p class="text-muted mb-0 small" style="font-size: 0.7rem;">Pengeluaran</p>
            </div>
        </div>
    </div>

    <!-- Chart Section (Mobile First) -->
    <div class="row mb-4">
        <div class="col-12 fade-in-up" style="animation-delay: 0.3s;">
            <div class="glass-effect p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-md-between gap-2 align-items-md-center mb-3 mb-md-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Statistik Keuangan</h5>
                        <p class="text-muted mb-0 small">Grafik pemasukan dan pengeluaran</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle text-white btn-sm" type="button" id="chartPeriodDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" style="background: var(--primary-color);">
                            <i class="fas fa-calendar-alt me-2"></i>6 Bulan
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="chartPeriodDropdown">
                            <li><a class="dropdown-item" href="#" data-period="3">3 Bulan</a></li>
                            <li><a class="dropdown-item active" href="#" data-period="6">6 Bulan</a></li>
                            <li><a class="dropdown-item" href="#" data-period="12">12 Bulan</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Chart Type Tabs -->
                <ul class="nav nav-pills mb-3 nav-fill" id="chartTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active btn-sm" id="bar-tab" data-bs-toggle="pill" data-chart-type="bar" type="button">
                            <i class="fas fa-chart-bar me-1"></i>Bar
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm" id="trend-tab" data-bs-toggle="pill" data-chart-type="trend" type="button">
                            <i class="fas fa-chart-line me-1"></i>Tren
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm" id="pie-tab" data-bs-toggle="pill" data-chart-type="pie" type="button">
                            <i class="fas fa-chart-pie me-1"></i>Aliran
                        </button>
                    </li>
                </ul>

                <div class="chart-container" style="position: relative; height: 250px;">
                    <canvas id="financialChart"></canvas>
                </div>

                <div class="mt-3 row text-center g-2">
                    <div class="col-6">
                        <div class="p-2">
                            <h6 class="mb-1 small text-muted">Total Pemasukan</h6>
                            <h5 class="fw-bold text-success mb-0 total-income" style="font-size: 0.95rem;">Rp {{ number_format($totalPemasukanGrafik, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2">
                            <h6 class="mb-1 small text-muted">Total Pengeluaran</h6>
                            <h5 class="fw-bold text-warning mb-0 total-expense" style="font-size: 0.95rem;">Rp {{ number_format($totalPengeluaranGrafik, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart Aliran Kas & Top Transaksi -->
    <div class="row g-3 mb-4">
        <!-- Aliran Kas Distribution -->
        <div class="col-12 fade-in-up" style="animation-delay: 0.4s;">
            <div class="glass-effect p-3 p-md-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>
                    Distribusi Aliran Kas Bulan Ini
                </h6>
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="chart-container" style="position: relative; height: 220px;">
                            <canvas id="aliranPieChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="aliran-legend">
                            <!-- Will be populated by JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Transaksi -->
        <div class="col-12 fade-in-up" style="animation-delay: 0.45s;">
            <div class="glass-effect p-3 p-md-4">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-trophy me-2 text-warning"></i>
                    Top 5 Uraian Bulan Ini
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless">
                        <thead>
                            <tr class="text-muted small">
                                <th>#</th>
                                <th>Uraian</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Freq</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topTransaksi as $index => $top)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="fw-medium">{{ Str::limit($top->uraian, 30) }}</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($top->total, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-info">{{ $top->frequency }}x</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Belum ada transaksi bulan ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats (Desktop) -->
    <div class="row g-3 d-none d-md-flex">
        <div class="col-md-4 fade-in-up" style="animation-delay: 0.5s;">
            <div class="glass-effect p-4 h-100">
                <h6 class="text-muted mb-3">Rata-rata Bulanan (6 bulan)</h6>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="text-muted mb-1 small">Pemasukan</p>
                        <h5 class="fw-bold text-success mb-0">Rp {{ number_format($rataPemasukan, 0, ',', '.') }}</h5>
                    </div>
                    <i class="fas fa-chart-line text-success fa-2x opacity-25"></i>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Pengeluaran</p>
                        <h5 class="fw-bold text-warning mb-0">Rp {{ number_format($rataPengeluaran, 0, ',', '.') }}</h5>
                    </div>
                    <i class="fas fa-chart-line text-warning fa-2x opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 fade-in-up" style="animation-delay: 0.55s;">
            <div class="glass-effect p-4 h-100">
                <h6 class="text-muted mb-3">Total Keseluruhan</h6>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="text-muted mb-1 small">Pemasukan</p>
                        <h5 class="fw-bold text-success mb-0">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h5>
                    </div>
                    <i class="fas fa-wallet text-success fa-2x opacity-25"></i>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small">Pengeluaran</p>
                        <h5 class="fw-bold text-warning mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h5>
                    </div>
                    <i class="fas fa-shopping-cart text-warning fa-2x opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 fade-in-up" style="animation-delay: 0.6s;">
            <div class="glass-effect p-4 h-100">
                <h6 class="text-muted mb-3">Selisih Bersih</h6>
                <div class="text-center py-4">
                    <h2 class="fw-bold {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
                    </h2>
                    <p class="text-muted mb-0">Total pemasukan - pengeluaran</p>
                    <div class="mt-3">
                        <span class="badge bg-{{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'success' : 'danger' }} px-3 py-2">
                            <i class="fas fa-{{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'check' : 'exclamation-triangle' }} me-1"></i>
                            {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'Surplus' : 'Defisit' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // SweetAlert Notification
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    background: '#f8f9fa',
                    iconColor: '#28a745',
                    customClass: {
                        title: 'text-success fw-bold',
                        popup: 'shadow-sm'
                    }
                });
            @endif
        });

        // Chart initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Initial chart data
            const initialData = @json($dataGrafik);
            const labels = initialData.map(d => d.label);
            const incomeData = initialData.map(d => d.pemasukan);
            const expenseData = initialData.map(d => d.pengeluaran);

            // Format currency
            function formatCurrency(value) {
                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Initialize Main Chart
            const ctx = document.getElementById('financialChart').getContext('2d');
            let currentChartType = 'bar';
            
            const financialChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Pemasukan',
                            data: incomeData,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenseData,
                            backgroundColor: 'rgba(255, 193, 7, 0.7)',
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: window.innerWidth < 768 ? 11 : 14 },
                                padding: 15,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    label += formatCurrency(context.raw);
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + ' jt';
                                    }
                                    return 'Rp ' + value;
                                },
                                font: { size: window.innerWidth < 768 ? 10 : 12 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: window.innerWidth < 768 ? 10 : 13, weight: '500' }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });

            // Chart period dropdown
            document.querySelectorAll('[data-period]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    document.querySelectorAll('[data-period]').forEach(el => el.classList.remove('active'));
                    this.classList.add('active');

                    const periodText = this.textContent;
                    document.getElementById('chartPeriodDropdown').innerHTML =
                        `<i class="fas fa-calendar-alt me-2"></i>${periodText}`;

                    const period = parseInt(this.getAttribute('data-period'));
                    updateChart(period, currentChartType);
                });
            });

            // Chart type tabs
            document.querySelectorAll('[data-chart-type]').forEach(tab => {
                tab.addEventListener('click', function() {
                    currentChartType = this.getAttribute('data-chart-type');
                    const period = document.querySelector('[data-period].active');
                    const periode = period ? parseInt(period.getAttribute('data-period')) : 6;
                    updateChart(periode, currentChartType);
                });
            });

            // Update chart function
            function updateChart(periode, type) {
                const btn = document.getElementById('chartPeriodDropdown');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                btn.disabled = true;

                fetch(`/admin/dashboard/chart-data?periode=${periode}&type=${type}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        financialChart.data.labels = data.labels;
                        
                        if (type === 'pie') {
                            financialChart.config.type = 'pie';
                            financialChart.data.datasets = [{
                                data: data.values,
                                backgroundColor: data.backgroundColor,
                                borderColor: data.borderColor,
                                borderWidth: 2
                            }];
                        } else if (type === 'trend') {
                            financialChart.config.type = 'line';
                            financialChart.data.datasets = data.datasets;
                        } else {
                            financialChart.config.type = 'bar';
                            financialChart.data.datasets = data.datasets;
                        }

                        const totalIncome = data.datasets ? 
                            data.datasets[0].data.reduce((a, b) => a + b, 0) : 0;
                        const totalExpense = data.datasets ? 
                            (data.datasets[1] ? data.datasets[1].data.reduce((a, b) => a + b, 0) : 0) : 0;

                        document.querySelector('.total-income').textContent = formatCurrency(totalIncome);
                        document.querySelector('.total-expense').textContent = formatCurrency(totalExpense);

                        financialChart.update();
                        btn.innerHTML = `<i class="fas fa-calendar-alt me-2"></i>${periode} Bulan`;
                        btn.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        btn.innerHTML = `<i class="fas fa-calendar-alt me-2"></i>${periode} Bulan`;
                        btn.disabled = false;
                    });
            }

            // Initialize Pie Chart for Aliran Kas
            const pieData = @json($dataPieAliran);
            const pieCtx = document.getElementById('aliranPieChart').getContext('2d');
            
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: pieData.labels.map(label => label.replace('Aktivitas ', '')),
                    datasets: [{
                        data: pieData.values,
                        backgroundColor: pieData.backgroundColor,
                        borderColor: pieData.borderColor,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = formatCurrency(context.raw);
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) + '%' : '0%';
                                    return `${label}: ${value} (${percentage})`;
                                }
                            }
                        }
                    }
                }
            });

            // Populate legend
            const legendContainer = document.querySelector('.aliran-legend');
            if (legendContainer && pieData.labels.length > 0) {
                const total = pieData.values.reduce((a, b) => a + b, 0);
                pieData.labels.forEach((label, index) => {
                    const percentage = total > 0 ? ((pieData.values[index] / total) * 100).toFixed(1) : 0;
                    const legendItem = document.createElement('div');
                    legendItem.className = 'd-flex align-items-center mb-2';
                    legendItem.innerHTML = `
                        <div style="width: 12px; height: 12px; background: ${pieData.backgroundColor[index]}; border-radius: 2px; margin-right: 8px;"></div>
                        <span class="small flex-grow-1">${label.replace('Aktivitas ', '')}</span>
                        <span class="small fw-bold">${percentage}%</span>
                    `;
                    legendContainer.appendChild(legendItem);
                });
            }

            // Animation on scroll
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
