<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Pelayan') - Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <a href="#" class="text-white flex items-center space-x-2 px-4">
                <i class="fas fa-utensils text-2xl"></i>
                <span class="text-2xl font-extrabold">Restaurant</span>
            </a>
            
            <nav>
                <a href="{{ route('waiter.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('waiter.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-chart-bar mr-2"></i>Dashboard
                </a>
                <a href="{{ route('waiter.orders.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('waiter.orders.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-shopping-cart mr-2"></i>Pesanan
                </a>
                <a href="{{ route('waiter.tables.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('waiter.tables.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-chair mr-2"></i>Meja
                </a>
                <a href="{{ route('waiter.reservations.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white {{ request()->routeIs('waiter.reservations.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-calendar-check mr-2"></i>Reservasi
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-full">
                <div class="px-4 py-2 border-t border-gray-700">
                    <div class="flex items-center space-x-2 mb-2">
                        <i class="fas fa-user-circle text-lg"></i>
                        <span class="text-sm">{{ Auth::user()->name ?? 'Guest' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left py-1 px-2 rounded transition duration-200 hover:bg-gray-700 text-sm">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900">@yield('title', 'Dashboard Pelayan')</h1>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
