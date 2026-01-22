@extends('layouts.app')

@section('title', 'Dashboard Admin - Keuangan Masjid')
@section('page-title', 'Dashboard')

@section('content')

<div class="row g-4">
    <!-- Stats Cards -->
    <div class="col-xl-4 col-md-6 fade-in-up">
        <div class="glass-effect p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted mb-2">Saldo Saat Ini</h6>
                    <h2 class="fw-bold text-primary">Rp 25.450.000</h2>
                    <div class="d-flex align-items-center mt-3">
                        <span class="badge bg-success bg-opacity-10 text-success me-2">
                            <i class="fas fa-arrow-up me-1"></i>12.5%
                        </span>
                        <span class="text-muted small">Dari bulan lalu</span>
                    </div>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="fas fa-wallet fa-2x text-primary"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">85% dari target bulanan</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
        <div class="glass-effect p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted mb-2">Total Pemasukan</h6>
                    <h2 class="fw-bold text-success">Rp 12.750.000</h2>
                    <div class="d-flex align-items-center mt-3">
                        <span class="badge bg-success bg-opacity-10 text-success me-2">
                            <i class="fas fa-arrow-up me-1"></i>8.2%
                        </span>
                        <span class="text-muted small">Dari bulan lalu</span>
                    </div>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="fas fa-donate fa-2x text-success"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">70% dari target pemasukan</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
        <div class="glass-effect p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                    <h2 class="fw-bold text-warning">Rp 8.325.000</h2>
                    <div class="d-flex align-items-center mt-3">
                        <span class="badge bg-warning bg-opacity-10 text-warning me-2">
                            <i class="fas fa-arrow-down me-1"></i>5.3%
                        </span>
                        <span class="text-muted small">Dari bulan lalu</span>
                    </div>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="fas fa-shopping-cart fa-2x text-warning"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">65% dari anggaran bulanan</small>
            </div>
        </div>
    </div>
    
    <!-- Chart Section -->
    <div class="col-12 mt-4 fade-in-up" style="animation-delay: 0.3s;">
        <div class="glass-effect p-4">
            <div class="d-flex flex-column justify-content-md-between gap-2 align-items-lg-center align-items-start mb-4">
                <div>
                    <h5 class="fw-bold text-dark">Statistik Keuangan Bulanan</h5>
                    <p class="text-muted mb-0">Grafik pemasukan dan pengeluaran selama 6 bulan terakhir</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="chartPeriodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
            
            <div class="row mt-4 text-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="rounded-circle p-2 me-3">
                            <i class="fas fa-circle text-success"></i>
                        </div>
                        <div class="text-start">
                            <h6 class="mb-0">Total Pemasukan 6 Bulan</h6>
                            <h4 class="fw-bold text-success mb-0">Rp 68.450.000</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="rounded-circl p-2 me-3">
                            <i class="fas fa-circle text-warning"></i>
                        </div>
                        <div class="text-start">
                            <h6 class="mb-0">Total Pengeluaran 6 Bulan</h6>
                            <h4 class="fw-bold text-warning mb-0">Rp 42.125.000</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Data
        const months = ['Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'];
        const incomeData = [9500000, 11000000, 12500000, 10500000, 12750000, 13250000];
        const expenseData = [6200000, 7500000, 8200000, 7800000, 8325000, 8450000];
        
        // Chart Colors from CSS variables
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim();
        const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--accent-color').trim();
        const warningColor = getComputedStyle(document.documentElement).getPropertyValue('--warning-color').trim();
        
        // Format currency
        function formatCurrency(value) {
            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Initialize Chart
        const ctx = document.getElementById('financialChart').getContext('2d');
        const financialChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: incomeData,
                        backgroundColor: primaryColor,
                        borderColor: primaryColor,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenseData,
                        backgroundColor: warningColor,
                        borderColor: warningColor,
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
                document.getElementById('chartPeriodDropdown').innerHTML = `<i class="fas fa-calendar-alt me-2"></i>${periodText}`;
                
                // In a real application, you would fetch new data based on the period
                // For this example, we'll just update the chart with dummy data
                const period = parseInt(this.getAttribute('data-period'));
                let newMonths, newIncomeData, newExpenseData;
                
                if (period === 3) {
                    newMonths = ['Agu', 'Sep', 'Okt'];
                    newIncomeData = [10500000, 12750000, 13250000];
                    newExpenseData = [7800000, 8325000, 8450000];
                } else if (period === 6) {
                    newMonths = ['Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'];
                    newIncomeData = [9500000, 11000000, 12500000, 10500000, 12750000, 13250000];
                    newExpenseData = [6200000, 7500000, 8200000, 7800000, 8325000, 8450000];
                } else if (period === 12) {
                    newMonths = ['Nov', 'Des', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'];
                    newIncomeData = [8200000, 9500000, 8900000, 9200000, 10100000, 9700000, 9500000, 11000000, 12500000, 10500000, 12750000, 13250000];
                    newExpenseData = [5200000, 6200000, 5800000, 6100000, 7200000, 6800000, 6200000, 7500000, 8200000, 7800000, 8325000, 8450000];
                }
                
                // Update chart
                financialChart.data.labels = newMonths;
                financialChart.data.datasets[0].data = newIncomeData;
                financialChart.data.datasets[1].data = newExpenseData;
                financialChart.update();
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
@endsection