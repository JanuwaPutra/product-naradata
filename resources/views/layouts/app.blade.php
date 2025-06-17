<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0d47a1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ config('app.name', 'Sistem Manajemen Gudang Naradata') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon/favicon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Apex Charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Add to homescreen prompt for PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <style>
        :root {
            --primary: #0d47a1;
            --primary-dark: #002171;
            --secondary: #00897b;
            --success: #10b981;
            --info: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light: #f8f9fa;
            --dark: #1f2937;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --border-radius: 0.5rem;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', 'sans-serif';
            background-color: #f9fafb;
            color: var(--gray-700);
            font-size: 0.95rem;
        }
        
        main {
            flex: 1;
        }
        
        .navbar {
            background: linear-gradient(135deg, #0d47a1 0%, #00897b 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1.5rem;
            min-height: 60px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
        }
        
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }
        
        .row.g-0 {
            margin-left: 0;
            margin-right: 0;
        }
        
        .sidebar {
            background-color: #ffffff;
            min-height: calc(100vh - 60px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            z-index: 1020;
            border-right: 1px solid var(--gray-200);
            position: fixed;
            top: 60px;
            height: calc(100vh - 60px);
            overflow-y: auto;
            width: 14.666667%;
            padding-top: 1rem;
            padding-right: 0;
            left: 0;
        }
        
        .sidebar .nav-link {
            border-radius: var(--border-radius);
            margin: 0.5rem 0.75rem;
            margin-right: 0.5rem;
            padding: 0.75rem 0.75rem;
            color: var(--gray-700);
            transition: all 0.3s;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background-color: var(--primary);
            opacity: 0.1;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover::before {
            width: 100%;
        }
        
        .sidebar .nav-link.active::before {
            width: 100%;
            opacity: 0;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: var(--gray-100);
            color: var(--primary);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #0d47a1 0%, #00897b 100%);
            color: white !important;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.3);
            margin-right: 0.5rem;
        }
        
        .nav-link.active i {
            color: white;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: all 0.3s;
            overflow: hidden;
        }

        
        .btn {
            border-radius: var(--border-radius);
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0d47a1 0%, #00897b 100%);
            border: none;
            box-shadow: 0 4px 10px rgba(13, 71, 161, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #00897b 0%, #0d47a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 137, 123, 0.4);
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.9rem;
        }
        
        .table th {
            font-weight: 600;
            color: var(--gray-700);
            background-color: var(--gray-100);
            border-bottom: 2px solid var(--gray-200);
            padding: 0.75rem 1rem;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        /* Custom Pagination Styles */
        .pagination {
            margin-bottom: 0;
            gap: 0.35rem;
        }
        
        .page-item .page-link {
            border-radius: var(--border-radius);
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            color: var(--primary);
            border: 1px solid var(--gray-200);
            transition: all 0.3s;
            min-width: 2rem;
            text-align: center;
        }
        
        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-color: var(--primary);
            box-shadow: 0 2px 5px rgba(67, 97, 238, 0.2);
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
        
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            color: #065f46;
            border-left: 4px solid var(--success);
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #b91c1c;
            border-left: 4px solid var(--danger);
        }
        
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 2rem;
            font-size: 0.75rem;
        }
        
        footer {
            background-color: #ffffff;
            border-top: 1px solid var(--gray-200);
            padding: 0.75rem 0;
            font-size: 0.85rem;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        
        /* Status indicators */
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }
        
        .status-indicator.bg-success {
            background-color: var(--success);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        
        /* User profile in navbar */
        .user-profile {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .user-profile:hover {
            background-color: rgba(255, 255, 255, 0.25);
        }
        
        /* Card styles */
        .card-header {
            padding: 1rem 1.25rem;
            background-color: #ffffff;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Form controls */
        .form-control, .form-select {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: var(--border-radius);
            border: 1px solid var(--gray-300);
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.35rem;
            color: var(--gray-700);
        }
        
        /* Heading sizes */
        h1, .h1 { font-size: 1.85rem; font-weight: 700; }
        h2, .h2 { font-size: 1.6rem; font-weight: 700; }
        h3, .h3 { font-size: 1.4rem; font-weight: 600; }
        h4, .h4 { font-size: 1.25rem; font-weight: 600; }
        h5, .h5 { font-size: 1.1rem; font-weight: 600; }
        h6, .h6 { font-size: 1rem; font-weight: 600; }
        
        /* Dashboard stat cards */
        .stat-card {
            border-radius: var(--border-radius);
            padding: 1rem;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card .stat-icon {
            font-size: 1.75rem;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-card .stat-label {
            color: var(--gray-600);
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .stat-card .stat-change {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
        }
        
        /* Sidebar improvements */
        .sidebar-header {
            padding: 1rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 0.5rem;
        }
        
        .sidebar-section {
            padding: 0.5rem 0;
            width: 100%;
        }
        
        .sidebar .nav {
            width: 100%;
        }
        
        .sidebar-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-500);
            margin: 1.5rem 0.75rem 0.5rem;
            font-weight: 600;
            padding-left: 0.5rem;
            border-left: 3px solid #00897b;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0;
        }
        
        .logo-container img {
            height: 40px;
            width: auto;
        }
        
        .logo-container .brand-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-left: 0.5rem;
            background: linear-gradient(135deg, #0d47a1 0%, #00897b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Main content */
        .main-content {
            margin-left: 14.666667%;
            margin-top: 60px;
            transition: margin-left 0.3s ease;
        }
        
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0 !important;
                width: 100%;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .sidebar {
                position: fixed;
                top: 60px;
                left: 0;
                height: calc(100vh - 60px);
                width: 80%;
                max-width: 280px;
                z-index: 1050;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                display: none;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }
            
            .sidebar.show {
                transform: translateX(0);
                display: block;
            }
            
            .navbar-toggler {
                display: block;
            }
            
            /* Overlay when sidebar is open */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
            
            /* Mobile-specific card adjustments */
            .card {
                margin-bottom: 1rem;
            }
            
            /* Table adjustments for mobile */
            .table-responsive {
                border-radius: var(--border-radius);
                margin-bottom: 1rem;
            }
            
            /* Adjust navbar for mobile */
            .navbar-brand {
                font-size: 1rem;
                max-width: 70%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            /* Bottom navigation for mobile */
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #fff;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                display: flex;
                justify-content: space-around;
                padding: 0.5rem 0;
                z-index: 1030;
                border-top: 1px solid var(--gray-200);
            }
            
            .mobile-bottom-nav a {
                display: flex;
                flex-direction: column;
                align-items: center;
                color: var(--gray-600);
                text-decoration: none;
                font-size: 0.7rem;
                padding: 0.5rem;
                transition: all 0.2s ease;
            }
            
            .mobile-bottom-nav a i {
                font-size: 1.2rem;
                margin-bottom: 0.25rem;
            }
            
            .mobile-bottom-nav a.active {
                color: var(--primary);
            }
            
            /* Add padding to main content to account for bottom nav */
            main {
                padding-bottom: 70px;
            }
        }
        
        /* Small mobile devices */
        @media (max-width: 575.98px) {
            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            h1, .h1 { font-size: 1.5rem; }
            h2, .h2 { font-size: 1.4rem; }
            h3, .h3 { font-size: 1.3rem; }
            h4, .h4 { font-size: 1.2rem; }
            h5, .h5 { font-size: 1.1rem; }
            
            .table td, .table th {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }
            
            /* Stack buttons in header on mobile */
            .header-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
                margin-top: 0.5rem;
            }
            
            .header-buttons .btn {
                width: 100%;
            }
        }
        
        @media (min-width: 992px) {
            .navbar-toggler {
                display: none;
            }
        }
        
        /* Blue to green gradient */
        .bg-blue-green-gradient {
            background: linear-gradient(135deg, #0d47a1 0%, #00897b 100%);
        }
        
        .text-blue-green-gradient {
            background: linear-gradient(135deg, #0d47a1 0%, #00897b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .sidebar .nav-link.disabled {
            color: var(--gray-500);
            opacity: 0.7;
            cursor: not-allowed;
            pointer-events: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link.disabled i {
            opacity: 0.7;
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/naradata-logo.png') }}" alt="Naradata Logo" style="height: 30px; width: auto; margin-right: 10px;">
                Sistem Manajemen Gudang Naradata
            </a>
            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
  
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 px-0 sidebar">
                <div class="sidebar-header">
                    <div class="logo-container">
                        <img src="{{ asset('images/naradata-logo.png') }}" alt="Naradata Logo" style="height: 40px; width: auto;">
                        <span class="brand-name">Naradata</span>
                    </div>
                </div>
                
                <div class="sidebar-section">
                    <p class="sidebar-title">Menu Utama</p>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="fas fa-boxes"></i> Inventaris
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                                <i class="fas fa-exchange-alt"></i> Transaksi
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <p class="sidebar-title">Laporan</p>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <span class="nav-link disabled">
                                <i class="fas fa-chart-bar"></i> Statistik
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link disabled">
                                <i class="fas fa-file-alt"></i> Laporan
                            </span>
                        </li>
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <p class="sidebar-title">Pengaturan</p>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <span class="nav-link disabled">
                                <i class="fas fa-user-cog"></i> Profil
                            </span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link disabled">
                                <i class="fas fa-cog"></i> Sistem
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 py-4 fade-in main-content">
                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h4 class="fw-bold">@yield('title', 'Dashboard')</h4>
                    @yield('header-buttons')
                </div>

                <div class="container-fluid px-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
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

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-bottom-nav d-lg-none">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i>
            <span>Inventaris</span>
        </a>
        <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>
        <a href="#" id="mobileMenuToggle">
            <i class="fas fa-bars"></i>
            <span>Menu</span>
        </a>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- <footer class="py-3 mt-auto">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <span>© 2025 Naradata</span>
            </div>
        </div>
    </footer> -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global script -->
    <script>
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
            
            // Elements
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            
            // Function to show sidebar
            function showSidebar() {
                sidebar.style.display = 'block';
                setTimeout(() => {
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.classList.add('show');
                    sidebarOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
                }, 50);
            }
            
            // Function to hide sidebar
            function hideSidebar() {
                sidebar.style.transform = 'translateX(-100%)';
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = ''; // Re-enable scrolling
                
                setTimeout(function() {
                    if (!sidebar.classList.contains('show')) {
                        sidebar.style.display = 'none';
                    }
                }, 300); // Match the transition duration
            }
            
            // Sidebar toggle for mobile
            if(sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (sidebar.classList.contains('show')) {
                        hideSidebar();
                    } else {
                        showSidebar();
                    }
                });
            }
            
            // Mobile menu toggle
            if(mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (sidebar.classList.contains('show')) {
                        hideSidebar();
                    } else {
                        showSidebar();
                    }
                });
            }
            
            // Close sidebar when clicking overlay
            if(sidebarOverlay) {
                sidebarOverlay.addEventListener('click', hideSidebar);
            }
            
            // Close sidebar when clicking a menu item (on mobile)
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            if (window.innerWidth < 992) {
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        hideSidebar();
                    });
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) { // For desktop
                    sidebar.style.display = 'block';
                    sidebar.style.transform = 'translateX(0)';
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                } else if (!sidebar.classList.contains('show')) {
                    sidebar.style.display = 'none';
                    sidebar.style.transform = 'translateX(-100%)';
                }
            });
            
            // Initialize sidebar state based on screen size
            if (window.innerWidth < 992) {
                sidebar.style.display = 'none';
                sidebar.style.transform = 'translateX(-100%)';
            }
            
            // Add swipe gestures for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            
            document.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);
            
            document.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, false);
            
            function handleSwipe() {
                const swipeThreshold = 100;
                
                // Swipe right to open sidebar
                if (touchEndX - touchStartX > swipeThreshold && touchStartX < 50) {
                    if (!sidebar.classList.contains('show')) {
                        showSidebar();
                    }
                }
                
                // Swipe left to close sidebar
                if (touchStartX - touchEndX > swipeThreshold && sidebar.classList.contains('show')) {
                    hideSidebar();
                }
            }
            
            // Add active class to current page in bottom nav
            const currentPath = window.location.pathname;
            const bottomNavLinks = document.querySelectorAll('.mobile-bottom-nav a');
            bottomNavLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @yield('scripts')
    
    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registered: ', registration.scope);
                    })
                    .catch(error => {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>
</body>
</html> 