<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Waiter Dashboard') - Restaurant Waiter</title>
    
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
        
        /* Alert Styles */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            margin-right: 8px;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        /* Responsive */
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
                <h1>Restaurant Waiter</h1>
                <p>Waiter Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('waiter.dashboard') }}" class="nav-item {{ request()->routeIs('waiter.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    Dasbor
                </a>
                <a href="{{ route('waiter.orders.index') }}" class="nav-item {{ request()->routeIs('waiter.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    Pesanan
                </a>
                <a href="{{ route('waiter.tables.index') }}" class="nav-item {{ request()->routeIs('waiter.tables.*') ? 'active' : '' }}">
                    <i class="fas fa-chair"></i>
                    Meja
                </a>
                <a href="{{ route('waiter.reservations.index') }}" class="nav-item {{ request()->routeIs('waiter.reservations.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    Reservasi
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <h4>{{ Auth::user()->name }}</h4>
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column !important;
            }
            
            .sidebar {
                width: 100% !important;
                height: auto !important;
                position: fixed !important;
                top: 0 !important;
                left: -100% !important;
                z-index: 1000 !important;
                transition: left 0.3s ease !important;
            }
            
            .sidebar.open {
                left: 0 !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold text-white">
                <i class="fas fa-utensils mr-2"></i>
                Restaurant - Pelayan
            </h1>
        </div>
        
        <nav class="flex-1 p-4">
            <div class="space-y-2">
                <a href="{{ route('waiter.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('waiter.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('waiter.orders.index') }}" 
                   class="nav-link {{ request()->routeIs('waiter.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    Kelola Pesanan
                </a>
                
                <a href="{{ route('waiter.tables.index') }}" 
                   class="nav-link {{ request()->routeIs('waiter.tables.*') ? 'active' : '' }}">
                    <i class="fas fa-table"></i>
                    Kelola Meja
                </a>
                
                <a href="{{ route('waiter.reservations.index') }}" 
                   class="nav-link {{ request()->routeIs('waiter.reservations.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    Reservasi
                </a>
            </div>
        </nav>
        
        <!-- User Info and Logout -->
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-10 w-10 rounded-full bg-gray-600 flex items-center justify-center">
                    <span class="text-white text-sm font-medium">{{ Auth::check() ? substr(Auth::user()->name, 0, 1) : 'G' }}</span>
                </div>
                <div>
                    <div class="text-sm font-medium text-white">{{ Auth::check() ? Auth::user()->name : 'Guest User' }}</div>
                    <div class="text-xs text-gray-400">{{ Auth::check() ? Auth::user()->email : 'guest@example.com' }}</div>
                </div>
            </div>
            
            <a href="{{ route('multi-session.dashboard') }}" 
               class="nav-link">
                <i class="fas fa-exchange-alt"></i>
                Multi Session
            </a>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-full text-left">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard Pelayan')</h1>
                <p class="text-gray-600 mt-1">@yield('subtitle', 'Kelola pesanan dine-in, meja, dan reservasi')</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-900">{{ Auth::check() ? Auth::user()->name : 'Guest User' }}</div>
                    <div class="text-xs text-gray-500">Pelayan</div>
                </div>
                <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
                    <span class="text-white text-sm font-medium">{{ Auth::check() ? substr(Auth::user()->name, 0, 1) : 'G' }}</span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
