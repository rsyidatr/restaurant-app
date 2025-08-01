<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Dashboard') - Restaurant Staff</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
        }
        
        .navbar {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        }
        
        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-white">Restaurant Staff</h1>
                    </div>
                    
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            @if(session('current_role') === 'admin' || Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Admin Panel
                                </a>
                            @endif
                            
                            @if(session('current_role') === 'chef' || Auth::user()->role === 'chef')
                                <a href="{{ route('chef.dashboard') }}" 
                                   class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Dapur
                                </a>
                            @endif
                            
                            @if(session('current_role') === 'waiter' || Auth::user()->role === 'waiter')
                                <a href="{{ route('waiter.dashboard') }}" 
                                   class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Pelayanan
                                </a>
                            @endif
                            
                            <a href="{{ route('multi-session.dashboard') }}" 
                               class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                Multi-Session
                            </a>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Role Badge -->
                    @if(session('current_role'))
                        <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-sm font-medium">
                            {{ ucfirst(session('current_role')) }}
                        </span>
                    @endif
                    
                    <!-- User Info -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <p class="text-sm text-gray-300">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        
                        <!-- User Avatar -->
                        <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        
                        <!-- Logout -->
                        <form action="{{ route('staff.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="logout-btn text-gray-300 hover:text-red-400 p-2 rounded-md transition-colors"
                                    title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
