@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Pengguna</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Detail
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Personal</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" 
                               id="date_of_birth" 
                               name="date_of_birth" 
                               value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('date_of_birth')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Account Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Akun</h3>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                        <select id="role" 
                                name="role" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pelayan" {{ old('role', $user->role) == 'pelayan' ? 'selected' : '' }}>Pelayan</option>
                            <option value="koki" {{ old('role', $user->role) == 'koki' ? 'selected' : '' }}>Koki</option>
                            <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Pelanggan</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                        <input type="file" 
                               id="avatar" 
                               name="avatar" 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('avatar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    </div>

                    @if($user->avatar)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                            <img src="{{ $user->avatar }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-24 h-24 rounded-full object-cover border border-gray-300">
                        </div>
                    @endif

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Aktifkan akun</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="email_verified" 
                               name="email_verified" 
                               value="1"
                               {{ old('email_verified', $user->email_verified_at ? true : false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="email_verified" class="ml-2 text-sm text-gray-700">Email sudah terverifikasi</label>
                    </div>

                    <!-- Preview Avatar -->
                    <div id="avatarPreview" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview Foto Baru</label>
                        <img id="previewImage" class="w-24 h-24 rounded-full object-cover border border-gray-300" alt="Preview">
                    </div>
                </div>
            </div>

            <!-- Staff Specific Information -->
            <div id="staffInfo" class="{{ in_array($user->role, ['admin', 'pelayan', 'koki']) ? '' : 'hidden' }} mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Staff</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">ID Karyawan</label>
                        <input type="text" 
                               id="employee_id" 
                               name="employee_id" 
                               value="{{ old('employee_id', $user->employee_id) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('employee_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">Gaji</label>
                        <input type="number" 
                               id="salary" 
                               name="salary" 
                               value="{{ old('salary', $user->salary) }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('salary')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Masuk</label>
                        <input type="date" 
                               id="hire_date" 
                               name="hire_date" 
                               value="{{ old('hire_date', $user->hire_date?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('hire_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="shift" class="block text-sm font-medium text-gray-700 mb-2">Shift</label>
                        <select id="shift" 
                                name="shift" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Shift</option>
                            <option value="pagi" {{ old('shift', $user->shift) == 'pagi' ? 'selected' : '' }}>Pagi (06:00 - 14:00)</option>
                            <option value="siang" {{ old('shift', $user->shift) == 'siang' ? 'selected' : '' }}>Siang (14:00 - 22:00)</option>
                            <option value="malam" {{ old('shift', $user->shift) == 'malam' ? 'selected' : '' }}>Malam (22:00 - 06:00)</option>
                        </select>
                        @error('shift')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Perbarui Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Show/hide staff info based on role
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const staffInfo = document.getElementById('staffInfo');
    
    if (role === 'admin' || role === 'pelayan' || role === 'koki') {
        staffInfo.classList.remove('hidden');
    } else {
        staffInfo.classList.add('hidden');
    }
});

// Avatar preview
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('avatarPreview');
    const previewImage = document.getElementById('previewImage');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endsection
