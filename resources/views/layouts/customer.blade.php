<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restoran')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <i class="fas fa-utensils text-2xl text-orange-600"></i>
                        <span class="text-xl font-bold text-gray-900">Restoran</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-orange-600 transition-colors {{ request()->routeIs('home') ? 'text-orange-600 font-semibold' : '' }}">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('customer.menu') }}" class="text-gray-700 hover:text-orange-600 transition-colors {{ request()->routeIs('customer.menu') ? 'text-orange-600 font-semibold' : '' }}">
                        <i class="fas fa-book-open mr-1"></i> Menu
                    </a>
                    <a href="{{ route('customer.reservation') }}" class="text-gray-700 hover:text-orange-600 transition-colors {{ request()->routeIs('customer.reservation*') ? 'text-orange-600 font-semibold' : '' }}">
                        <i class="fas fa-calendar-alt mr-1"></i> Reservasi
                    </a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    <!-- Cart Icon -->
                    <button onclick="toggleCart()" class="relative p-2 text-gray-700 hover:text-orange-600 transition-colors">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-orange-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                    </button>

                    <!-- User Menu -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-orange-600 transition-colors">
                                <i class="fas fa-user"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="{{ route('customer.order-history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-receipt mr-2"></i> Order History
                                </a>
                                <hr class="my-1">
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600 transition-colors">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2 text-gray-700 hover:text-orange-600" x-data @click="$dispatch('toggle-mobile-menu')">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden bg-white border-t" x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open">
            <div class="px-4 py-2 space-y-2">
                <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-orange-600 transition-colors">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <a href="{{ route('customer.menu') }}" class="block py-2 text-gray-700 hover:text-orange-600 transition-colors">
                    <i class="fas fa-book-open mr-2"></i> Menu
                </a>
                <a href="{{ route('customer.reservation') }}" class="block py-2 text-gray-700 hover:text-orange-600 transition-colors">
                    <i class="fas fa-calendar-alt mr-2"></i> Reservasi
                </a>
                
                @guest
                <hr class="my-2">
                <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-orange-600 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
                <a href="{{ route('register') }}" class="block py-2 text-gray-700 hover:text-orange-600 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i> Register
                </a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Cart Sidebar -->
    <div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40" onclick="toggleCart()"></div>
    <div id="cart-sidebar" class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Keranjang Belanja</h3>
                <button onclick="toggleCart()" class="text-gray-500 hover:text-orange-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div id="cart-items" class="p-6 max-h-96 overflow-y-auto">
            <p class="text-gray-500 text-center">Keranjang masih kosong</p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 p-6 border-t bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <span class="font-semibold">Total:</span>
                <span id="cart-total" class="font-bold text-xl text-orange-600">Rp 0</span>
            </div>
            <div class="space-y-2">
                <a href="{{ route('checkout.index') }}" id="checkout-btn" 
                   class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 rounded-lg transition duration-300 font-semibold text-center block hidden">
                    Checkout
                </a>
                <button onclick="clearCart()" id="clear-cart-btn" 
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition duration-300 hidden">
                    Clear Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-orange-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Restoran</h3>
                    <p class="text-orange-100">Nikmati pengalaman kuliner terbaik dengan menu berkualitas tinggi dan pelayanan yang ramah.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <div class="space-y-2 text-orange-100">
                        <p><i class="fas fa-map-marker-alt mr-2"></i> Jl. Contoh No. 123, Jakarta</p>
                        <p><i class="fas fa-phone mr-2"></i> (021) 1234-5678</p>
                        <p><i class="fas fa-envelope mr-2"></i> info@restoran.com</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Jam Operasional</h3>
                    <div class="space-y-2 text-orange-100">
                        <p>Senin - Jumat: 10:00 - 22:00</p>
                        <p>Sabtu - Minggu: 09:00 - 23:00</p>
                    </div>
                </div>
            </div>
            <hr class="my-8 border-orange-700">
            <div class="text-center text-orange-100">
                <p>&copy; 2025 Restoran. All rights reserved.</p>
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

        window.showToast = function(message, type = 'success') {
            // Remove existing toasts
            const existingToasts = document.querySelectorAll('.toast-notification');
            existingToasts.forEach(toast => toast.remove());
            
            const toast = document.createElement('div');
            toast.className = `toast-notification fixed top-4 left-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-0`;
            
            if (type === 'success') {
                toast.className += ' bg-green-600 text-white';
                toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
            } else {
                toast.className += ' bg-red-600 text-white';
                toast.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
            }
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(-100%)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>
