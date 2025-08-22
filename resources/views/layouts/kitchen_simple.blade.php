<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Dapur') - Restaurant</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
            padding: 24px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .main-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }
        
        .header-date {
            color: #6b7280;
            font-size: 14px;
        }
        
        .content-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 32px;
        }
        
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
        
        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Restaurant Kitchen</h1>
                <p>Dapur Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('kitchen.dashboard') }}" class="nav-item {{ request()->routeIs('kitchen.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    Dasbor
                </a>
                <a href="{{ route('kitchen.orders.index') }}" class="nav-item {{ request()->routeIs('kitchen.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    Pesanan Masuk
                </a>
                <a href="{{ route('kitchen.menu.index') }}" class="nav-item {{ request()->routeIs('kitchen.menu.*') ? 'active' : '' }}">
                    <i class="fas fa-utensils"></i>
                    Kelola Menu
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <h4>{{ Auth::user()->name ?? 'Chef' }}</h4>
                        <p>Koki</p>
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
                <h2>@yield('title', 'Dashboard Dapur')</h2>
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
