<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Restaurant Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Fallback TailwindCSS dari CDN jika Vite belum ready -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Enhanced Admin Dashboard Styles with !important declarations */
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
                max-height: 200px !important;
                position: relative !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                height: calc(100vh - 200px) !important;
            }
            
            .stats-grid {
                grid-template-columns: 1fr !important;
            }
            
            .content-grid {
                grid-template-columns: 1fr !important;
            }
        }
        
        /* Additional Card Styles */
        .bg-white {
            background-color: white !important;
        }
        
        .rounded-lg {
            border-radius: 8px !important;
        }
        
        .border {
            border-width: 1px !important;
        }
        
        .border-gray-200 {
            border-color: #e5e7eb !important;
        }
        
        .p-6 {
            padding: 24px !important;
        }
        
        .border-b {
            border-bottom-width: 1px !important;
        }
        
        .text-lg {
            font-size: 18px !important;
        }
        
        .font-semibold {
            font-weight: 600 !important;
        }
        
        .text-gray-900 {
            color: #111827 !important;
        }
        
        .space-y-4 > * + * {
            margin-top: 16px !important;
        }
        
        .flex {
            display: flex !important;
        }
        
        .items-center {
            align-items: center !important;
        }
        
        .justify-between {
            justify-content: space-between !important;
        }
        
        .bg-gray-50 {
            background-color: #f9fafb !important;
        }
        
        .text-center {
            text-align: center !important;
        }
        
        .py-8 {
            padding-top: 32px !important;
            padding-bottom: 32px !important;
        }
        
        .text-gray-500 {
            color: #6b7280 !important;
        }
    </style>
            z-index: 10;
        }
        
        .main-content {
            margin-left: 256px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding: 0 1.5rem;
        }
        
        .stats-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .icon-box {
            width: 48px;
            height: 48px;
            background: #000;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding: 0 1.5rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            margin: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            color: #d1d5db;
            text-decoration: none;
            transition: background-color 0.2s, color 0.2s;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background-color: #374151;
            color: white;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }
        
        .header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .content-area {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold">Restaurant Admin</h1>
            <p class="text-gray-400 text-sm">Management Panel</p>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.menu.index') }}" 
               class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                <i class="fas fa-utensils"></i>
                Menu Management
            </a>
            <a href="{{ route('admin.orders.index') }}" 
               class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                Orders
            </a>
            <a href="{{ route('admin.reservations.index') }}" 
               class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                Reservations
            </a>
            <a href="{{ route('admin.tables.index') }}" 
               class="nav-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                <i class="fas fa-table"></i>
                Tables
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                Users
            </a>
            <a href="{{ route('admin.payments.index') }}" 
               class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                Payments
            </a>
            <a href="{{ route('admin.reports.index') }}" 
               class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                Reports
            </a>
        </nav>
        
        <!-- User Info -->
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center mb-3">
                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-user text-gray-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt w-4 mr-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="header">
            <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            <div class="text-sm text-gray-600">
                <i class="fas fa-calendar mr-1"></i>
                {{ now()->format('l, F j, Y') }}
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="content-area">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
