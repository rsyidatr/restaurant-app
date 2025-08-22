@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kelola Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pengguna
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Pengguna</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-600">Admin</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['admins'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-concierge-bell text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Pelayan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pelayan'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-utensils text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-600">Koki</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['koki'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-purple-600">Pelanggan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['customers'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" 
                       id="searchUser" 
                       placeholder="Cari nama atau email..."
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <select id="roleFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pelayan" {{ request('role') == 'pelayan' ? 'selected' : '' }}>Pelayan</option>
                    <option value="koki" {{ request('role') == 'koki' ? 'selected' : '' }}>Koki</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Pelanggan</option>
                </select>
            </div>
            <div>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div>
                <button onclick="filterUsers()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pengguna
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bergabung
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Terakhir Login
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($user->avatar)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->role == 'admin') bg-red-100 text-red-800
                                @elseif($user->role == 'pelayan') bg-blue-100 text-blue-800
                                @elseif($user->role == 'koki') bg-orange-100 text-orange-800
                                @elseif($user->role == 'pelanggan') bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST" class="inline" id="toggle-form-{{ $user->id }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="button" 
                                            onclick="confirmToggleStatus('{{ $user->name }}', {{ $user->is_active ? 'true' : 'false' }}, document.getElementById('toggle-form-{{ $user->id }}'))"
                                            class="{{ $user->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}"
                                            title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" id="delete-form-{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete('{{ $user->name }}', document.getElementById('delete-form-{{ $user->id }}'))"
                                            class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pengguna</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada pengguna yang terdaftar.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.users.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Tambah Pengguna Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>

<script>
function filterUsers() {
    const search = document.getElementById('searchUser').value;
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('role');
    url.searchParams.delete('status');
    
    if (search) url.searchParams.set('search', search);
    if (role) url.searchParams.set('role', role);
    if (status) url.searchParams.set('status', status);
    
    window.location = url;
}

// Enter key support for search
document.getElementById('searchUser').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        filterUsers();
    }
});

// Enhanced delete confirmation
function confirmDelete(userName, form) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Pengguna</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus pengguna <strong>${userName}</strong>? 
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 mr-2">
                        Batal
                    </button>
                    <button onclick="submitDelete()" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    window.closeModal = function() {
        document.body.removeChild(modal);
    };
    
    window.submitDelete = function() {
        form.submit();
        document.body.removeChild(modal);
    };
    
    return false;
}

// Enhanced toggle status confirmation
function confirmToggleStatus(userName, currentStatus, form) {
    const action = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
    const actionColor = currentStatus ? 'red' : 'green';
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
    modal.innerHTML = `
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-${actionColor}-100">
                    <i class="fas fa-user-${currentStatus ? 'slash' : 'check'} text-${actionColor}-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Konfirmasi Status</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin ${action} pengguna <strong>${userName}</strong>?
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="closeStatusModal()" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 mr-2">
                        Batal
                    </button>
                    <button onclick="submitToggleStatus()" 
                            class="px-4 py-2 bg-${actionColor}-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-${actionColor}-700">
                        <i class="fas fa-user-${currentStatus ? 'slash' : 'check'} mr-1"></i> ${action.charAt(0).toUpperCase() + action.slice(1)}
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    window.closeStatusModal = function() {
        document.body.removeChild(modal);
    };
    
    window.submitToggleStatus = function() {
        form.submit();
        document.body.removeChild(modal);
    };
    
    return false;
}
</script>
@endsection
