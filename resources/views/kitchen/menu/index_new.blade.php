@extends('layouts.kitchen_simple')

@section('title', 'Kelola Menu')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Ketersediaan Menu</h1>
                <p class="text-gray-600 mt-1">Atur ketersediaan item menu secara real-time</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="refreshAvailability()" 
                        class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all flex items-center space-x-2 shadow-lg"
                        title="Refresh Data">
                    <i class="fas fa-sync-alt"></i>
                    <span class="hidden md:inline">Refresh</span>
                </button>
                <div class="bg-gradient-to-br from-purple-400 to-indigo-500 p-3 rounded-xl shadow-lg">
                    <i class="fas fa-utensils text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tersedia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $availableCount ?? 0 }}</p>
                    <p class="text-xs text-green-600 font-medium">Menu aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-red-400 to-red-600 rounded-xl shadow-lg">
                    <i class="fas fa-times text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tidak Tersedia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $unavailableCount ?? 0 }}</p>
                    @if(($unavailableCount ?? 0) > 0)
                        <p class="text-xs text-red-600 font-medium">Perlu perhatian</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg">
                    <i class="fas fa-list text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Menu</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl shadow-lg">
                    <i class="fas fa-tags text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kategori</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $categoriesCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Bulk Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <!-- Filter Section -->
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('kitchen.menu.index') }}" 
                   class="filter-btn {{ !request('availability') ? 'active' : '' }}">
                    <i class="fas fa-list mr-2"></i>
                    Semua Menu
                    <span class="badge">{{ $totalCount ?? 0 }}</span>
                </a>
                
                <a href="{{ route('kitchen.menu.index', ['availability' => 'available']) }}" 
                   class="filter-btn {{ request('availability') === 'available' ? 'active' : '' }}">
                    <i class="fas fa-check mr-2"></i>
                    Tersedia
                    <span class="badge bg-green-500">{{ $availableCount ?? 0 }}</span>
                </a>
                
                <a href="{{ route('kitchen.menu.index', ['availability' => 'unavailable']) }}" 
                   class="filter-btn {{ request('availability') === 'unavailable' ? 'active' : '' }}">
                    <i class="fas fa-times mr-2"></i>
                    Tidak Tersedia
                    <span class="badge bg-red-500">{{ $unavailableCount ?? 0 }}</span>
                </a>
            </div>

            <!-- Bulk Action Section -->
            <div class="flex items-center space-x-3">
                <button onclick="selectAll()" 
                        class="bulk-btn bg-blue-50 hover:bg-blue-100 text-blue-700 border-blue-200"
                        title="Pilih Semua">
                    <i class="fas fa-check-square"></i>
                </button>
                
                <button onclick="bulkSetAvailable()" 
                        class="bulk-btn bg-green-50 hover:bg-green-100 text-green-700 border-green-200"
                        title="Tandai Tersedia"
                        id="bulk-available-btn" disabled>
                    <i class="fas fa-check"></i>
                </button>
                
                <button onclick="bulkSetUnavailable()" 
                        class="bulk-btn bg-red-50 hover:bg-red-100 text-red-700 border-red-200"
                        title="Tandai Tidak Tersedia"
                        id="bulk-unavailable-btn" disabled>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Items Grid -->
    <div id="menu-container">
        @if(isset($menuItems) && $menuItems->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($menuItems as $item)
                <div class="menu-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300" 
                     data-item-id="{{ $item->id }}">
                    
                    <!-- Card Header with Selection -->
                    <div class="relative">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" 
                                 alt="{{ $item->name }}"
                                 class="w-full h-48 object-cover rounded-t-xl">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-orange-100 to-red-100 rounded-t-xl flex items-center justify-center">
                                <i class="fas fa-utensils text-orange-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Selection Checkbox -->
                        <div class="absolute top-3 left-3">
                            <input type="checkbox" 
                                   class="item-checkbox w-5 h-5 text-orange-600 bg-white border-2 border-white rounded focus:ring-orange-500 focus:ring-2"
                                   value="{{ $item->id }}"
                                   onchange="updateBulkButtons()">
                        </div>
                        
                        <!-- Availability Badge -->
                        <div class="absolute top-3 right-3">
                            @php
                                $availability = $item->menuAvailability;
                                $isAvailable = $availability && $availability->is_available;
                            @endphp
                            <span class="availability-badge px-3 py-1 rounded-full text-xs font-medium
                                {{ $isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $isAvailable ? 'check' : 'times' }} mr-1"></i>
                                {{ $isAvailable ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $item->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $item->category->name ?? 'Tanpa Kategori' }}</p>
                            <p class="text-orange-600 font-semibold mt-2">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>

                        @if($item->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $item->description }}</p>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">
                                @if($isAvailable)
                                    <button onclick="toggleAvailability({{ $item->id }}, false)" 
                                            class="action-btn bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white"
                                            title="Tandai Tidak Tersedia">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @else
                                    <button onclick="toggleAvailability({{ $item->id }}, true)" 
                                            class="action-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white"
                                            title="Tandai Tersedia">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                
                                <button onclick="viewDetails({{ $item->id }})" 
                                        class="action-btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white"
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Availability Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer availability-toggle" 
                                       data-item-id="{{ $item->id }}"
                                       {{ $isAvailable ? 'checked' : '' }}
                                       onchange="quickToggle({{ $item->id }}, this.checked)">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($menuItems->hasPages())
                <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                    {{ $menuItems->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-utensils text-6xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Menu</h3>
                <p class="text-gray-600 mb-6">
                    @if(request('availability'))
                        Tidak ada menu dengan status "{{ request('availability') }}" saat ini.
                    @else
                        Belum ada item menu yang tersedia.
                    @endif
                </p>
                @if(request('availability'))
                    <a href="{{ route('kitchen.menu.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Menu
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600 mx-auto mb-4"></div>
        <p class="text-gray-600">Memproses perubahan...</p>
    </div>
</div>

<style>
.filter-btn {
    @apply inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium transition-all hover:bg-gray-200;
}

.filter-btn.active {
    @apply bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg;
}

.badge {
    @apply inline-flex items-center justify-center w-6 h-6 bg-gray-400 text-white text-xs font-bold rounded-full ml-2;
}

.bulk-btn {
    @apply w-10 h-10 rounded-lg flex items-center justify-center font-medium transition-all border;
}

.bulk-btn:disabled {
    @apply opacity-50 cursor-not-allowed;
}

.action-btn {
    @apply w-10 h-10 rounded-lg flex items-center justify-center font-medium transition-all transform hover:scale-105 shadow-lg;
}

.menu-card {
    transition: all 0.3s ease;
}

.menu-card:hover {
    transform: translateY(-2px);
}

.availability-badge {
    backdrop-filter: blur(10px);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
let selectedItems = [];

function refreshAvailability() {
    showInfo('Memperbarui data ketersediaan menu...');
    setTimeout(() => {
        location.reload();
    }, 500);
}

function updateBulkButtons() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    selectedItems = Array.from(checkboxes).map(cb => cb.value);
    
    const bulkAvailableBtn = document.getElementById('bulk-available-btn');
    const bulkUnavailableBtn = document.getElementById('bulk-unavailable-btn');
    
    const hasSelection = selectedItems.length > 0;
    bulkAvailableBtn.disabled = !hasSelection;
    bulkUnavailableBtn.disabled = !hasSelection;
    
    if (hasSelection) {
        bulkAvailableBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        bulkUnavailableBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        bulkAvailableBtn.classList.add('opacity-50', 'cursor-not-allowed');
        bulkUnavailableBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
    
    updateBulkButtons();
}

function bulkSetAvailable() {
    if (selectedItems.length === 0) return;
    
    const confirmId = showWarning(`Tandai ${selectedItems.length} item menu sebagai tersedia?`, false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedBulkUpdate(true, '${confirmId}')" 
                class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
            Ya, Tandai Tersedia
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
}

function bulkSetUnavailable() {
    if (selectedItems.length === 0) return;
    
    const confirmId = showWarning(`Tandai ${selectedItems.length} item menu sebagai tidak tersedia?`, false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedBulkUpdate(false, '${confirmId}')" 
                class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
            Ya, Tandai Tidak Tersedia
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
}

function proceedBulkUpdate(isAvailable, confirmId) {
    notificationManager.hide(confirmId);
    document.getElementById('loading-overlay').classList.remove('hidden');
    
    fetch('/kitchen/menu/bulk-update-availability', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            items: selectedItems,
            is_available: isAvailable
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loading-overlay').classList.add('hidden');
        if (data.success) {
            showSuccess(data.message);
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        document.getElementById('loading-overlay').classList.add('hidden');
        console.error('Error:', error);
        showError('Terjadi kesalahan saat memperbarui ketersediaan menu');
    });
}

function toggleAvailability(itemId, isAvailable) {
    const action = isAvailable ? 'tersedia' : 'tidak tersedia';
    const confirmId = showWarning(`Tandai menu ini sebagai ${action}?`, false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedToggle(${itemId}, ${isAvailable}, '${confirmId}')" 
                class="px-3 py-1 ${isAvailable ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'} text-white text-xs rounded">
            Ya, ${isAvailable ? 'Tersedia' : 'Tidak Tersedia'}
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
}

function proceedToggle(itemId, isAvailable, confirmId) {
    notificationManager.hide(confirmId);
    quickToggle(itemId, isAvailable);
}

function quickToggle(itemId, isAvailable) {
    const processingId = showInfo('Memperbarui ketersediaan menu...', false);
    
    fetch(`/kitchen/menu/${itemId}/toggle-availability`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            is_available: isAvailable
        })
    })
    .then(response => response.json())
    .then(data => {
        notificationManager.hide(processingId);
        if (data.success) {
            showSuccess(data.message);
            
            // Update UI immediately
            const card = document.querySelector(`[data-item-id="${itemId}"]`);
            if (card) {
                updateCardAvailability(card, isAvailable);
            }
        } else {
            showError(data.message);
            // Revert toggle state
            const toggle = document.querySelector(`[data-item-id="${itemId}"]`);
            if (toggle) {
                toggle.checked = !isAvailable;
            }
        }
    })
    .catch(error => {
        notificationManager.hide(processingId);
        console.error('Error:', error);
        showError('Terjadi kesalahan saat memperbarui ketersediaan menu');
        
        // Revert toggle state
        const toggle = document.querySelector(`[data-item-id="${itemId}"]`);
        if (toggle) {
            toggle.checked = !isAvailable;
        }
    });
}

function updateCardAvailability(card, isAvailable) {
    const badge = card.querySelector('.availability-badge');
    const actionBtn = card.querySelector('.action-btn');
    
    if (badge) {
        badge.className = `availability-badge px-3 py-1 rounded-full text-xs font-medium ${
            isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
        }`;
        badge.innerHTML = `<i class="fas fa-${isAvailable ? 'check' : 'times'} mr-1"></i>${
            isAvailable ? 'Tersedia' : 'Habis'
        }`;
    }
    
    if (actionBtn) {
        actionBtn.className = `action-btn ${
            isAvailable 
                ? 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700' 
                : 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700'
        } text-white`;
        actionBtn.innerHTML = `<i class="fas fa-${isAvailable ? 'times' : 'check'}"></i>`;
        actionBtn.title = `Tandai ${isAvailable ? 'Tidak Tersedia' : 'Tersedia'}`;
        actionBtn.setAttribute('onclick', `toggleAvailability(${card.dataset.itemId}, ${!isAvailable})`);
    }
}

function viewDetails(itemId) {
    showInfo('Fitur detail menu akan segera tersedia!');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateBulkButtons();
});
</script>
@endsection
