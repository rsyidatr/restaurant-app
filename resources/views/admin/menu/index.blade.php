@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Menu</h1>
    </div>

    <!-- Alert Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

<div class="space-y-6">
    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <div class="flex space-x-4">
            <a href="{{ route('admin.menu.create') }}" 
               class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Menu
            </a>
            <a href="{{ route('admin.menu-categories.index') }}" 
               class="bg-white text-black border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-list mr-2"></i>Kelola Kategori
            </a>
        </div>
        
        <!-- Filter -->
        <div class="flex space-x-4">
            <select class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-black focus:border-black" 
                    onchange="filterByCategory(this.value)">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <!-- Menu Items Grid -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item Menu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($menuItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($item->image_url)
                                    <img class="h-12 w-12 rounded-lg object-cover mr-4" 
                                         src="{{ Storage::url($item->image_url) }}" 
                                         alt="{{ $item->name }}">
                                @else
                                    <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                {{ $item->category->name ?? 'Tanpa Kategori' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleAvailability({{ $item->id }})" 
                                    class="inline-flex px-2 py-1 text-xs font-medium rounded-full transition-colors
                                        {{ $item->is_available ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                <span id="status-{{ $item->id }}">
                                    {{ $item->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.menu.show', $item) }}" 
                               class="text-blue-600 hover:text-blue-800 inline-block" 
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.menu.edit', $item) }}" 
                               class="text-yellow-600 hover:text-yellow-800 inline-block" 
                               title="Edit Menu">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    onclick="alert('Button clicked for item: {{ $item->id }}'); deleteMenuItem({{ $item->id }}, {{ json_encode($item->name) }})" 
                                    class="text-red-600 hover:text-red-800 inline-block" 
                                    title="Hapus Menu"
                                    id="delete-btn-{{ $item->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-utensils text-gray-300 text-4xl mb-4"></i>
                            <p>Belum ada item menu.</p>
                            <a href="{{ route('admin.menu.create') }}" 
                               class="mt-2 inline-block text-black hover:underline">
                                Tambah item menu pertama Anda
                            </a>
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4 transform transition-all duration-300 scale-95" id="deleteModalContent">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="ml-3 text-lg font-medium text-gray-900">Konfirmasi Hapus Menu</h3>
        </div>
        
        <div class="mb-6">
            <p class="text-gray-600 mb-2">Anda yakin ingin menghapus menu item berikut?</p>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="font-semibold text-gray-900" id="deleteItemName"></p>
                <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan dan akan menghapus menu dari daftar customer.</p>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button onclick="confirmDelete()" 
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i>Hapus Menu
            </button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 transform translate-x-full transition-transform duration-300 z-50">
    <div class="bg-white border-l-4 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i id="toastIcon" class="text-xl"></i>
            </div>
            <div class="ml-3">
                <p id="toastMessage" class="text-sm font-medium"></p>
            </div>
            <button onclick="hideToast()" class="ml-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let deleteItemId = null;
let deleteItemName = null;

// Toggle availability function
function toggleAvailability(itemId) {
    fetch(`/admin/menu/${itemId}/toggle-availability`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const statusElement = document.getElementById(`status-${itemId}`);
            const buttonElement = statusElement.parentElement;
            
            if (data.is_available) {
                statusElement.textContent = 'Tersedia';
                buttonElement.className = 'inline-flex px-2 py-1 text-xs font-medium rounded-full transition-colors bg-green-100 text-green-800 hover:bg-green-200';
            } else {
                statusElement.textContent = 'Tidak Tersedia';
                buttonElement.className = 'inline-flex px-2 py-1 text-xs font-medium rounded-full transition-colors bg-red-100 text-red-800 hover:bg-red-200';
            }
            
            showToast('Status menu berhasil diperbarui', 'success');
        } else {
            showToast('Gagal memperbarui status menu', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memperbarui status', 'error');
    });
}

// Delete menu item functions
function deleteMenuItem(itemId, itemName) {
    console.log('Delete button clicked:', itemId, itemName); // Debug log
    
    deleteItemId = itemId;
    deleteItemName = itemName;
    
    // Set the item name in modal
    const deleteItemNameElement = document.getElementById('deleteItemName');
    if (deleteItemNameElement) {
        deleteItemNameElement.textContent = itemName;
    } else {
        console.error('deleteItemName element not found');
        return;
    }
    
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    if (!modal || !modalContent) {
        console.error('Modal elements not found');
        return;
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 150);
    
    deleteItemId = null;
    deleteItemName = null;
}

function confirmDelete() {
    console.log('Confirm delete clicked:', deleteItemId); // Debug log
    
    if (!deleteItemId) {
        console.error('No item ID to delete');
        return;
    }
    
    // Show loading state
    const deleteButton = document.querySelector('#deleteModal button[onclick="confirmDelete()"]');
    if (deleteButton) {
        const originalText = deleteButton.innerHTML;
        deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghapus...';
        deleteButton.disabled = true;
    }
    
    try {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/menu/${deleteItemId}`;
        form.style.display = 'none'; // Hide form
        
        // Add method field for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Add CSRF token
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        form.appendChild(tokenInput);
        
        console.log('Form created with action:', form.action);
        console.log('CSRF token:', tokenInput.value);
        console.log('Method input:', methodInput.value);
        
        // Append to body and submit
        document.body.appendChild(form);
        
        console.log('Submitting form to:', form.action); // Debug log
        form.submit();
        
    } catch (error) {
        console.error('Error submitting delete form:', error);
        showToast('Terjadi kesalahan saat menghapus menu', 'error');
        
        // Restore button state
        if (deleteButton) {
            deleteButton.innerHTML = '<i class="fas fa-trash mr-2"></i>Hapus Menu';
            deleteButton.disabled = false;
        }
    }
}

// Toast notification functions
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const icon = document.getElementById('toastIcon');
    const messageEl = document.getElementById('toastMessage');
    const borderClass = type === 'success' ? 'border-green-400' : 'border-red-400';
    const iconClass = type === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500';
    
    // Set content
    messageEl.textContent = message;
    icon.className = iconClass;
    toast.firstElementChild.className = `bg-white ${borderClass} rounded-lg shadow-lg p-4 max-w-sm`;
    
    // Show toast
    toast.classList.remove('translate-x-full');
    toast.classList.add('translate-x-0');
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.classList.remove('translate-x-0');
    toast.classList.add('translate-x-full');
}

// Filter function
function filterByCategory(categoryId) {
    let url = new URL(window.location);
    if (categoryId) {
        url.searchParams.set('category', categoryId);
    } else {
        url.searchParams.delete('category');
    }
    window.location = url;
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Show success message if exists
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('success') }}', 'success');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('error') }}', 'error');
    });
@endif

// Debug: Log all delete buttons when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, checking delete buttons...');
    const deleteButtons = document.querySelectorAll('[id^="delete-btn-"]');
    console.log('Found delete buttons:', deleteButtons.length);
    deleteButtons.forEach(button => {
        console.log('Button:', button.id, 'onclick:', button.getAttribute('onclick'));
    });
});
</script>
@endpush
@endsection
