@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Menu</h1>
    </div>

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
        {{-- <div class="flex space-x-4">
            <select class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-black focus:border-black" 
                    onchange="filterByCategory(this.value)">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div> --}}
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
                            <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" class="inline" id="delete-form-{{ $item->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin menghapus menu {{ addslashes($item->name) }}?')"
                                        class="text-red-600 hover:text-red-800 inline-block" 
                                        title="Hapus Menu">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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

@push('scripts')
<script>
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
            
            // Use new notification system
            notificationManager.success('Status menu berhasil diperbarui');
        } else {
            notificationManager.error('Gagal memperbarui status menu');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        notificationManager.error('Terjadi kesalahan saat memperbarui status');
    });
}

// Filter function
function filterByCategory(categoryId) {
    // Get current URL
    let url = new URL(window.location.href);
    
    // Update or remove category parameter
    if (categoryId && categoryId !== '') {
        url.searchParams.set('category', categoryId);
    } else {
        url.searchParams.delete('category');
    }
    
    // Remove page parameter to go back to first page when filtering
    url.searchParams.delete('page');
    
    // Navigate to the new URL
    window.location.href = url.toString();
}
</script>
@endpush
@endsection
