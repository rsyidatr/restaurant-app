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
                                            class="bg-orange-600 hover:bg-orange-700 text-white w-10 h-10 rounded-full transition duration-300 transform hover:scale-110 flex items-center justify-center"
                                            onclick="addToCart({{ $item['id'] }}, '{{ $item['name'] }}', {{ $item['price'] }})">
                                            <i class="fas fa-plus text-lg"></i>
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

    <script>
        // CSRF Token untuk AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         '{{ csrf_token() }}';

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

        function addToCart(itemId, itemName, itemPrice) {
            const quantity = parseInt(document.getElementById(`qty-${itemId}`).value);
            const imageElement = document.querySelector(`img[alt="${itemName}"]`);
            const imageSrc = imageElement ? imageElement.src.split('/').pop() : '';
            
            // Show loading state
            const button = document.querySelector(`button[onclick*="addToCart(${itemId}"]`);
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
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
