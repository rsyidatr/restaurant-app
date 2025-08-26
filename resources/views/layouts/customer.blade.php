<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restaurant - Premium Dining Experience')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom TailwindCSS Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'gold': {
                            50: '#fdfbf7',
                            100: '#f8f0d8',
                            200: '#f2e1b1',
                            300: '#ecc849',
                            400: '#d4af37',
                            500: '#b8941f',
                            600: '#9a7914',
                            700: '#7c5f11',
                            800: '#5d470d',
                            900: '#3e2f09'
                        },
                        'charcoal': {
                            50: '#f6f6f6',
                            100: '#e7e7e7',
                            200: '#d1d1d1',
                            300: '#b0b0b0',
                            400: '#888888',
                            500: '#6d6d6d',
                            600: '#5d5d5d',
                            700: '#4f4f4f',
                            800: '#454545',
                            900: '#3d3d3d',
                            950: '#2a2a2a'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'scale-in': 'scaleIn 0.4s ease-out',
                        'glow': 'glow 2s ease-in-out infinite alternate'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { 
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': { 
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        scaleIn: {
                            '0%': { 
                                opacity: '0',
                                transform: 'scale(0.95)'
                            },
                            '100%': { 
                                opacity: '1',
                                transform: 'scale(1)'
                            }
                        },
                        glow: {
                            '0%': { 
                                boxShadow: '0 0 20px rgba(212, 175, 55, 0.3)'
                            },
                            '100%': { 
                                boxShadow: '0 0 30px rgba(212, 175, 55, 0.6)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-overlay {
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        }
        
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .elegant-card {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            box-shadow: 
                20px 20px 60px #bebebe,
                -20px -20px 60px #ffffff;
        }
        
        .elegant-card-dark {
            background: linear-gradient(145deg, #2a2a2a, #3d3d3d);
            box-shadow: 
                20px 20px 60px #1a1a1a,
                -20px -20px 60px #4a4a4a;
        }
        
        .luxury-button {
            background: linear-gradient(135deg, #d4af37 0%, #f2e1b1 50%, #d4af37 100%);
            box-shadow: 
                0 8px 32px rgba(212, 175, 55, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .luxury-button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 12px 40px rgba(212, 175, 55, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
        
        .navbar-blur {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .scroll-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #d4af37, #f2e1b1);
            transform-origin: left;
            z-index: 9999;
        }
        
        .nav-link {
            @apply text-charcoal-700 hover:text-gold-600 font-medium transition-all duration-300 relative;
        }
        
        .nav-link.active {
            @apply text-gold-600;
        }
        
        .nav-link.active::after {
            content: '';
            @apply absolute bottom-0 left-0 w-full h-0.5 bg-gold-500;
        }
        
        .dropdown-item {
            @apply block w-full text-left px-4 py-3 text-sm font-medium text-charcoal-700 hover:bg-gradient-to-r hover:from-gold-50 hover:to-gold-100 hover:text-gold-700 transition-all duration-300 rounded-lg mx-2 my-1;
        }
        
        .dropdown-item:first-child {
            @apply mt-2;
        }
        
        .dropdown-item:last-child {
            @apply mb-2;
        }
        
        .mobile-nav-link {
            @apply block w-full text-left py-3 text-charcoal-700 hover:text-gold-600 font-medium border-b border-gray-100 transition-colors duration-200;
        }
        
        .footer-link {
            @apply block text-gray-300 hover:text-gold-400 transition-colors duration-300;
        }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="font-inter bg-gray-50 text-charcoal-900 overflow-x-hidden">
    <!-- Scroll Progress Indicator -->
    <div class="scroll-indicator" id="scroll-progress"></div>
    <!-- Navigation -->
    <nav class="navbar-blur fixed w-full top-0 z-50 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-utensils text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="font-playfair text-2xl font-bold text-charcoal-900">Restaurant</h1>
                        <p class="text-xs text-charcoal-500 font-medium">Premium Dining</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" 
                       class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="{{ route('customer.menu') }}" 
                       class="nav-link {{ request()->routeIs('customer.menu*') ? 'active' : '' }}">
                        <i class="fas fa-utensils mr-2"></i>Menu
                    </a>
                    <a href="{{ route('customer.reservation') }}" 
                       class="nav-link {{ request()->routeIs('customer.reservation*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt mr-2"></i>Reservations
                    </a>
                    <a href="{{ route('customer.order-history') }}" 
                       class="nav-link {{ request()->routeIs('customer.order-history*') ? 'active' : '' }}">
                        <i class="fas fa-receipt mr-2"></i>My Orders
                    </a>
                </div>

                <!-- Cart & User Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Cart -->
                    <button onclick="toggleCart()" class="relative group">
                        <div class="w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-200 hover:border-gold-400 transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-shopping-cart text-charcoal-700 group-hover:text-gold-600"></i>
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-gold-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold shadow-lg hidden">
                                0
                            </span>
                        </div>
                    </button>

                    @auth
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-3 bg-white rounded-full shadow-lg px-4 py-2 border border-gray-200 hover:border-gold-400 transition-all duration-300">
                                <div class="w-8 h-8 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="font-medium text-charcoal-700 hidden sm:block">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-charcoal-500 text-sm"></i>
                            </button>
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-1 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-1 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 @click.away="open = false"
                                 class="absolute right-0 top-full mt-3 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50 backdrop-blur-sm">
                                <div class="px-3 py-2 border-b border-gray-100">
                                    <p class="text-xs font-semibold text-charcoal-500 uppercase tracking-wider">My Account</p>
                                </div>
                                <a href="{{ route('customer.order-history') }}" class="dropdown-item">
                                    <i class="fas fa-receipt mr-3 text-gold-500"></i>My Orders
                                </a>
                                <a href="{{ route('customer.reservation') }}" class="dropdown-item">
                                    <i class="fas fa-calendar-alt mr-3 text-gold-500"></i>My Reservations
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-red-600 hover:bg-red-50 hover:from-red-50 hover:to-red-100">
                                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="luxury-button text-charcoal-900 font-semibold px-6 py-3 rounded-full">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    @endauth
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="md:hidden w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-200" 
                            onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-charcoal-700"></i>
                    </button>
                </div>
            </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden bg-white border-t border-gray-200 hidden">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('home') }}" class="mobile-nav-link">
                    <i class="fas fa-home mr-3"></i>Home
                </a>
                <a href="{{ route('customer.menu') }}" class="mobile-nav-link">
                    <i class="fas fa-utensils mr-3"></i>Menu
                </a>
                <a href="{{ route('customer.reservation') }}" class="mobile-nav-link">
                    <i class="fas fa-calendar-alt mr-3"></i>Reservations
                </a>
                <a href="{{ route('customer.order-history') }}" class="mobile-nav-link">
                    <i class="fas fa-receipt mr-3"></i>My Orders
                </a>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Cart Sidebar -->
    <div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40" onclick="toggleCart()"></div>
    <div id="cart-sidebar" class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="font-playfair text-2xl font-bold text-charcoal-900">Shopping Cart</h3>
                <button onclick="toggleCart()" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gold-100 transition-colors">
                    <i class="fas fa-times text-charcoal-700"></i>
                </button>
            </div>
        </div>
        <div id="cart-items" class="p-6 max-h-96 overflow-y-auto">
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Your cart is empty</p>
                <p class="text-gray-400 text-sm">Add some delicious items to get started</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 p-6 border-t bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <span class="font-playfair font-semibold text-lg">Total:</span>
                <span id="cart-total" class="font-bold text-2xl text-gold-600">Rp 0</span>
            </div>
            <div class="space-y-3">
                <a href="{{ route('checkout.index') }}" id="checkout-btn" 
                   class="w-full luxury-button text-charcoal-900 font-semibold py-3 rounded-full text-center block hidden transition-all duration-300">
                    Proceed to Checkout
                </a>
                <button onclick="clearCart()" id="clear-cart-btn" 
                        class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-full transition-all duration-300 font-medium hidden">
                    Clear Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-charcoal-900 text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Restaurant Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-utensils text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-playfair text-2xl font-bold">Restaurant</h3>
                            <p class="text-gold-400 text-sm">Premium Dining Experience</p>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed">
                        Experience culinary excellence in an atmosphere of refined elegance. 
                        Our restaurant offers the finest dining experience with impeccable service.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-playfair text-xl font-semibold mb-6 text-gold-400">Quick Links</h4>
                    <div class="space-y-3">
                        <a href="{{ route('home') }}" class="footer-link">Home</a>
                        <a href="{{ route('customer.menu') }}" class="footer-link">Our Menu</a>
                        <a href="{{ route('customer.reservation') }}" class="footer-link">Reservations</a>
                        <a href="{{ route('customer.order-history') }}" class="footer-link">Order History</a>
                    </div>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="font-playfair text-xl font-semibold mb-6 text-gold-400">Contact</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-gold-400"></i>
                            <span class="text-gray-300">123 Elegant Street, Luxury District</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-gold-400"></i>
                            <span class="text-gray-300">+1 (555) 123-4567</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-gold-400"></i>
                            <span class="text-gray-300">info@restaurant.com</span>
                        </div>
                    </div>
                </div>
                
                <!-- Hours -->
                <div>
                    <h4 class="font-playfair text-xl font-semibold mb-6 text-gold-400">Hours</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Monday - Friday</span>
                            <span class="text-white">11:00 - 22:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Saturday</span>
                            <span class="text-white">10:00 - 23:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Sunday</span>
                            <span class="text-white">10:00 - 21:00</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-charcoal-700 mt-12 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} Restaurant. All rights reserved. 
                    <span class="text-gold-400">Premium Dining Experience</span>
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <!-- Global Cart JavaScript -->
    <script>
        let cart = [];

        // Load cart when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
        });

        window.loadCart = async function() {
            try {
                const response = await fetch('{{ route("cart.get") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    cart = data.items || [];
                    updateCartDisplay();
                    updateCartCounter();
                }
            } catch (error) {
                console.error('Error loading cart:', error);
            }
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cart-items');
            const checkoutBtn = document.getElementById('checkout-btn');
            const clearCartBtn = document.getElementById('clear-cart-btn');
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-gray-500 text-center">Keranjang masih kosong</p>';
                checkoutBtn.classList.add('hidden');
                clearCartBtn.classList.add('hidden');
            } else {
                cartItems.innerHTML = cart.map(item => `
                    <div class="flex justify-between items-center mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium">${item.menu_name}</h4>
                            <p class="text-sm text-gray-600">${item.quantity}x Rp ${item.price.toLocaleString()}</p>
                            <p class="text-sm font-semibold text-orange-600">Total: Rp ${(item.price * item.quantity).toLocaleString()}</p>
                        </div>
                        <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700 ml-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `).join('');
                checkoutBtn.classList.remove('hidden');
                clearCartBtn.classList.remove('hidden');
            }

            // Update total
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('cart-total').textContent = 'Rp ' + total.toLocaleString();
        }

        function updateCartCounter() {
            const cartCount = document.getElementById('cart-count');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            if (totalItems > 0) {
                cartCount.textContent = totalItems;
                cartCount.classList.remove('hidden');
            } else {
                cartCount.classList.add('hidden');
            }
        }

        function toggleCart() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');
            
            if (sidebar.classList.contains('translate-x-full')) {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                loadCart(); // Refresh cart when opening
            } else {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        async function removeFromCart(itemId) {
            try {
                const response = await fetch(`{{ url('cart/remove') }}/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    loadCart();
                    showToast('Item berhasil dihapus dari keranjang', 'success');
                }
            } catch (error) {
                showToast('Gagal menghapus item', 'error');
            }
        }

        async function clearCart() {
            if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
                try {
                    const response = await fetch('{{ route("cart.clear") }}', {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        loadCart();
                        showToast('Keranjang berhasil dikosongkan', 'success');
                    }
                } catch (error) {
                    showToast('Gagal mengosongkan keranjang', 'error');
                }
            }
        }

        // Enhanced notification functions
        function showSuccessNotification(message) {
            if (window.NotificationManager) {
                window.NotificationManager.show(message, 'success');
            }
        }

        function showErrorNotification(message) {
            if (window.NotificationManager) {
                window.NotificationManager.show(message, 'error');
            }
        }

        function showWarningNotification(message) {
            if (window.NotificationManager) {
                window.NotificationManager.show(message, 'warning');
            }
        }

        function showInfoNotification(message) {
            if (window.NotificationManager) {
                window.NotificationManager.show(message, 'info');
            }
        }

        // Scroll Progress Indicator
        window.addEventListener('scroll', () => {
            const scrolled = (window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
            document.getElementById('scroll-progress').style.transform = `scaleX(${scrolled / 100})`;
        });
        
        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
        });
        
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
    
    <!-- Universal Notification System -->
    @include('components.notification')
</body>
</html>
