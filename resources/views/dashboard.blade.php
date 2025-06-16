@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-2 me-2">
                            <i class="fas fa-box text-primary"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase">Total Produk</h6>
                            <h4 class="mb-0 fw-bold">{{ $totalProducts }}</h4>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary w-100 btn-xs">
                        <i class="fas fa-arrow-right me-1"></i> Lihat Semua
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-box bg-danger bg-opacity-10 rounded-3 p-2 me-2">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase">Stok Menipis</h6>
                            <h4 class="mb-0 fw-bold">{{ $lowStockProducts }}</h4>
                        </div>
                    </div>
                    <div class="progress mb-1" style="height: 5px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ min(100, ($lowStockProducts / max(1, $totalProducts)) * 100) }}%" aria-valuenow="{{ $lowStockProducts }}" aria-valuemin="0" aria-valuemax="{{ $totalProducts }}"></div>
                    </div>
                    <p class="card-text text-muted small mb-0">Produk dengan stok < 10</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-box bg-success bg-opacity-10 rounded-3 p-2 me-2">
                            <i class="fas fa-cash-register text-success"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase">Penjualan Hari Ini</h6>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-success w-100 btn-xs">
                        <i class="fas fa-chart-line me-1"></i> Lihat Penjualan
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-box bg-warning bg-opacity-10 rounded-3 p-2 me-2">
                            <i class="fas fa-chart-line text-warning"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-0 small text-uppercase">Penjualan Bulan Ini</h6>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($thisMonthSales, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="ms-2">
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-2 small">{{ date('F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Row -->
    <div class="row g-3 mb-3">
        <!-- Sales Chart -->
        <div class="col-md-8 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold small"><i class="fas fa-chart-line me-1 text-primary"></i> Grafik Penjualan</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-xs active" id="weekly-sales">Mingguan</button>
                            <button type="button" class="btn btn-outline-secondary btn-xs" id="monthly-sales">Bulanan</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="salesChart" style="height: 280px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Product Distribution Chart -->
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-2">
                    <h5 class="mb-0 fw-semibold small"><i class="fas fa-chart-pie me-1 text-info"></i> Distribusi Produk</h5>
                </div>
                <div class="card-body">
                    <div id="productDistributionChart" style="height: 280px;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Levels Chart Row -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold small"><i class="fas fa-boxes me-1 text-danger"></i> Level Stok Produk</h5>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary btn-xs">
                            <i class="fas fa-box me-1"></i> Kelola Produk
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="stockLevelsChart" style="height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold small"><i class="fas fa-crown me-1 text-warning"></i> Produk Terlaris</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th class="text-end">Unit Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSellingProducts as $index => $product)
                                    <tr>
                                        <td width="40">
                                            <div class="text-center">
                                                @if($index < 3)
                                                    <span class="badge rounded-circle p-1 {{ $index === 0 ? 'bg-warning' : ($index === 1 ? 'bg-secondary' : 'bg-bronze') }}">
                                                        {{ $index + 1 }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">{{ $index + 1 }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none fw-medium text-dark">
                                                {{ $product->name }}
                                            </a>
                                            @if($index === 0)
                                                <span class="badge bg-warning-subtle text-warning ms-1">Terlaris</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-semibold small">{{ $product->total_sold ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <i class="fas fa-chart-bar fa-2x text-secondary mb-2"></i>
                                            <h6 class="fw-bold">Belum Ada Data</h6>
                                            <p class="text-muted small">Belum ada data penjualan produk</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold small"><i class="fas fa-clock me-1 text-info"></i> Penjualan Terbaru</h5>
                        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary btn-xs">
                            <i class="fas fa-plus me-1"></i> Catat Penjualan
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales as $sale)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark border small">{{ $sale->sale_date->format('d M Y') }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('products.show', $sale->product) }}" class="text-decoration-none fw-medium text-dark small">
                                                {{ $sale->product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary rounded-pill small">{{ $sale->quantity }}</span>
                                        </td>
                                        <td class="text-end fw-bold small">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-receipt fa-2x text-secondary mb-2"></i>
                                            <h6 class="fw-bold">Belum Ada Penjualan</h6>
                                            <p class="text-muted small mb-2">Belum ada data penjualan terbaru</p>
                                            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus me-1"></i> Catat Penjualan
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(count($recentSales) > 0)
                <div class="card-footer bg-white border-top py-2 text-center">
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary btn-xs">
                        <i class="fas fa-list me-1"></i> Lihat Semua Penjualan
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
    .bg-bronze {
        background-color: #CD7F32;
        color: white;
    }
    
    .btn-xs {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
    </style>

@endsection

@section('scripts')
<script>
    // Use actual data from the controller
    const weeklySalesData = @json($weeklySalesData);
    const monthlySalesData = @json($monthlySalesData);
    const productDistributionData = @json($productDistributionData['data']);
    const productCategories = @json($productDistributionData['categories']);
    const stockLevelsData = @json($stockLevelsData);

    // Initialize charts when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesChartOptions = {
            series: [{
                name: 'Penjualan',
                data: weeklySalesData.map(item => item.y)
            }],
            chart: {
                type: 'area',
                height: 280,
                toolbar: {
                    show: false
                },
                fontFamily: 'Inter, sans-serif',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#4361ee'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: weeklySalesData.map(item => item.x),
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    },
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                padding: {
                    bottom: 5
                }
            },
            legend: {
                show: false
            },
            markers: {
                size: 4,
                colors: ['#4361ee'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            }
        };

        // Product Distribution Chart
        const productDistributionOptions = {
            series: productDistributionData,
            chart: {
                type: 'donut',
                height: 280,
                fontFamily: 'Inter, sans-serif',
            },
            labels: productCategories,
            colors: ['#4361ee', '#4cc9f0', '#f72585', '#adb5bd'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                showAlways: false,
                                label: 'Total Produk',
                                fontSize: '14px',
                                fontWeight: 600,
                                color: '#495057',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                fontSize: '12px',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 2
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 2
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 250
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' produk';
                    }
                }
            }
        };
        
        // Stock Levels Chart
        const stockLevelsOptions = {
            series: [{
                name: 'Stok',
                data: stockLevelsData.stocks
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: {
                    show: false
                },
                fontFamily: 'Inter, sans-serif',
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                    distributed: true,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: stockLevelsData.colors,
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetX: 20,
                style: {
                    fontSize: '12px',
                    colors: ['#304758']
                }
            },
            xaxis: {
                categories: stockLevelsData.products,
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' unit';
                    }
                }
            },
            legend: {
                show: false
            },
            grid: {
                borderColor: '#f1f1f1',
                padding: {
                    left: 10
                }
            }
        };

        // Initialize charts
        const salesChart = new ApexCharts(document.querySelector("#salesChart"), salesChartOptions);
        salesChart.render();

        const productDistributionChart = new ApexCharts(document.querySelector("#productDistributionChart"), productDistributionOptions);
        productDistributionChart.render();
        
        const stockLevelsChart = new ApexCharts(document.querySelector("#stockLevelsChart"), stockLevelsOptions);
        stockLevelsChart.render();

        // Toggle between weekly and monthly data
        document.getElementById('weekly-sales').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('monthly-sales').classList.remove('active');
            
            salesChart.updateOptions({
                series: [{
                    data: weeklySalesData.map(item => item.y)
                }],
                xaxis: {
                    categories: weeklySalesData.map(item => item.x)
                }
            });
        });

        document.getElementById('monthly-sales').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('weekly-sales').classList.remove('active');
            
            salesChart.updateOptions({
                series: [{
                    data: monthlySalesData.map(item => item.y)
                }],
                xaxis: {
                    categories: monthlySalesData.map(item => item.x)
                }
            });
        });
    });
</script>
@endsection 