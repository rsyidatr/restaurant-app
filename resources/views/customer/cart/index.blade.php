@extends('layouts.customer')

@section('title', 'Keranjang - Restoran')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Keranjang Belanja</h1>
            <p class="text-gray-600 mt-2">Kelola item pesanan Anda sebelum checkout</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-900">Item dalam Keranjang</h2>
                    </div>
                    
                    <div id="cart-items-container" class="p-6">
                        <!-- Cart items will be loaded here via JavaScript -->
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Keranjang Anda masih kosong</p>
                            <a href="{{ route('customer.menu') }}" 
                               class="inline-block mt-4 bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotal" class="font-semibold">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pajak (10%):</span>
                            <span id="tax" class="font-semibold">Rp 0</span>
                        </div>
                        <hr>
                        <div class="flex justify-between text-lg">
                            <span class="font-semibold">Total:</span>
                            <span id="total" class="font-bold text-orange-600">Rp 0</span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <a href="{{ route('checkout.index') }}" id="checkout-btn" 
                           class="w-full bg-orange-600 text-white py-3 px-4 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 font-semibold text-center block hidden transition-colors">
                            Lanjut ke Checkout
                        </a>
                        
                        <button onclick="clearAllCart()" id="clear-all-btn"
                                class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 font-semibold hidden transition-colors">
                            Kosongkan Keranjang
                        </button>
                        
                        <a href="{{ route('customer.menu') }}" 
                           class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 font-semibold text-center block transition-colors">
                            Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quantity Update Modal -->
<div id="quantity-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Update Quantity</h3>
        <div class="flex items-center justify-center space-x-4 mb-6">
            <button onclick="decreaseQuantity()" class="bg-gray-200 hover:bg-gray-300 w-10 h-10 rounded-full">
                <i class="fas fa-minus"></i>
            </button>
            <input type="number" id="modal-quantity" value="1" min="1" 
                   class="w-20 text-center border border-gray-300 rounded-md">
            <button onclick="increaseQuantity()" class="bg-gray-200 hover:bg-gray-300 w-10 h-10 rounded-full">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="flex space-x-3">
            <button onclick="updateItemQuantity()" 
                    class="flex-1 bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700">
                Update
            </button>
            <button onclick="closeQuantityModal()" 
                    class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400">
                Batal
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = [];
let currentEditId = null;

// Load cart when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
});

async function loadCart() {
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
            updateOrderSummary();
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        showToast('Gagal memuat keranjang', 'error');
    }
}

function updateCartDisplay() {
    const container = document.getElementById('cart-items-container');
    const checkoutBtn = document.getElementById('checkout-btn');
    const clearAllBtn = document.getElementById('clear-all-btn');
    
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Keranjang Anda masih kosong</p>
                <a href="{{ route('customer.menu') }}" 
                   class="inline-block mt-4 bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                    Mulai Belanja
                </a>
            </div>
        `;
        checkoutBtn.classList.add('hidden');
        clearAllBtn.classList.add('hidden');
    } else {
        container.innerHTML = cart.map(item => `
            <div class="flex items-center space-x-4 p-4 border-b border-gray-200 last:border-b-0">
                <img src="${item.image}" alt="${item.menu_name}" 
                     class="w-20 h-20 object-cover rounded-lg">
                
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">${item.menu_name}</h3>
                    <p class="text-gray-600">Rp ${item.price.toLocaleString()}</p>
                    ${item.notes ? `<p class="text-sm text-gray-500 mt-1">Catatan: ${item.notes}</p>` : ''}
                </div>
                
                <div class="flex items-center space-x-3">
                    <button onclick="openQuantityModal(${item.id}, ${item.quantity})" 
                            class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-md">
                        <i class="fas fa-edit mr-1"></i>
                        ${item.quantity}
                    </button>
                    
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">Rp ${(item.price * item.quantity).toLocaleString()}</p>
                    </div>
                    
                    <button onclick="removeFromCart(${item.id})" 
                            class="text-red-500 hover:text-red-700 p-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
        
        checkoutBtn.classList.remove('hidden');
        clearAllBtn.classList.remove('hidden');
    }
}

function updateOrderSummary() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = Math.round(subtotal * 0.1);
    const total = subtotal + tax;
    
    document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString();
    document.getElementById('tax').textContent = 'Rp ' + tax.toLocaleString();
    document.getElementById('total').textContent = 'Rp ' + total.toLocaleString();
}

function openQuantityModal(itemId, currentQuantity) {
    currentEditId = itemId;
    document.getElementById('modal-quantity').value = currentQuantity;
    document.getElementById('quantity-modal').classList.remove('hidden');
    document.getElementById('quantity-modal').classList.add('flex');
}

function closeQuantityModal() {
    currentEditId = null;
    document.getElementById('quantity-modal').classList.add('hidden');
    document.getElementById('quantity-modal').classList.remove('flex');
}

function decreaseQuantity() {
    const input = document.getElementById('modal-quantity');
    const value = parseInt(input.value);
    if (value > 1) {
        input.value = value - 1;
    }
}

function increaseQuantity() {
    const input = document.getElementById('modal-quantity');
    const value = parseInt(input.value);
    input.value = value + 1;
}

async function updateItemQuantity() {
    if (!currentEditId) return;
    
    const quantity = parseInt(document.getElementById('modal-quantity').value);
    
    try {
        const response = await fetch(`{{ url('cart/update') }}/${currentEditId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: quantity })
        });
        
        if (response.ok) {
            closeQuantityModal();
            loadCart();
            showToast('Quantity berhasil diupdate', 'success');
        } else {
            throw new Error('Gagal update quantity');
        }
    } catch (error) {
        showToast('Gagal update quantity', 'error');
    }
}

async function removeFromCart(itemId) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
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
                showToast('Item berhasil dihapus', 'success');
            }
        } catch (error) {
            showToast('Gagal menghapus item', 'error');
        }
    }
}

async function clearAllCart() {
    if (confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
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

function showToast(message, type = 'success') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-4 left-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300`;
    
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
@endpush
