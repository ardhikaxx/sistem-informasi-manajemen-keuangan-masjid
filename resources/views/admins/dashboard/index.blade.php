@extends('layouts.app')

@section('title', 'Dashboard Admin - Keuangan Masjid')
@section('page-title', 'Dashboard')

@section('content')
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="row g-4">
        <!-- Stats Cards -->
        <div class="col-xl-4 col-md-6 fade-in-up">
            <div class="glass-effect p-4 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Saldo Saat Ini</h6>
                        <h2 class="fw-bold text-primary">Rp {{ number_format($saldoSaatIni, 0, ',', '.') }}</h2>
                        <div class="d-flex align-items-center mt-3">
                            <span
                                class="badge bg-{{ $arahPemasukan == 'naik' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $arahPemasukan == 'naik' ? 'success' : 'danger' }} me-2">
                                <i
                                    class="fas fa-arrow-{{ $arahPemasukan == 'naik' ? 'up' : 'down' }} me-1"></i>{{ round($perubahanPemasukan, 1) }}%
                            </span>
                            <span class="text-muted small">Dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-wallet fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
            <div class="glass-effect p-4 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total Pemasukan</h6>
                        <h2 class="fw-bold text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h2>
                        <div class="d-flex align-items-center mt-3">
                            <span
                                class="badge bg-{{ $arahPemasukan == 'naik' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $arahPemasukan == 'naik' ? 'success' : 'danger' }} me-2">
                                <i
                                    class="fas fa-arrow-{{ $arahPemasukan == 'naik' ? 'up' : 'down' }} me-1"></i>{{ round($perubahanPemasukan, 1) }}%
                            </span>
                            <span class="text-muted small">Dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-donate fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
            <div class="glass-effect p-4 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                        <h2 class="fw-bold text-warning">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
                        <div class="d-flex align-items-center mt-3">
                            <span
                                class="badge bg-{{ $arahPengeluaran == 'naik' ? 'warning' : 'success' }} bg-opacity-10 text-{{ $arahPengeluaran == 'naik' ? 'warning' : 'success' }} me-2">
                                <i
                                    class="fas fa-arrow-{{ $arahPengeluaran == 'naik' ? 'up' : 'down' }} me-1"></i>{{ round($perubahanPengeluaran, 1) }}%
                            </span>
                            <span class="text-muted small">Dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-shopping-cart fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="col-12 mt-4 mb-5 fade-in-up" style="animation-delay: 0.3s;">
            <div class="glass-effect p-4">
                <div
                    class="d-flex flex-column flex-md-row justify-content-md-between gap-2 align-items-lg-center align-items-start mb-4">
                    <div>
                        <h5 class="fw-bold text-dark">Statistik Keuangan Bulanan</h5>
                        <p class="text-muted mb-0">Grafik pemasukan dan pengeluaran selama periode terpilih</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle text-white" type="button" id="chartPeriodDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" style="background: var(--primary-color);">
                            <i class="fas fa-calendar-alt me-2"></i>6 Bulan Terakhir
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="chartPeriodDropdown">
                            <li><a class="dropdown-item" href="#" data-period="3">3 Bulan Terakhir</a></li>
                            <li><a class="dropdown-item active" href="#" data-period="6">6 Bulan Terakhir</a></li>
                            <li><a class="dropdown-item" href="#" data-period="12">1 Tahun Terakhir</a></li>
                        </ul>
                    </div>
                </div>

                <div class="chart-container" style="position: relative; height: 350px;">
                    <canvas id="financialChart"></canvas>
                </div>

                <div class="mt-4 flex-column flex-md-row text-center d-flex justify-content-start align-items-center gap-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="rounded-circle p-2 me-3">
                                <i class="fas fa-circle text-success"></i>
                            </div>
                            <div class="text-start">
                                <h6 class="mb-0">Total Pemasukan Periode</h6>
                                <h4 class="fw-bold text-success mb-0">Rp
                                    {{ number_format($totalPemasukanGrafik, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="rounded-circle p-2 me-3">
                                <i class="fas fa-circle text-warning"></i>
                            </div>
                            <div class="text-start">
                                <h6 class="mb-0">Total Pengeluaran Periode</h6>
                                <h4 class="fw-bold text-warning mb-0">Rp
                                    {{ number_format($totalPengeluaranGrafik, 0, ',', '.') }}</h4>
                            </div>
                        </div>
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

        // Chart initialization and functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initial chart data
            const initialData = @json($dataGrafik);
            const labels = initialData.map(d => d.label);
            const incomeData = initialData.map(d => d.pemasukan);
            const expenseData = initialData.map(d => d.pengeluaran);

            // Chart Colors
            const primaryColor = 'rgba(40, 167, 69, 0.7)';
            const warningColor = 'rgba(255, 193, 7, 0.7)';

            // Format currency
            function formatCurrency(value) {
                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Initialize Chart
            const ctx = document.getElementById('financialChart').getContext('2d');
            const financialChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Pemasukan',
                            data: incomeData,
                            backgroundColor: primaryColor,
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenseData,
                            backgroundColor: warningColor,
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
                                font: {
                                    size: 14,
                                    family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                                },
                                padding: 20,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
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
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });

            // Chart period dropdown functionality
            document.querySelectorAll('[data-period]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Update active state
                    document.querySelectorAll('[data-period]').forEach(el => {
                        el.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Update dropdown button text
                    const periodText = this.textContent;
                    document.getElementById('chartPeriodDropdown').innerHTML =
                        `<i class="fas fa-calendar-alt me-2"></i>${periodText}`;

                    // Show loading state
                    const btn = document.getElementById('chartPeriodDropdown');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                    btn.disabled = true;

                    // Fetch new data based on period
                    const period = parseInt(this.getAttribute('data-period'));

                    fetch(`/admin/dashboard/chart-data?periode=${period}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 401 || response.status === 403) {
                                    // Redirect ke login jika tidak autentikasi
                                    window.location.href = '/admin/login';
                                    return;
                                }
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Update chart
                            financialChart.data.labels = data.labels;
                            financialChart.data.datasets[0].data = data.datasets[0].data;
                            financialChart.data.datasets[1].data = data.datasets[1].data;

                            // Update totals display
                            const totalIncome = data.datasets[0].data.reduce((a, b) => a + b,
                            0);
                            const totalExpense = data.datasets[1].data.reduce((a, b) => a + b,
                                0);

                            document.querySelector('.text-success.mb-0').textContent =
                                `Rp ${totalIncome.toLocaleString('id-ID', {maximumFractionDigits: 0})}`;
                            document.querySelector('.text-warning.mb-0').textContent =
                                `Rp ${totalExpense.toLocaleString('id-ID', {maximumFractionDigits: 0})}`;

                            financialChart.update();
                        })
                        .catch(error => {
                            console.error('Error fetching chart data:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal memuat data grafik. Silakan coba lagi.',
                                background: '#f8f9fa',
                                iconColor: '#dc3545'
                            }).then(() => {
                                // Restore button state even on error
                                btn.innerHTML = originalText;
                                btn.disabled = false;
                            });
                        });
                });
            });

            // Add animation to stats cards on scroll
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

            // Observe all fade-in-up elements
            document.querySelectorAll('.fade-in-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
@endpush
