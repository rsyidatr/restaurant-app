@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Pengguna</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Edit
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        @if($user->avatar)
                            <img class="h-20 w-20 rounded-full object-cover" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                        @else
                            <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-xl font-medium text-gray-700">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        @if($user->phone)
                            <p class="text-gray-600">{{ $user->phone }}</p>
                        @endif
                        <div class="mt-2 flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->role == 'admin') bg-red-100 text-red-800
                                @elseif($user->role == 'pelayan') bg-blue-100 text-blue-800
                                @elseif($user->role == 'koki') bg-orange-100 text-orange-800
                                @elseif($user->role == 'pelanggan') bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Personal</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                        <p class="text-gray-800">{{ $user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="text-gray-800">{{ $user->email }}</p>
                        @if($user->email_verified_at)
                            <span class="text-xs text-green-600">✓ Terverifikasi</span>
                        @else
                            <span class="text-xs text-red-600">✗ Belum terverifikasi</span>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nomor Telepon</label>
                        <p class="text-gray-800">{{ $user->phone ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Lahir</label>
                        <p class="text-gray-800">{{ $user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : '-' }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600">Alamat</label>
                        <p class="text-gray-800">{{ $user->address ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Bergabung</label>
                        <p class="text-gray-800">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Login</label>
                        <p class="text-gray-800">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Belum pernah login' }}</p>
                    </div>
                </div>
            </div>

            <!-- Staff Information -->
            @if(in_array($user->role, ['admin', 'pelayan', 'koki']))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Staff</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">ID Karyawan</label>
                        <p class="text-gray-800">{{ $user->employee_id ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Gaji</label>
                        <p class="text-gray-800">{{ $user->salary ? 'Rp ' . number_format($user->salary, 0, ',', '.') : '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Masuk</label>
                        <p class="text-gray-800">{{ $user->hire_date ? $user->hire_date->format('d/m/Y') : '-' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Shift</label>
                        <p class="text-gray-800">
                            @if($user->shift == 'pagi') Pagi (06:00 - 14:00)
                            @elseif($user->shift == 'siang') Siang (14:00 - 22:00)
                            @elseif($user->shift == 'malam') Malam (22:00 - 06:00)
                            @else -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Activity History -->
            @if($user->role == 'pelanggan')
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Pelanggan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $userStats['total_orders'] ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Total Pesanan</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($userStats['total_spent'] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">Total Pengeluaran</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $userStats['total_reservations'] ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Total Reservasi</p>
                    </div>
                </div>

                @if(isset($recentOrders) && $recentOrders->count() > 0)
                    <h4 class="font-medium text-gray-900 mb-3">Pesanan Terbaru</h4>
                    <div class="space-y-3">
                        @foreach($recentOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">Order #{{ $order->order_number }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->orderItems->count() }} items</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($order->status == 'completed') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="w-full {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-md">
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Akun
                            </button>
                        </form>
                    @endif

                    @if($user->phone)
                        <a href="tel:{{ $user->phone }}" 
                           class="w-full inline-block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Telepon
                        </a>
                        
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" 
                           target="_blank"
                           class="w-full inline-block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            WhatsApp
                        </a>
                    @endif
                    
                    <a href="mailto:{{ $user->email }}" 
                       class="w-full inline-block text-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        Email
                    </a>

                    @if($user->role == 'pelanggan')
                        <a href="{{ route('admin.orders.create', ['customer_id' => $user->id]) }}" 
                           class="w-full inline-block text-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                            Buat Pesanan
                        </a>
                        
                        <a href="{{ route('admin.reservations.create', ['customer_id' => $user->id]) }}" 
                           class="w-full inline-block text-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                            Buat Reservasi
                        </a>
                    @endif
                </div>
            </div>

            <!-- Account Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Akun</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Email:</span>
                        <span class="font-medium {{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Login Terakhir:</span>
                        <span class="font-medium text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            @if($user->phone || $user->email)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kontak</h3>
                
                <div class="space-y-3">
                    @if($user->phone)
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-sm text-gray-900">{{ $user->phone }}</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-900">{{ $user->email }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Danger Zone -->
            @if($user->id !== auth()->id())
            <div class="bg-white rounded-lg shadow p-6 border border-red-200">
                <h3 class="text-lg font-semibold text-red-800 mb-4">Danger Zone</h3>
                
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Hapus Pengguna
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
