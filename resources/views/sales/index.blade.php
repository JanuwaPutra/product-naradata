@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('header-buttons')
    <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Catat Transaksi
    </a>
    <a href="{{ route('sales.import.form') }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-import me-1"></i> Import Excel
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-semibold">Data Transaksi</h5>
                </div>
                <div class="col-auto">
                    <div class="btn-group">
                        <a href="{{ secure_url(route('sales.export.excel', [], false)) }}?search={{ request('search') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" class="btn btn-sm btn-outline-success" id="excelExportBtn">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </a>
                        <a href="{{ secure_url(route('sales.export.pdf', [], false)) }}?search={{ request('search') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" class="btn btn-sm btn-outline-danger" id="pdfExportBtn">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <label class="me-2">Show</label>
                        <select id="perPage" class="form-select form-select-sm" style="width: 80px; min-width: 80px;">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <label class="ms-2">entries</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <label class="me-2">Search:</label>
                        <div class="input-group input-group-sm" style="width: auto; max-width: 250px;">
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari transaksi..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary btn-sm" id="searchButton" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <label class="me-1">Periode:</label>
                        <div class="input-group input-group-sm" style="width: auto;">
                            <span class="input-group-text">Dari</span>
                            <input type="date" id="startDate" class="form-control form-control-sm" style="width: 140px;" value="{{ request('start_date') }}">
                        </div>
                        <div class="input-group input-group-sm" style="width: auto;">
                            <span class="input-group-text">Sampai</span>
                            <input type="date" id="endDate" class="form-control form-control-sm" style="width: 140px;" value="{{ request('end_date') }}">
                        </div>
                        <button id="filterDateButton" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <button id="resetFilterButton" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th>Kasir</th>
                            <th class="text-end">Total</th>
                            <th width="100" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="salesTable">
                        @include('sales.partials.sale-table')
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-2 border-top mt-3">
                <div class="mb-2 mb-md-0">
                    <small class="text-muted" style="font-size: 0.8rem;">Menampilkan <span id="fromItem">{{ $sales->firstItem() ?? 0 }}</span> sampai <span id="toItem">{{ $sales->lastItem() ?? 0 }}</span> dari <span id="totalItems">{{ $sales->total() }}</span> data</small>
                </div>
                <div class="pagination-container" id="pagination">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-xs {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // AJAX search and pagination
        let timer;
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const perPageSelect = document.getElementById('perPage');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const filterDateButton = document.getElementById('filterDateButton');
        const resetFilterButton = document.getElementById('resetFilterButton');
        
        // Set initial values from URL params if they exist
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('start_date')) {
            startDateInput.value = urlParams.get('start_date');
        }
        if (urlParams.has('end_date')) {
            endDateInput.value = urlParams.get('end_date');
        }
        
        // Function to load sales with AJAX
        function loadSales(page = 1) {
            const search = searchInput.value;
            const perPage = perPageSelect.value;
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            
            console.log('Filtering with dates:', startDate, endDate);
            
            // Show loading indicator
            document.getElementById('salesTable').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 small">Memuat data...</p>
                    </td>
                </tr>
            `;
            
            // Build query string
            const queryParams = new URLSearchParams();
            queryParams.append('page', page);
            queryParams.append('search', search);
            queryParams.append('per_page', perPage);
            
            if (startDate) {
                queryParams.append('start_date', startDate);
            }
            
            if (endDate) {
                queryParams.append('end_date', endDate);
            }
            
            // Make AJAX request - use current protocol (http/https)
            const baseUrl = window.location.protocol + '//' + window.location.host;
            const url = new URL('{{ route('sales.index', [], false) }}', baseUrl);
            
            fetch(`${url}?${queryParams.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update table content
                document.getElementById('salesTable').innerHTML = data.html;
                
                // Update pagination
                document.getElementById('pagination').innerHTML = data.pagination;
                
                // Update counter
                document.getElementById('fromItem').textContent = data.from || 0;
                document.getElementById('toItem').textContent = data.to || 0;
                document.getElementById('totalItems').textContent = data.total;
                
                // Reinitialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
                
                // Setup pagination links
                setupPaginationLinks();
                
                // Update URL with current filters without reloading page
                const url = new URL(window.location);
                url.searchParams.set('search', search);
                url.searchParams.set('per_page', perPage);
                
                if (startDate) {
                    url.searchParams.set('start_date', startDate);
                } else {
                    url.searchParams.delete('start_date');
                }
                
                if (endDate) {
                    url.searchParams.set('end_date', endDate);
                } else {
                    url.searchParams.delete('end_date');
                }
                
                window.history.pushState({}, '', url);
                
                // Update export links
                updateExportLinks(search, startDate, endDate);
            })
            .catch(error => {
                console.error('Error loading sales:', error);
                document.getElementById('salesTable').innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="py-3">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h6 class="fw-bold">Error</h6>
                                <p class="text-muted mb-3 small">Gagal memuat data. Silakan coba lagi.</p>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }
        
        // Function to update export links
        function updateExportLinks(search, startDate, endDate) {
            const excelLink = document.getElementById('excelExportBtn');
            const pdfLink = document.getElementById('pdfExportBtn');
            
            if (excelLink) {
                const baseUrl = window.location.protocol + '//' + window.location.host;
                let url = new URL('{{ route('sales.export.excel', [], false) }}', baseUrl);
                url.searchParams.set('search', search || '');
                if (startDate) url.searchParams.set('start_date', startDate);
                if (endDate) url.searchParams.set('end_date', endDate);
                excelLink.href = url.toString();
            }
            
            if (pdfLink) {
                const baseUrl = window.location.protocol + '//' + window.location.host;
                let url = new URL('{{ route('sales.export.pdf', [], false) }}', baseUrl);
                url.searchParams.set('search', search || '');
                if (startDate) url.searchParams.set('start_date', startDate);
                if (endDate) url.searchParams.set('end_date', endDate);
                pdfLink.href = url.toString();
            }
        }
        
        // Setup event for search input (with debounce)
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                loadSales();
            }, 500);
        });
        
        // Setup event for search button
        searchButton.addEventListener('click', function() {
            loadSales();
        });
        
        // Setup event for per page select
        perPageSelect.addEventListener('change', function() {
            loadSales();
        });
        
        // Setup event for filter date button
        filterDateButton.addEventListener('click', function() {
            loadSales();
        });
        
        // Setup event for reset filter button
        resetFilterButton.addEventListener('click', function() {
            startDateInput.value = '';
            endDateInput.value = '';
            searchInput.value = '';
            perPageSelect.value = '10';
            loadSales();
        });
        
        // Function to setup pagination links
        function setupPaginationLinks() {
            document.querySelectorAll('#pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.href.split('page=')[1].split('&')[0];
                    loadSales(page);
                });
            });
        }
        
        // Initial setup for pagination links
        setupPaginationLinks();
    });
</script>
@endsection 