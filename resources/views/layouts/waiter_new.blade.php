<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Pelayan') - {{ config('app.name', 'Restaurant') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- TailwindCSS Fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Enhanced Waiter Dashboard Styles with !important declarations */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f9fafb !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif !important;
            display: flex !important;
            height: 100vh !important;
            overflow: hidden !important;
        }
        
        .sidebar { 
            width: 256px !important; 
            background: linear-gradient(135deg, #000000 0%, #1f2937 100%) !important; 
            color: white !important; 
            height: 100vh !important; 
            display: flex !important; 
            flex-direction: column !important; 
            position: relative !important;
            flex-shrink: 0 !important;
            overflow-y: auto !important;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1) !important;
        }
        
        .main-content { 
            flex: 1 !important; 
            margin-left: 0 !important; 
            background-color: #f9fafb !important;
            display: flex !important;
            flex-direction: column !important;
            height: 100vh !important;
            overflow: hidden !important;
        }
        
        /* Navigation Styles */
        .nav-link {
            display: flex !important;
            align-items: center !important;
            padding: 12px 20px !important;
            margin: 2px 12px !important;
            color: #d1d5db !important;
            text-decoration: none !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
            font-weight: 500 !important;
        }
        
        .nav-link:hover {
            background-color: rgba(55, 65, 81, 0.7) !important;
            color: #ffffff !important;
            transform: translateX(4px) !important;
        }
        
        .nav-link.active {
            background-color: #374151 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
        }
        
        .nav-link i {
            width: 20px !important;
            margin-right: 12px !important;
            text-align: center !important;
        }
        
        /* Header Styles */
        .header {
            background: white !important;
            border-bottom: 1px solid #e5e7eb !important;
            padding: 20px 32px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            flex-shrink: 0 !important;
        }
        
        /* Content Area */
        .content-area {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 32px !important;
            background-color: #f9fafb !important;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
            gap: 24px !important;
            margin-bottom: 32px !important;
        }
        
        .stats-card {
            background: white !important;
            border-radius: 12px !important;
            padding: 24px !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease !important;
        }
        
        .stats-card:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
        
        .icon-box {
            width: 48px !important;
            height: 48px !important;
            background: #f3f4f6 !important;
            border-radius: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #6b7280 !important;
            font-size: 20px !important;
        }
        
        /* Content Grid */
        .content-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)) !important;
            gap: 24px !important;
            margin-bottom: 32px !important;
        }
        
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
