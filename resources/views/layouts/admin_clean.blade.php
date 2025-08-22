<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Restaurant Admin</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            height: 100vh;
            overflow: hidden;
        }
        
        .admin-layout {
            display: flex;
            height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #374151;
        }
        
        .sidebar-header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .sidebar-header p {
            font-size: 14px;
            color: #9ca3af;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
        }
        
        .nav-item {
            display: block;
            padding: 12px 24px;
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .nav-item:hover {
            background: rgba(55, 65, 81, 0.5);
            color: white;
            border-left-color: #3b82f6;
        }
        
        .nav-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: white;
            border-left-color: #3b82f6;
        }
        
        .nav-item i {
            width: 20px;
            margin-right: 12px;
        }
        
        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid #374151;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: #374151;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        
        .user-details h4 {
            font-size: 14px;
            font-weight: 500;
        }
        
        .user-details p {
            font-size: 12px;
            color: #9ca3af;
        }
        
        .logout-btn {
            width: 100%;
            padding: 8px 16px;
            background: transparent;
            border: 1px solid #374151;
            color: #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .logout-btn:hover {
            background: #374151;
            color: white;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .main-header {
            background: white;
            padding: 20px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .main-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
        }
        
        .header-date {
            color: #6b7280;
            font-size: 14px;
        }
        
        .content-wrapper {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
            background: #f8fafc;
        }
        
        /* Dashboard Components */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-info h3 {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }
        
        .stat-info p {
            color: #6b7280;
            font-size: 14px;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            background: #f3f4f6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #6b7280;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .content-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        
        .card-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }
        
        .card-content {
            padding: 24px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        
        .order-info h4 {
            font-weight: 500;
            color: #111827;
            margin-bottom: 4px;
        }
        
        .order-info p {
            color: #6b7280;
            font-size: 14px;
        }
        
        .order-amount {
            text-align: right;
        }
        
        .order-amount .price {
            font-weight: 600;
            color: #111827;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 4px;
        }
        
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-processing { background: #dbeafe; color: #2563eb; }
        .status-ready { background: #dcfce7; color: #16a34a; }
        .status-completed { background: #f3f4f6; color: #374151; }
        
        .table-status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
        }
        
        .status-indicator {
            display: flex;
            align-items: center;
        }
        
        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 12px;
        }
        
        .dot-green { background: #10b981; }
        .dot-yellow { background: #f59e0b; }
        .dot-red { background: #ef4444; }
        .dot-blue { background: #3b82f6; }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: #f0fdf4;
            border-color: #22c55e;
            color: #15803d;
        }
        
        .alert-error {
            background: #fef2f2;
            border-color: #ef4444;
            color: #dc2626;
        }
        
        .no-content {
            text-align: center;
            padding: 48px 24px;
            color: #6b7280;
        }
        
        .no-content i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: 200px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Restaurant Admin</h1>
                <p>Management Panel</p>
                <div class="mt-3">
                    <a href="{{ route('multi-session.dashboard') }}" 
                       class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded">
                        Multi-Session
                    </a>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    Dasbor
                </a>
                <a href="{{ route('admin.menu-categories.index') }}" class="nav-item {{ request()->routeIs('admin.menu-categories.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    Kategori Menu
                </a>
                <a href="{{ route('admin.menu.index') }}" class="nav-item {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                    <i class="fas fa-utensils"></i>
                    Item Menu
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    Pesanan
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="nav-item {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    Reservasi
                </a>
                <a href="{{ route('admin.tables.index') }}" class="nav-item {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                    <i class="fas fa-table"></i>
                    Meja
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Pengguna
                </a>
                <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    Pembayaran
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    Laporan
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <h4>{{ auth()->user()->name }}</h4>
                        <p>{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <header class="main-header">
                <h2>@yield('page-title', 'Dasbor')</h2>
                <div class="header-date">
                    <i class="fas fa-calendar"></i>
                    {{ now()->format('l, F j, Y') }}
                </div>
            </header>
            
            <main class="content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Universal Notification System -->
    @include('components.notification')
</body>
</html>
