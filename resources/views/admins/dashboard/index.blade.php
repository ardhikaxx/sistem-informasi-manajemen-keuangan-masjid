@extends('layouts.app')

@section('title', 'Dashboard Admin - Keuangan Masjid')
@section('page-title', 'Dashboard')

@section('content')
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Saldo Card -->
        <div class="col-12 fade-in-up">
            <div class="glass-effect p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Saldo Saat Ini</h6>
                        <h3 class="fw-bold text-primary mb-2">Rp {{ number_format($saldoSaatIni, 0, ',', '.') }}</h3>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-{{ $arahPemasukan == 'naik' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $arahPemasukan == 'naik' ? 'success' : 'danger' }} me-2">
                                <i class="fas fa-arrow-{{ $arahPemasukan == 'naik' ? 'up' : 'down' }} me-1"></i>{{ round($perubahanPemasukan, 1) }}%
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

        <!-- Pemasukan Card -->
        <div class="col-md-6 fade-in-up" style="animation-delay: 0.1s;">
            <div class="glass-effect p-4 text-center h-100">
                <h6 class="text-muted mb-2">Pemasukan Bulan Ini</h6>
                <h4 class="text-success fw-bold">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h4>
            </div>
        </div>

        <!-- Pengeluaran Card -->
        <div class="col-md-6 fade-in-up" style="animation-delay: 0.15s;">
            <div class="glass-effect p-4 text-center h-100">
                <h6 class="text-muted mb-2">Pengeluaran Bulan Ini</h6>
                <h4 class="text-danger fw-bold">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row mb-4">
        <div class="col-12 fade-in-up" style="animation-delay: 0.2s;">
            <div class="glass-effect p-4">
                <!-- Chart Header -->
                <div class="d-flex flex-column flex-md-row justify-content-md-between gap-2 align-items-md-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Grafik Keuangan</h5>
                        <p class="text-muted mb-0">Pemasukan dan pengeluaran bulanan</p>
                    </div>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="chartType" id="barChart" value="bar" checked>
                        <label class="btn btn-outline-primary btn-sm" for="barChart">
                            <i class="fas fa-chart-bar me-1"></i>Bar
                        </label>
                        
                        <input type="radio" class="btn-check" name="chartType" id="lineChart" value="trend">
                        <label class="btn btn-outline-primary btn-sm" for="lineChart">
                            <i class="fas fa-chart-line me-1"></i>Tren
                        </label>
                    </div>
                </div>

                <!-- Chart Canvas -->
                <div style="position: relative; height: 300px;">
                    <canvas id="mainChart"></canvas>
                </div>

                <!-- Summary -->
                <div class="mt-4 row text-center g-3">
                    <div class="col-md-6">
                        <div class="p-3">
                            <h6 class="text-muted mb-2">Total Pemasukan Periode</h6>
                            <h4 class="fw-bold text-success mb-0 total-income">Rp {{ number_format($totalPemasukanGrafik, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3">
                            <h6 class="text-muted mb-2">Total Pengeluaran Periode</h6>
                            <h4 class="fw-bold text-danger mb-0 total-expense">Rp {{ number_format($totalPengeluaranGrafik, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="row g-3 mb-4">
        <!-- Aliran Kas Distribution -->
        <div class="col-md-6 fade-in-up" style="animation-delay: 0.3s;">
            <div class="glass-effect p-4 h-100">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>
                    Distribusi Aliran Kas
                </h5>
                
                <!-- Pie Chart -->
                <div style="position: relative; height: 220px;" class="mb-3">
                    <canvas id="aliranChart"></canvas>
                </div>

                <!-- Legend -->
                <div id="aliranLegend">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>

        <!-- Top Transaksi -->
        <div class="col-md-6 fade-in-up" style="animation-delay: 0.35s;">
            <div class="glass-effect p-4 h-100">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fas fa-trophy me-2 text-warning"></i>
                    Top 5 Uraian Bulan Ini
                </h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold text-dark">#</th>
                                <th class="fw-semibold text-dark">Uraian</th>
                                <th class="fw-semibold text-dark text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topTransaksi as $index => $top)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $index < 3 ? 'primary' : 'secondary' }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($top->uraian, 35) }}</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($top->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
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

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 fade-in-up" style="animation-delay: 0.4s;">
            <div class="glass-effect p-4 text-center h-100">
                <h6 class="text-muted mb-2">Rata-rata Pemasukan</h6>
                <h4 class="text-success fw-bold">Rp {{ number_format($rataPemasukan, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4 fade-in-up" style="animation-delay: 0.45s;">
            <div class="glass-effect p-4 text-center h-100">
                <h6 class="text-muted mb-2">Rata-rata Pengeluaran</h6>
                <h4 class="text-danger fw-bold">Rp {{ number_format($rataPengeluaran, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-4 fade-in-up" style="animation-delay: 0.5s;">
            <div class="glass-effect p-4 text-center h-100">
                <h6 class="text-muted mb-2">Selisih Bersih</h6>
                <h4 class="fw-bold {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'text-success' : 'text-danger' }}">
                    Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
                </h4>
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
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            @endif
        });

        // Chart initialization
        document.addEventListener('DOMContentLoaded', function() {
            const initialData = @json($dataGrafik);
            const labels = initialData.map(d => d.label);
            const incomeData = initialData.map(d => d.pemasukan);
            const expenseData = initialData.map(d => d.pengeluaran);

            function formatCurrency(value) {
                return 'Rp ' + value.toLocaleString('id-ID');
            }

            // Main Chart
            const ctx = document.getElementById('mainChart').getContext('2d');
            let chartType = 'bar';
            
            const mainChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Pemasukan',
                            data: incomeData,
                            backgroundColor: 'rgba(40, 167, 69, 0.8)',
                            borderRadius: 4,
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenseData,
                            backgroundColor: 'rgba(220, 53, 69, 0.8)',
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 11 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + formatCurrency(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + 'jt';
                                    if (value >= 1000) return (value / 1000) + 'rb';
                                    return value;
                                },
                                font: { size: 10 }
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // Chart type toggle
            document.querySelectorAll('input[name="chartType"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    chartType = this.value;
                    const periode = 6;
                    updateChart(periode, chartType);
                });
            });

            function updateChart(periode, type) {
                fetch(`/admin/dashboard/chart-data?periode=${periode}&type=${type}`)
                    .then(response => response.json())
                    .then(data => {
                        mainChart.data.labels = data.labels;
                        
                        if (type === 'trend') {
                            mainChart.config.type = 'line';
                            mainChart.data.datasets = data.datasets.map(ds => ({
                                ...ds,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointHoverRadius: 5
                            }));
                        } else {
                            mainChart.config.type = 'bar';
                            mainChart.data.datasets = data.datasets.map(ds => ({
                                ...ds,
                                borderRadius: 4
                            }));
                        }
                        
                        mainChart.update();
                    });
            }

            // Pie Chart for Aliran Kas
            const pieData = @json($dataPieAliran);
            const pieCtx = document.getElementById('aliranChart').getContext('2d');
            
            const aliranChart = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: pieData.labels.map(l => l.replace('Aktivitas ', '')),
                    datasets: [{
                        data: pieData.values,
                        backgroundColor: pieData.backgroundColor,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label;
                                    const value = formatCurrency(context.raw);
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? ((context.raw / total) * 100).toFixed(1) + '%' : '0%';
                                    return `${label}: ${value} (${pct})`;
                                }
                            }
                        }
                    }
                }
            });

            // Populate legend
            const legendContainer = document.getElementById('aliranLegend');
            if (legendContainer) {
                const total = pieData.values.reduce((a, b) => a + b, 0);
                pieData.labels.forEach((label, index) => {
                    const pct = total > 0 ? ((pieData.values[index] / total) * 100).toFixed(1) : 0;
                    const item = document.createElement('div');
                    item.className = 'd-flex align-items-center mb-2';
                    item.innerHTML = `
                        <div style="width: 10px; height: 10px; background: ${pieData.backgroundColor[index]}; border-radius: 2px; margin-right: 8px;"></div>
                        <span class="text-muted flex-grow-1">${label.replace('Aktivitas ', '')}</span>
                        <span class="fw-semibold">${pct}%</span>
                    `;
                    legendContainer.appendChild(item);
                });
            }

            // Animate cards
            document.querySelectorAll('.fade-in-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(15px)';
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100);
            });
        });
    </script>
@endpush
