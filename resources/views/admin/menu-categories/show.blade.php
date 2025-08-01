@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Kategori: {{ $menuCategory->name }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.menu-categories.edit', $menuCategory) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Edit
            </a>
            <a href="{{ route('admin.menu-categories.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Informasi Kategori</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama</label>
                        <p class="text-lg text-gray-900">{{ $menuCategory->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="text-gray-900">{{ $menuCategory->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jumlah Item</label>
                        <p class="text-lg font-semibold text-blue-600">{{ $menuCategory->menuItems->count() }} item</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Dibuat</label>
                        <p class="text-gray-900">{{ $menuCategory->created_at->format('d M Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Terakhir Diupdate</label>
                        <p class="text-gray-900">{{ $menuCategory->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Item Menu dalam Kategori</h2>
                    <a href="{{ route('admin.menu.create', ['category' => $menuCategory->id]) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        Tambah Item
                    </a>
                </div>
                
                @if($menuCategory->menuItems->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($menuCategory->menuItems as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($item->image_url)
                                                    <img class="h-10 w-10 rounded-full object-cover mr-3" 
                                                         src="{{ $item->image_url }}" 
                                                         alt="{{ $item->name }}">
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $item->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <a href="{{ route('admin.menu.show', $item) }}" 
                                               class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                                            <a href="{{ route('admin.menu.edit', $item) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p class="text-lg">Belum ada item menu dalam kategori ini</p>
                        <a href="{{ route('admin.menu.create', ['category' => $menuCategory->id]) }}" 
                           class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Tambah Item Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
