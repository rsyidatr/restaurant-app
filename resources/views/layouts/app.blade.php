<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-50">
    <!-- Header/Navbar -->
    <header class="bg-white shadow-sm fixed w-full top-0 z-50">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo/Restaurant Name (Left) -->
                <div class="flex items-center">
                    <a href="{{ route('customer.home') }}" class="text-2xl font-semibold text-orange-600">
                        Restaurant
                    </a>
                </div>

                <!-- Navigation Links (Center) -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('customer.home') }}" class="text-gray-600 hover:text-orange-600 transition">Home</a>
                    <a href="#menu" class="text-gray-600 hover:text-orange-600 transition">Menu</a>
                    <a href="#contact" class="text-gray-600 hover:text-orange-600 transition">Contact</a>
                    <a href="#reservation" class="text-gray-600 hover:text-orange-600 transition">Reservation</a>
                </div>

                <!-- Right Side Icons -->
                <div class="flex items-center space-x-6">
                    <!-- Shopping Cart -->
                    <a href="#" class="text-gray-600 hover:text-orange-600 transition relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-orange-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">0</span>
                    </a>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-orange-600 transition">
                            <i class="fas fa-user-circle text-xl"></i>
                        </button>
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-gray-600 hover:text-orange-600 transition" @click="mobileMenu = !mobileMenu">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu (Hidden by default) -->
            <div class="md:hidden hidden pt-4" x-show="mobileMenu" @click.away="mobileMenu = false">
                <a href="{{ route('customer.home') }}" class="block py-2 text-gray-600 hover:text-orange-600 transition">Home</a>
                <a href="#menu" class="block py-2 text-gray-600 hover:text-orange-600 transition">Menu</a>
                <a href="#contact" class="block py-2 text-gray-600 hover:text-orange-600 transition">Contact</a>
                <a href="#reservation" class="block py-2 text-gray-600 hover:text-orange-600 transition">Reservation</a>
            </div>
        </nav>
    </header>

    <!-- Main Content with padding-top to account for fixed header -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Alpine.js untuk dropdown dan mobile menu -->
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
