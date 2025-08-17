<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Dapur') - {{ config('app.name', 'Restaurant') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('kitchen.dashboard') }}" class="text-xl font-bold text-gray-800">
                                <i class="fas fa-fire text-orange-600 mr-2"></i>
                                Restaurant - Dapur
                            </a>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('kitchen.dashboard') }}" 
                               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors
                                      {{ request()->routeIs('kitchen.dashboard') ? 'border-orange-500 text-orange-600' : '' }}">
                                <i class="fas fa-tachometer-alt mr-1"></i>
                                Dashboard
                            </a>
                            
                            <a href="{{ route('kitchen.orders.index') }}" 
                               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors
                                      {{ request()->routeIs('kitchen.orders.*') ? 'border-orange-500 text-orange-600' : '' }}">
                                <i class="fas fa-clipboard-list mr-1"></i>
                                Pesanan Masuk
                            </a>
                            
                            <a href="{{ route('kitchen.menu.index') }}" 
                               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors
                                      {{ request()->routeIs('kitchen.menu.*') ? 'border-orange-500 text-orange-600' : '' }}">
                                <i class="fas fa-utensils mr-1"></i>
                                Ketersediaan Menu
                            </a>
                        </div>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                                <div class="relative">
                                    <button type="button" 
                                            class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500" 
                                            id="user-menu-button" 
                                            aria-expanded="false" 
                                            aria-haspopup="true"
                                            onclick="toggleUserMenu()">
                                        <span class="sr-only">Open user menu</span>
                                        <div class="h-8 w-8 rounded-full bg-orange-600 flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                    </button>
                                    
                                    <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" 
                                         role="menu" 
                                         aria-orientation="vertical" 
                                         aria-labelledby="user-menu-button" 
                                         id="user-menu">
                                        <a href="{{ route('multi-session.dashboard') }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                           role="menuitem">
                                            <i class="fas fa-users mr-2"></i>Multi Session
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                                    role="menuitem">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="sm:hidden flex items-center">
                        <button type="button" 
                                class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500" 
                                aria-controls="mobile-menu" 
                                aria-expanded="false"
                                onclick="toggleMobileMenu()">
                            <span class="sr-only">Open main menu</span>
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div class="sm:hidden hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('kitchen.dashboard') }}" 
                       class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                              {{ request()->routeIs('kitchen.dashboard') ? 'bg-orange-50 border-orange-500 text-orange-700' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('kitchen.orders.index') }}" 
                       class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                              {{ request()->routeIs('kitchen.orders.*') ? 'bg-orange-50 border-orange-500 text-orange-700' : '' }}">
                        <i class="fas fa-clipboard-list mr-2"></i>Pesanan Masuk
                    </a>
                    <a href="{{ route('kitchen.menu.index') }}" 
                       class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                              {{ request()->routeIs('kitchen.menu.*') ? 'bg-orange-50 border-orange-500 text-orange-700' : '' }}">
                        <i class="fas fa-utensils mr-2"></i>Ketersediaan Menu
                    </a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-orange-600 flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('multi-session.dashboard') }}" 
                           class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                            <i class="fas fa-users mr-2"></i>Multi Session
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        
        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 transform translate-x-full transition-transform duration-300 z-50">
        <div class="bg-white border-l-4 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i id="toastIcon" class="text-xl"></i>
                </div>
                <div class="ml-3">
                    <p id="toastMessage" class="text-sm font-medium"></p>
                </div>
                <button onclick="hideToast()" class="ml-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    
    @stack('scripts')
    
    <script>
        // Toggle user menu
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }
        
        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userButton = document.getElementById('user-menu-button');
            
            if (!userButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
        
        // Toast notification functions
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            const messageEl = document.getElementById('toastMessage');
            const borderClass = type === 'success' ? 'border-green-400' : 'border-red-400';
            const iconClass = type === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500';
            
            messageEl.textContent = message;
            icon.className = iconClass;
            toast.firstElementChild.className = `bg-white ${borderClass} rounded-lg shadow-lg p-4 max-w-sm`;
            
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
            
            setTimeout(() => {
                hideToast();
            }, 3000);
        }
        
        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
        }
        
        // Show success message if exists
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('error') }}', 'error');
            });
        @endif
    </script>
</body>
</html>
