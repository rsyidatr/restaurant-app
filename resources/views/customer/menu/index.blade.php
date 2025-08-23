@extends('layouts.customer')

@section('title', 'Menu - Warung Nusantara')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
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
                {{-- <div class="flex items-center space-x-2">
                    <i class="fas fa-shopping-cart text-orange-600"></i>
                    <span class="text-gray-600">Keranjang: <span id="cart-count">0</span> item</span>
                </div> --}}
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

                    <!-- Menu Items Grid - 4 cards per row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($category['items'] as $item)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden transition duration-300 hover:shadow-xl hover:scale-105 cursor-pointer group">
                                @php
                                    // Handle different image path formats
                                    if (str_contains($item['image'], 'menu/')) {
                                        // New storage path (menu/filename.ext)
                                        $imageUrl = asset('storage/' . $item['image']);
                                    } elseif (str_contains($item['image'], 'images/menu/')) {
                                        // Old public path (images/menu/filename.ext)
                                        $imageUrl = asset($item['image']);
                                    } else {
                                        // Default or just filename
                                        $imageUrl = asset('images/menu/' . $item['image']);
                                    }
                                @endphp
                                
                                <!-- Image with click to view detail -->
                                <div class="relative overflow-hidden" onclick="showMenuDetail({{ $item['id'] }}, `{{ $item['name'] }}`, `{{ str_replace(['`', "\n", "\r"], ['\\`', ' ', ' '], $item['description']) }}`, {{ $item['price'] }}, `{{ $imageUrl }}`)">
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="w-full h-36 object-cover transition duration-300 group-hover:scale-110"
                                         onerror="this.src='{{ asset('images/menu/default-menu.jpg') }}'">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300 flex items-center justify-center">
                                        <i class="fas fa-eye text-white text-xl opacity-0 group-hover:opacity-100 transition duration-300"></i>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <!-- Title and Price -->
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-1 cursor-pointer" 
                                            onclick="showMenuDetail({{ $item['id'] }}, `{{ $item['name'] }}`, `{{ str_replace(['`', "\n", "\r"], ['\\`', ' ', ' '], $item['description']) }}`, {{ $item['price'] }}, `{{ $imageUrl }}`)">
                                            {{ $item['name'] }}
                                        </h3>
                                        <span class="text-orange-600 font-bold text-sm whitespace-nowrap ml-2">
                                            Rp {{ number_format($item['price']) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Truncated Description -->
                                    <p class="text-gray-600 text-xs mb-3 line-clamp-2">
                                        {{ Str::limit($item['description'], 50) }}
                                    </p>
                                    
                                    <!-- Add to Cart Section -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center border rounded-md">
                                            <button type="button" class="px-2 py-1 text-gray-500 hover:text-orange-600 transition text-xs" onclick="decreaseQuantity({{ $item['id'] }})">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="qty-{{ $item['id'] }}" value="1" min="1" max="99" class="w-12 text-center border-0 focus:ring-0 text-xs">
                                            <button type="button" class="px-2 py-1 text-gray-500 hover:text-orange-600 transition text-xs" onclick="increaseQuantity({{ $item['id'] }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <button 
                                            class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded-md transition duration-300 text-xs font-medium"
                                            onclick="addToCart({{ $item['id'] }}, '{{ $item['name'] }}', {{ $item['price'] }})">
                                            <i class="fas fa-cart-plus mr-1"></i>Tambah
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

    <!-- Menu Detail Modal -->
    <div id="menuDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-lg w-full max-h-[85vh] overflow-y-auto shadow-2xl">
            <div class="relative">
                <!-- Close Button -->
                <button onclick="closeMenuDetail()" class="absolute top-3 right-3 z-10 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full p-1.5 transition duration-300 shadow-sm">
                    <i class="fas fa-times text-gray-600 text-sm"></i>
                </button>
                
                <!-- Modal Content -->
                <div class="p-0">
                    <!-- Image -->
                    <div class="relative">
                        <img id="modalImage" src="" alt="" class="w-full h-48 object-cover rounded-t-lg">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                            <h2 id="modalTitle" class="text-xl font-bold text-white mb-1"></h2>
                            <p id="modalPrice" class="text-orange-300 text-lg font-semibold"></p>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-4">
                        <!-- Description -->
                        <div class="mb-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-2">Deskripsi</h3>
                            <p id="modalDescription" class="text-gray-600 text-sm leading-relaxed"></p>
                        </div>
                        
                        <!-- Add to Cart Section -->
                        <div class="border-t pt-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center border rounded-lg">
                                    <button type="button" class="px-3 py-2 text-gray-500 hover:text-orange-600 transition" onclick="decreaseModalQuantity()">
                                        <i class="fas fa-minus text-sm"></i>
                                    </button>
                                    <input type="number" id="modalQuantity" value="1" min="1" max="99" class="w-14 text-center border-0 focus:ring-0 text-sm">
                                    <button type="button" class="px-3 py-2 text-gray-500 hover:text-orange-600 transition" onclick="increaseModalQuantity()">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                </div>
                                <button 
                                    id="modalAddToCartBtn"
                                    class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-300 text-sm font-medium"
                                    onclick="addToCartFromModal()">
                                    <i class="fas fa-cart-plus mr-1"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CSRF Token untuk AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         '{{ csrf_token() }}';

        // Global variables for modal
        let currentModalItem = {};

        // Load cart saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
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

        // Modal Functions
        function showMenuDetail(itemId, itemName, itemDescription, itemPrice, imageUrl) {
            currentModalItem = {
                id: itemId,
                name: itemName,
                description: itemDescription,
                price: itemPrice,
                image: imageUrl
            };

            // Update modal content
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImage').alt = itemName;
            document.getElementById('modalTitle').textContent = itemName;
            document.getElementById('modalPrice').textContent = 'Rp ' + numberFormat(itemPrice);
            document.getElementById('modalDescription').textContent = itemDescription;
            document.getElementById('modalQuantity').value = 1;

            // Show modal
            document.getElementById('menuDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMenuDetail() {
            document.getElementById('menuDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function increaseModalQuantity() {
            const qtyInput = document.getElementById('modalQuantity');
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }

        function decreaseModalQuantity() {
            const qtyInput = document.getElementById('modalQuantity');
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }

        function addToCartFromModal() {
            const quantity = document.getElementById('modalQuantity').value;
            addToCart(currentModalItem.id, currentModalItem.name, currentModalItem.price, quantity);
            closeMenuDetail();
        }

        // Close modal when clicking outside
        document.getElementById('menuDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMenuDetail();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenuDetail();
            }
        });

        // Number formatting helper
        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function addToCart(itemId, itemName, itemPrice, customQuantity = null) {
            const quantity = customQuantity || parseInt(document.getElementById(`qty-${itemId}`).value);
            const imageElement = document.querySelector(`img[alt="${itemName}"]`);
            const imageSrc = imageElement ? imageElement.src.split('/').pop() : '';
            
            // Show loading state
            const button = customQuantity ? 
                document.getElementById('modalAddToCartBtn') : 
                document.querySelector(`button[onclick*="addToCart(${itemId}"]`);
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
                    // Trigger global cart reload
                    if (window.loadCart) {
                        window.loadCart();
                    }
                    // Use global showToast function
                    if (window.showToast) {
                        window.showToast(data.message, 'success');
                    }
                    
                    // Reset quantity input
                    document.getElementById(`qty-${itemId}`).value = 1;
                } else {
                    if (window.showToast) {
                        window.showToast('Gagal menambahkan ke keranjang', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.showToast) {
                    window.showToast('Terjadi kesalahan', 'error');
                }
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    </script>
@endsection
