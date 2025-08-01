@extends('layouts.customer')

@section('title', 'Menu - Warung Nusantara')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-light text-center mb-4">Menu Restoran</h1>
            <p class="text-center text-orange-100 text-lg">Nikmati kelezatan masakan Indonesia autentik</p>
        </div>
    </div>

    <!-- Shopping Cart Info -->
    <div class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Pilih menu favorit Anda</span>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shopping-cart text-orange-600"></i>
                    <span class="text-gray-600">Keranjang: <span id="cart-count">0</span> item</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Categories -->
    <div class="py-12">
        <div class="container mx-auto px-4">
            @foreach($menuCategories as $categoryKey => $category)
                <div class="mb-16">
                    <!-- Category Header -->
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-light text-orange-800 mb-4">{{ $category['name'] }}</h2>
                        <div class="w-24 h-1 bg-orange-600 mx-auto"></div>
                    </div>

                    <!-- Menu Items Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($category['items'] as $item)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-lg">
                                <img src="{{ url('images/menu/' . $item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-xl font-semibold text-gray-800">{{ $item['name'] }}</h3>
                                        <span class="text-orange-600 font-bold text-lg">Rp {{ number_format($item['price']) }}</span>
                                    </div>
                                    <p class="text-gray-600 mb-4 text-sm leading-relaxed">{{ $item['description'] }}</p>
                                    
                                    <!-- Add to Cart Section -->
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center border rounded-lg">
                                            <button type="button" class="px-3 py-1 text-gray-500 hover:text-orange-600 transition" onclick="decreaseQuantity({{ $item['id'] }})">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="qty-{{ $item['id'] }}" value="1" min="1" max="99" class="w-16 text-center border-0 focus:ring-0">
                                            <button type="button" class="px-3 py-1 text-gray-500 hover:text-orange-600 transition" onclick="increaseQuantity({{ $item['id'] }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <button 
                                            class="flex-1 bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02]"
                                            onclick="addToCart({{ $item['id'] }}, '{{ $item['name'] }}', {{ $item['price'] }})">
                                            <i class="fas fa-cart-plus mr-2"></i>
                                            Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Cart Sidebar (Hidden by default) -->
    <div id="cart-sidebar" class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Keranjang Belanja</h3>
                <button onclick="toggleCart()" class="text-gray-500 hover:text-orange-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div id="cart-items" class="p-6">
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
                </button>
                <button onclick="clearCart()" class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg transition duration-300 text-sm">
                    Kosongkan Keranjang
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Overlay -->
    <div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40" onclick="toggleCart()"></div>

    <script>
        let cart = [];
        let cartCount = 0;
        let cartTotal = 0;

        // CSRF Token untuk AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         '{{ csrf_token() }}';

        // Load cart saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
            
            // Add click event to cart icon in header
            const cartIcon = document.querySelector('.fas.fa-shopping-cart').parentElement;
            if (cartIcon) {
                cartIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleCart();
                });
            }

            // Add meta tag for CSRF if not exists
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }
        });

        function increaseQuantity(itemId) {
            const qtyInput = document.getElementById(`qty-${itemId}`);
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }

        function decreaseQuantity(itemId) {
            const qtyInput = document.getElementById(`qty-${itemId}`);
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }

        function addToCart(itemId, itemName, itemPrice) {
            const quantity = parseInt(document.getElementById(`qty-${itemId}`).value);
            const imageElement = document.querySelector(`img[alt="${itemName}"]`);
            const imageSrc = imageElement ? imageElement.src.split('/').pop() : '';
            
            // Show loading state
            const button = document.querySelector(`button[onclick*="addToCart(${itemId}"]`);
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...';
            button.disabled = true;

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    menu_item_id: itemId,
                    menu_name: itemName,
                    price: itemPrice,
                    quantity: quantity,
                    image: imageSrc
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCounter(data.cart_count, data.cart_total);
                    showToast(data.message, 'success');
                    loadCart(); // Reload cart items
                    
                    // Reset quantity input
                    document.getElementById(`qty-${itemId}`).value = 1;
                } else {
                    showToast('Gagal menambahkan ke keranjang', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function loadCart() {
            fetch('/cart/get', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                cart = data.items;
                updateCartCounter(data.cart_count, data.cart_total);
                updateCartDisplay();
            })
            .catch(error => {
                console.error('Error loading cart:', error);
            });
        }

        function removeFromCart(cartItemId) {
            fetch(`/cart/remove/${cartItemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCounter(data.cart_count, data.cart_total);
                    loadCart(); // Reload cart items
                    showToast(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Gagal menghapus item', 'error');
            });
        }

        function updateCartCounter(count, total) {
            cartCount = count;
            cartTotal = total;
            
            document.getElementById('cart-count').textContent = count;
            document.getElementById('cart-total').textContent = `Rp ${total.toLocaleString()}`;
            
            // Update cart counter di header
            if (window.updateHeaderCartCount) {
                window.updateHeaderCartCount(count);
            }
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cart-items');
            const checkoutBtn = document.getElementById('checkout-btn');
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-gray-500 text-center">Keranjang masih kosong</p>';
                checkoutBtn.classList.add('hidden');
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

        function showToast(message, type = 'success') {
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
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, 3000);
        }

        function clearCart() {
            if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
                fetch('/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCounter(data.cart_count, data.cart_total);
                        loadCart();
                        showToast(data.message, 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Gagal mengosongkan keranjang', 'error');
                });
            }
        }
    </script>
@endsection
