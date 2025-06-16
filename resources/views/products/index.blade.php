@extends('layouts.app')

@section('title', 'Daftar Inventaris')

@section('header-buttons')
    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Tambah Barang
    </a>
    <a href="{{ route('products.import.form') }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-import me-1"></i> Import Excel
    </a>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6 col-lg-3 mb-2 mb-lg-0">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 rounded p-2 me-2">
                            <i class="fas fa-boxes text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">Total Barang</h6>
                            <h5 class="fw-bold mb-0">{{ $products->total() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success bg-opacity-10 rounded p-2 me-2">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">Stok Tersedia</h6>
                            <h5 class="fw-bold mb-0">{{ $products->where('stock', '>', 0)->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-semibold">Daftar Inventaris</h5>
                </div>
                <div class="col-auto">
                    <div class="btn-group">
                        <a href="{{ secure_url(route('products.export.excel', [], false)) }}?search={{ request('search') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </a>
                        <a href="{{ secure_url(route('products.export.pdf', [], false)) }}?search={{ request('search') }}" class="btn btn-sm btn-outline-danger">
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
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari barang..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary btn-sm" id="searchButton" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th width="100" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="productsTable">
                        @forelse($products as $product)
                            <tr>

                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none fw-medium text-dark">
                                        {{ $product->name }}
                                    </a>
                                    <div class="small text-muted text-truncate" style="max-width: 200px; font-size: 0.8rem;">
                                        {{ Str::limit($product->description ?? 'Tidak ada deskripsi', 40) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold small">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @if($product->stock > 10)
                                        <span class="badge bg-success-subtle text-success rounded-pill">{{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                        <span class="badge bg-warning-subtle text-warning rounded-pill">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary btn-xs" data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning btn-xs" data-bs-toggle="tooltip" title="Edit Barang">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-xs" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}" title="Hapus Barang">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title small">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0 small">Apakah Anda yakin ingin menghapus barang ini?</p>
                                                    </div>
                                                    <div class="modal-footer py-1">
                                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('products.destroy', $product) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="py-3">
                                        <i class="fas fa-boxes fa-3x text-secondary mb-3"></i>
                                        <h6 class="fw-bold">Tidak Ada Barang</h6>
                                        <p class="text-muted mb-3 small">Belum ada barang yang tersedia di gudang</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm px-3">
                                            <i class="fas fa-plus me-1"></i> Tambah Barang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-2 border-top mt-3">
                <div class="mb-2 mb-md-0">
                    <small class="text-muted" style="font-size: 0.8rem;">Menampilkan <span id="fromItem">{{ $products->firstItem() ?? 0 }}</span> sampai <span id="toItem">{{ $products->lastItem() ?? 0 }}</span> dari <span id="totalItems">{{ $products->total() }}</span> data</small>
                </div>
                <div class="pagination-container" id="pagination">
                    {{ $products->links() }}
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
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // AJAX search and pagination
        let timer;
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const perPageSelect = document.getElementById('perPage');
        
        // Function to load products with AJAX
        function loadProducts(page = 1) {
            const search = searchInput.value;
            const perPage = perPageSelect.value;
            
            // Show loading indicator
            document.getElementById('productsTable').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 small">Memuat data...</p>
                    </td>
                </tr>
            `;
            
            // Make AJAX request - use current protocol (http/https)
            const baseUrl = window.location.protocol + '//' + window.location.host;
            const url = new URL('{{ route('products.index', [], false) }}', baseUrl);
            const queryParams = new URLSearchParams();
            queryParams.append('page', page);
            queryParams.append('search', search);
            queryParams.append('per_page', perPage);
            
            fetch(`${url}?${queryParams.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update table content
                document.getElementById('productsTable').innerHTML = data.html;
                
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
                
                // Update export links
                updateExportLinks(search);
            })
            .catch(error => {
                console.error('Error loading products:', error);
                document.getElementById('productsTable').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4">
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
        function updateExportLinks(search) {
            const excelLink = document.querySelector('a[href*="products.export.excel"]');
            const pdfLink = document.querySelector('a[href*="products.export.pdf"]');
            
            if (excelLink) {
                const baseUrl = window.location.protocol + '//' + window.location.host;
                let url = new URL('{{ route('products.export.excel', [], false) }}', baseUrl);
                url.searchParams.set('search', search || '');
                excelLink.href = url.toString();
            }
            
            if (pdfLink) {
                const baseUrl = window.location.protocol + '//' + window.location.host;
                let url = new URL('{{ route('products.export.pdf', [], false) }}', baseUrl);
                url.searchParams.set('search', search || '');
                pdfLink.href = url.toString();
            }
        }
        
        // Setup event for search input (with debounce)
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                loadProducts();
            }, 500);
        });
        
        // Setup event for search button
        searchButton.addEventListener('click', function() {
            loadProducts();
        });
        
        // Setup event for per page select
        perPageSelect.addEventListener('change', function() {
            loadProducts();
        });
        
        // Function to setup pagination links
        function setupPaginationLinks() {
            document.querySelectorAll('#pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.href.split('page=')[1].split('&')[0];
                    loadProducts(page);
                });
            });
        }
        
        // Initial setup for pagination links
        setupPaginationLinks();
    });
</script>
@endsection 