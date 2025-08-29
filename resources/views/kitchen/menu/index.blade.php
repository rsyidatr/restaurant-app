@extends('layouts.kitchen_simple')

@section('title', 'Kelola Menu')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Ketersediaan Menu</h1>
                <p class="text-gray-600 mt-1">Atur ketersediaan item menu secara real-time</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="refreshAvailability()" 
                        class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-all flex items-center space-x-2"
                        title="Refresh Data">
                    <i class="fas fa-sync-alt"></i>
                    <span class="hidden md:inline">Refresh</span>
                </button>
                <div class="text-gray-400">
                    <i class="fas fa-utensils text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="text-gray-400 mr-4">
                    <i class="fas fa-check text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Tersedia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $availableCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Menu aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="text-gray-400 mr-4">
                    <i class="fas fa-times text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Tidak Tersedia</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $unavailableCount ?? 0 }}</p>
                    @if(($unavailableCount ?? 0) > 0)
                        <p class="text-xs text-orange-600">Perlu perhatian</p>
                    @else
                        <p class="text-xs text-gray-500">Semua tersedia</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="text-gray-400 mr-4">
                    <i class="fas fa-list text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Menu</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Item menu</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="text-gray-400 mr-4">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Kategori</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $categoriesCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Kategori menu</p>
                </div>
            </div>
        </div>
    </div>



    <!-- Menu Items List -->
    <div id="menu-container">
        @if(isset($menuItems) && $menuItems->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <h3 class="text-sm font-medium text-gray-700">Daftar Menu</h3>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="divide-y divide-gray-200">
                    @foreach($menuItems as $item)
                    @php
                        $isAvailable = $item->is_available;
                    @endphp
                    <div class="menu-item px-6 py-4 hover:bg-gray-50 transition-colors duration-200" 
                         data-item-id="{{ $item->id }}">
                        <div class="flex items-center justify-between">
                            <!-- Left Section: Image and Info -->
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Image -->
                                <div class="flex-shrink-0">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                             alt="{{ $item->name }}"
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 border border-gray-300 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensils text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Menu Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 text-lg">{{ $item->name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ $item->category->name ?? 'Tanpa Kategori' }}</p>
                                            @if($item->description)
                                                <p class="text-sm text-gray-500 mt-2 line-clamp-1">{{ $item->description }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right ml-4">
                                            <p class="text-lg font-semibold text-orange-600">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Section: Status and Toggle -->
                            <div class="flex items-center space-x-4 ml-6">
                                <!-- Availability Status -->
                                <div class="text-center">
                                    <span class="availability-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border
                                        {{ $isAvailable ? 'border-green-300 bg-green-50 text-green-700' : 'border-red-300 bg-red-50 text-red-700' }}">
                                        <i class="fas fa-{{ $isAvailable ? 'check' : 'times' }} mr-2"></i>
                                        {{ $isAvailable ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </div>

                                <!-- Availability Toggle Switch -->
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           class="sr-only peer availability-toggle" 
                                           data-item-id="{{ $item->id }}"
                                           {{ $isAvailable ? 'checked' : '' }}
                                           onchange="quickToggle({{ $item->id }}, this.checked)">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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
.bulk-btn {
    @apply w-10 h-10 rounded-lg flex items-center justify-center font-medium transition-all border;
}

.bulk-btn:disabled {
    @apply opacity-50 cursor-not-allowed;
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
function refreshAvailability() {
    showInfo('Memperbarui data ketersediaan menu...');
    setTimeout(() => {
        location.reload();
    }, 500);
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
            
            // Update UI with actual value from server response
            const card = document.querySelector(`[data-item-id="${itemId}"]`);
            if (card) {
                updateCardAvailability(card, data.is_available);
            }
            
            // Update toggle state to match server response
            const toggle = document.querySelector(`[data-item-id="${itemId}"]`);
            if (toggle) {
                toggle.checked = data.is_available;
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
        
        // Revert toggle state to original
        const toggle = document.querySelector(`[data-item-id="${itemId}"]`);
        if (toggle) {
            toggle.checked = !isAvailable;
        }
    });
}

function updateCardAvailability(card, isAvailable) {
    const badge = card.querySelector('.availability-badge');
    
    if (badge) {
        badge.className = `availability-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border ${
            isAvailable ? 'border-green-300 bg-green-50 text-green-700' : 'border-red-300 bg-red-50 text-red-700'
        }`;
        badge.innerHTML = `<i class="fas fa-${isAvailable ? 'check' : 'times'} mr-2"></i>${
            isAvailable ? 'Tersedia' : 'Habis'
        }`;
    }
    
    // Update toggle switch state
    const toggle = card.querySelector('.availability-toggle');
    if (toggle) {
        toggle.checked = isAvailable;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Page initialized
});
</script>
@endsection
