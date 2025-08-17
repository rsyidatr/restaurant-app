@extends('layouts.kitchen')

@section('title', 'Ketersediaan Menu')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ketersediaan Menu</h1>
                <p class="text-gray-600 mt-1">Kelola status ketersediaan setiap menu</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-utensils text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('kitchen.menu.index') }}" class="flex flex-wrap gap-4 items-end">
            <!-- Category Filter -->
            <div class="flex-1 min-w-48">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" id="category" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Availability Filter -->
            <div class="flex-1 min-w-48">
                <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Status Ketersediaan</label>
                <select name="availability" id="availability" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div>
                <button type="submit" 
                        class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>

            <!-- Clear Filter -->
            <div>
                <a href="{{ route('kitchen.menu.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Aksi Massal</h2>
            <div class="text-sm text-gray-500">
                <span id="selectedCount">0</span> item dipilih
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="bulkUpdateAvailability(true)" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors disabled:opacity-50" 
                    id="makeAvailableBtn" disabled>
                <i class="fas fa-check mr-2"></i>Tandai Tersedia
            </button>
            <button onclick="bulkUpdateAvailability(false)" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors disabled:opacity-50" 
                    id="makeUnavailableBtn" disabled>
                <i class="fas fa-times mr-2"></i>Tandai Tidak Tersedia
            </button>
        </div>
    </div>

    <!-- Menu Items -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Menu</h2>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="selectAll" 
                           class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    <label for="selectAll" class="text-sm text-gray-700">Pilih Semua</label>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAllHeader" 
                                   class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($menuItems as $item)
                    <tr class="hover:bg-gray-50" id="row-{{ $item->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                                   class="item-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($item->image_url)
                                    <img class="h-10 w-10 rounded-full object-cover" 
                                         src="{{ asset('storage/' . $item->image_url) }}" 
                                         alt="{{ $item->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-utensils text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                {{ $item->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleAvailability({{ $item->id }})" 
                                    class="inline-flex px-3 py-1 text-xs font-medium rounded-full transition-colors
                                           {{ $item->is_available 
                                              ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                                              : 'bg-red-100 text-red-800 hover:bg-red-200' }}" 
                                    id="status-{{ $item->id }}">
                                <i class="fas {{ $item->is_available ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                {{ $item->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('kitchen.menu.show', $item) }}" 
                               class="text-orange-600 hover:text-orange-900 mr-3">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                            <button onclick="toggleAvailability({{ $item->id }})" 
                                    class="text-gray-600 hover:text-gray-900">
                                <i class="fas fa-toggle-on mr-1"></i>Toggle
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-utensils text-gray-300 text-4xl mb-4"></i>
                                <p>Tidak ada item menu yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($menuItems->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $menuItems->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    let selectedItems = [];

    // Select all functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedItems();
    });

    document.getElementById('selectAllHeader').addEventListener('change', function() {
        document.getElementById('selectAll').checked = this.checked;
        document.getElementById('selectAll').dispatchEvent(new Event('change'));
    });

    // Individual checkbox functionality
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedItems);
    });

    function updateSelectedItems() {
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        selectedItems = Array.from(checkboxes).map(cb => cb.value);
        
        document.getElementById('selectedCount').textContent = selectedItems.length;
        
        const bulkButtons = ['makeAvailableBtn', 'makeUnavailableBtn'];
        bulkButtons.forEach(btnId => {
            document.getElementById(btnId).disabled = selectedItems.length === 0;
        });
    }

    function toggleAvailability(menuId) {
        fetch(`/kitchen/menu/${menuId}/toggle-availability`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                // Update status button
                const statusBtn = document.getElementById(`status-${menuId}`);
                if (data.is_available) {
                    statusBtn.className = 'inline-flex px-3 py-1 text-xs font-medium rounded-full transition-colors bg-green-100 text-green-800 hover:bg-green-200';
                    statusBtn.innerHTML = '<i class="fas fa-check mr-1"></i>Tersedia';
                } else {
                    statusBtn.className = 'inline-flex px-3 py-1 text-xs font-medium rounded-full transition-colors bg-red-100 text-red-800 hover:bg-red-200';
                    statusBtn.innerHTML = '<i class="fas fa-times mr-1"></i>Tidak Tersedia';
                }
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    }

    function bulkUpdateAvailability(isAvailable) {
        if (selectedItems.length === 0) {
            showToast('Pilih minimal satu item menu', 'error');
            return;
        }

        const action = isAvailable ? 'tersedia' : 'tidak tersedia';
        if (confirm(`Tandai ${selectedItems.length} item menu sebagai ${action}?`)) {
            fetch('/kitchen/menu/bulk-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    menu_ids: selectedItems,
                    availability: isAvailable
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    location.reload();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan', 'error');
            });
        }
    }
</script>
@endpush
@endsection
