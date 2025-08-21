@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Meja {{ $table->table_number }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.tables.show', $table) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Detail
            </a>
            <a href="{{ route('admin.tables.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.tables.update', $table) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label for="table_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Meja *</label>
                        <input type="text" 
                               id="table_number" 
                               name="table_number" 
                               value="{{ old('table_number', $table->table_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('table_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Kapasitas *</label>
                        <select id="capacity" 
                                name="capacity" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Pilih kapasitas</option>
                            <option value="2" {{ old('capacity', $table->capacity) == '2' ? 'selected' : '' }}>2 orang</option>
                            <option value="4" {{ old('capacity', $table->capacity) == '4' ? 'selected' : '' }}>4 orang</option>
                            <option value="6" {{ old('capacity', $table->capacity) == '6' ? 'selected' : '' }}>6 orang</option>
                            <option value="8" {{ old('capacity', $table->capacity) == '8' ? 'selected' : '' }}>8 orang</option>
                            <option value="10" {{ old('capacity', $table->capacity) == '10' ? 'selected' : '' }}>10 orang</option>
                            <option value="15" {{ old('capacity', $table->capacity) == '15' ? 'selected' : '' }}>15 orang</option>
                            <option value="20" {{ old('capacity', $table->capacity) == '20' ? 'selected' : '' }}>20 orang</option>
                            <option value="30" {{ old('capacity', $table->capacity) == '30' ? 'selected' : '' }}>30 orang (VIP)</option>
                        </select>
                        </select>
                        @error('capacity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <select id="location" 
                                name="location" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih lokasi</option>
                            <option value="indoor" {{ old('location', $table->location) == 'indoor' ? 'selected' : '' }}>Indoor</option>
                            <option value="outdoor" {{ old('location', $table->location) == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                            <option value="vip" {{ old('location', $table->location) == 'vip' ? 'selected' : '' }}>VIP Room</option>
                            <option value="terrace" {{ old('location', $table->location) == 'terrace' ? 'selected' : '' }}>Terrace</option>
                            <option value="balcony" {{ old('location', $table->location) == 'balcony' ? 'selected' : '' }}>Balcony</option>
                        </select>
                        @error('location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="available" {{ old('status', $table->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="occupied" {{ old('status', $table->status) == 'occupied' ? 'selected' : '' }}>Terisi</option>
                            <option value="reserved" {{ old('status', $table->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            <option value="maintenance" {{ old('status', $table->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Deskripsi tambahan tentang meja ini..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $table->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fitur Khusus</label>
                        <div class="space-y-2">
                            @php
                                $currentFeatures = old('features', json_decode($table->features ?? '[]', true));
                            @endphp
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="features[]" 
                                       value="window_view"
                                       {{ in_array('window_view', $currentFeatures) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Pemandangan Jendela</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="features[]" 
                                       value="power_outlet"
                                       {{ in_array('power_outlet', $currentFeatures) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Stop Kontak</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="features[]" 
                                       value="wheelchair_accessible"
                                       {{ in_array('wheelchair_accessible', $currentFeatures) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Akses Kursi Roda</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="features[]" 
                                       value="privacy"
                                       {{ in_array('privacy', $currentFeatures) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Privasi Tinggi</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="features[]" 
                                       value="air_conditioning"
                                       {{ in_array('air_conditioning', $currentFeatures) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">AC</span>
                            </label>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Preview Meja</h4>
                        <div id="tablePreview" class="w-16 h-16 mx-auto border-2 border-gray-400 rounded-lg flex items-center justify-center">
                            <span class="text-xs text-gray-600">{{ $table->table_number }}</span>
                        </div>
                        <div id="previewInfo" class="text-center mt-2 text-sm text-gray-600">
                            <!-- Preview info will be updated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.tables.show', $table) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Perbarui Meja
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updatePreview() {
    const tableNumber = document.getElementById('table_number').value || '?';
    const capacity = document.getElementById('capacity').value || '?';
    const location = document.getElementById('location').value;
    const status = document.getElementById('status').value;
    
    // Update preview visual
    const preview = document.getElementById('tablePreview');
    const previewInfo = document.getElementById('previewInfo');
    
    preview.innerHTML = `<span class="text-sm font-bold">${tableNumber}</span>`;
    
    // Update colors based on status
    if (status === 'available') {
        preview.className = 'w-16 h-16 mx-auto border-2 border-green-500 bg-green-50 rounded-lg flex items-center justify-center';
    } else if (status === 'occupied') {
        preview.className = 'w-16 h-16 mx-auto border-2 border-red-500 bg-red-50 rounded-lg flex items-center justify-center';
    } else if (status === 'reserved') {
        preview.className = 'w-16 h-16 mx-auto border-2 border-yellow-500 bg-yellow-50 rounded-lg flex items-center justify-center';
    } else if (status === 'maintenance') {
        preview.className = 'w-16 h-16 mx-auto border-2 border-gray-500 bg-gray-50 rounded-lg flex items-center justify-center';
    }
    
    // Update info
    let info = `${capacity} orang`;
    if (location) {
        const locationText = {
            'indoor': 'Indoor',
            'outdoor': 'Outdoor',
            'vip': 'VIP Room',
            'terrace': 'Terrace',
            'balcony': 'Balcony'
        };
        info += ` - ${locationText[location]}`;
    }
    
    previewInfo.textContent = info;
}

// Add event listeners for real-time preview
document.getElementById('table_number').addEventListener('input', updatePreview);
document.getElementById('capacity').addEventListener('change', updatePreview);
document.getElementById('location').addEventListener('change', updatePreview);
document.getElementById('status').addEventListener('change', updatePreview);

// Initial preview update
document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection
