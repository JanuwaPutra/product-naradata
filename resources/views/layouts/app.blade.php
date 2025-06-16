<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CRUD Product') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Apex Charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --light: #f8f9fa;
            --dark: #212529;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --border-radius: 0.375rem;
            --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --box-shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
            --box-shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            --transition: all 0.2s ease-in-out;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            color: var(--gray-700);
            font-size: 0.9rem;
        }
        
        main {
            flex: 1;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0.75rem;
            min-height: 50px;
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
        }
        
        .sidebar {
            background-color: #ffffff;
            min-height: calc(100vh - 50px);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            z-index: 10;
        }
        
        .sidebar .nav-link {
            border-radius: var(--border-radius);
            margin: 0.15rem 0;
            padding: 0.5rem 0.75rem;
            color: var(--gray-700);
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--gray-100);
            color: var(--primary);
            transform: translateX(3px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white !important;
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .card:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        
        .btn {
            border-radius: var(--border-radius);
            padding: 0.35rem 0.75rem;
            font-weight: 500;
            transition: var(--transition);
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            box-shadow: 0 2px 6px rgba(67, 97, 238, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(67, 97, 238, 0.4);
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
        }
        
        .table th {
            font-weight: 600;
            color: var(--gray-700);
            background-color: var(--gray-100);
            border-bottom: 2px solid var(--gray-200);
            padding: 0.5rem 0.75rem;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.5rem 0.75rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.03);
        }
        
        /* Custom Pagination Styles */
        .pagination {
            margin-bottom: 0;
            gap: 0.25rem;
        }
        
        .page-item .page-link {
            border-radius: var(--border-radius);
            padding: 0.35rem 0.5rem;
            font-size: 0.8rem;
            color: var(--primary);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            min-width: 1.75rem;
            text-align: center;
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-color: var(--primary);
            box-shadow: 0 1px 3px rgba(67, 97, 238, 0.2);
        }
        
        .page-item.disabled .page-link {
            color: var(--gray-500);
            background-color: var(--gray-100);
        }
        
        .page-link:focus {
            box-shadow: none;
        }
        
        .page-link:hover {
            background-color: var(--gray-100);
            border-color: var(--gray-300);
            color: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        /* Pagination container spacing */
        .pagination-container {
            margin-top: 0.5rem;
        }
        
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-sm);
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }
        
        .alert-success {
            background-color: rgba(76, 201, 240, 0.15);
            color: #0e7490;
            border-left: 3px solid var(--success);
        }
        
        .alert-danger {
            background-color: rgba(230, 57, 70, 0.15);
            color: #b91c1c;
            border-left: 3px solid var(--danger);
        }
        
        .badge {
            font-weight: 500;
            padding: 0.25em 0.5em;
            border-radius: 2rem;
            font-size: 0.75rem;
        }
        
        footer {
            background-color: #ffffff;
            border-top: 1px solid var(--gray-200);
            padding: 0.5rem 0;
            font-size: 0.8rem;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        /* Status indicators */
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        
        /* User profile in navbar */
        .user-profile {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.85rem;
        }
        
        .user-profile i {
            font-size: 1rem;
        }
        
        /* Compact card styles */
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        /* Form controls */
        .form-control, .form-select {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
            border-radius: var(--border-radius);
        }
        
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        /* Icon sizes */
        .fa-2x {
            font-size: 1.5rem;
        }
        
        /* Heading sizes */
        h1, .h1 { font-size: 1.75rem; }
        h2, .h2 { font-size: 1.5rem; }
        h3, .h3 { font-size: 1.3rem; }
        h4, .h4 { font-size: 1.2rem; }
        h5, .h5 { font-size: 1.1rem; }
        h6, .h6 { font-size: 1rem; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-2">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="fas fa-box-open me-2"></i>
                Inventaris Produk Naradata
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">

                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 px-0 sidebar border-end">
                <div class="p-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="status-indicator bg-success me-2"></div>
                        <span class="fw-medium small">Sistem Aktif</span>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="fas fa-box me-2"></i> Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                                <i class="fas fa-shopping-cart me-2"></i> Penjualan
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="my-3">
                    
                    <!-- <div class="card bg-light mb-2">
                        <div class="card-body p-2">
                            <h6 class="card-title mb-2 fw-semibold small">
                                <i class="fas fa-info-circle me-1 text-primary"></i> Status Sistem
                            </h6>
                            <div class="d-flex align-items-center mb-1">
                                <div class="me-2">
                                    <span class="bg-success rounded-circle d-inline-block" style="width:6px;height:6px"></span>
                                </div>
                                <small class="fs-xs">Sistem aktif</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <span class="bg-primary rounded-circle d-inline-block" style="width:6px;height:6px"></span>
                                </div>
                                <small class="fs-xs">Database terhubung</small>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-3 py-3 fade-in">
                <div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-3">
                    <h4 class="fw-bold">@yield('title', 'Dashboard')</h4>
                    @yield('header-buttons')
                </div>

                <div class="container-fluid px-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global script -->
    <script>
        // Auto dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in class to main content
            document.querySelector('main').classList.add('fade-in');
            
            // Auto dismiss alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    
    @yield('scripts')
</body>
</html> 