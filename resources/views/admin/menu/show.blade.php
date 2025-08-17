@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Menu Item</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.menu.edit', $menuItem) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Edit
            </a>
            <a href="{{ route('admin.menu.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informasi Menu -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Menu</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nama Menu</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $menuItem->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Kategori</label>
                    <p class="text-gray-800">{{ $menuItem->category->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Deskripsi</label>
                    <p class="text-gray-800">{{ $menuItem->description ?? 'Tidak ada deskripsi' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Harga</label>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($menuItem->price, 0, ',', '.') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Status</label>
                    @if($menuItem->is_available)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Tersedia
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Tidak Tersedia
                        </span>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Dibuat</label>
                    <p class="text-gray-800">{{ $menuItem->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Terakhir Diperbarui</label>
                    <p class="text-gray-800">{{ $menuItem->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Gambar Menu -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Gambar Menu</h2>
            
            @if($menuItem->image_url)
                @php
                    $imagePath = $menuItem->image_url;
                    if (str_contains($imagePath, 'menu/')) {
                        $imageUrl = asset('storage/' . $imagePath);
                    } else {
                        $imageUrl = asset('images/menu/' . $imagePath);
                    }
                @endphp
                <img src="{{ $imageUrl }}" 
                     alt="{{ $menuItem->name }}" 
                     class="w-full h-64 object-cover rounded-lg border border-gray-300"
                     onerror="this.src='{{ asset('images/menu/default-menu.jpg') }}'">
            @else
                <div class="w-full h-64 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Tidak ada gambar</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistik Penjualan -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistik Penjualan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600">Total Terjual</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalOrdered ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Total Pendapatan</p>
                        <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Rating</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $averageRating ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-4">
        <form action="{{ route('admin.menu.destroy', $menuItem) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                Hapus Menu
            </button>
        </form>
    </div>
</div>
@endsection
