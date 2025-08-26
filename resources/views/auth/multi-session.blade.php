@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Multi-Session Dashboard</h1>
                <div class="text-sm text-gray-600">
                    Total Sesi Aktif: <span class="font-semibold">{{ count($activeSessions) }}</span>
                </div>
            </div>

            <!-- Active Sessions -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Sesi Aktif</h2>
                
                @if(count($activeSessions) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($activeSessions as $guard => $session)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                        <span class="font-semibold text-gray-800 capitalize">{{ $guard }}</span>
                                    </div>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        {{ ucfirst($session['user_role']) }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2 mb-4">
                                    <p class="text-sm text-gray-600">
                                        <strong>Nama:</strong> {{ $session['user_name'] }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <strong>Email:</strong> {{ $session['user_email'] }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <strong>Login:</strong> {{ $session['login_time']->format('H:i - d/m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <strong>Durasi:</strong> {{ $session['login_time']->diffForHumans() }}
                                    </p>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('multi-session.switch', $guard) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm text-center">
                                        Buka Sesi
                                    </a>
                                    <form action="{{ route('multi-session.logout', $guard) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm"
                                                onclick="return confirm('Yakin ingin logout dari sesi {{ $guard }}?')">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <p>Tidak ada sesi aktif</p>
                    </div>
                @endif
            </div>

            <!-- Login Forms -->
            <div class="border-t border-gray-200 pt-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Login Sebagai</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Admin Login -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Admin
                        </h3>
                        <form action="{{ route('multi-session.login', 'admin') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <input type="email" name="email" placeholder="Email Admin" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="password" name="password" placeholder="Password" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">
                                    Login Admin
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Staff Login -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Staff
                        </h3>
                        <form action="{{ route('multi-session.login', 'staff') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <input type="email" name="email" placeholder="Email Staff" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                <input type="password" name="password" placeholder="Password" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                    Login Staff
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Customer Login -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Customer
                        </h3>
                        <form action="{{ route('multi-session.login', 'customer') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <input type="email" name="email" placeholder="Email Customer" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <input type="password" name="password" placeholder="Password" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-sm">
                                    Login Customer
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Regular Login -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                            </svg>
                            Regular
                        </h3>
                        <form action="{{ route('multi-session.login', 'web') }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <input type="email" name="email" placeholder="Email" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <input type="password" name="password" placeholder="Password" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm">
                                    Login Regular
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="border-t border-gray-200 pt-6 mt-8">
                <h3 class="font-semibold text-gray-800 mb-3">Petunjuk Penggunaan:</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Anda dapat login dengan beberapa akun sekaligus menggunakan guard yang berbeda</li>
                    <li>• Setiap guard (admin, staff, customer, regular) memiliki sesi terpisah</li>
                    <li>• Gunakan tombol "Buka Sesi" untuk beralih antar sesi aktif</li>
                    <li>• Logout dari sesi tertentu tidak akan mempengaruhi sesi lainnya</li>
                    <li>• Berguna untuk testing multiple roles atau mengelola akun berbeda</li>
                </ul>
            </div>
        </div>
        
        <!-- Testing Credentials Info -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Test Credentials</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                <div class="bg-white rounded p-3 border">
                    <h4 class="font-semibold text-blue-600 mb-2">Admin</h4>
                    <p><strong>Email:</strong> admin@test.com</p>
                    <p><strong>Password:</strong> password</p>
                </div>
                <div class="bg-white rounded p-3 border">
                    <h4 class="font-semibold text-green-600 mb-2">Pelayan</h4>
                    <p><strong>Email:</strong> waiter@test.com</p>
                    <p><strong>Password:</strong> password</p>
                </div>
                <div class="bg-white rounded p-3 border">
                    <h4 class="font-semibold text-orange-600 mb-2">Koki</h4>
                    <p><strong>Email:</strong> chef@test.com</p>
                    <p><strong>Password:</strong> password</p>
                </div>
                <div class="bg-white rounded p-3 border">
                    <h4 class="font-semibold text-purple-600 mb-2">Customer</h4>
                    <p><strong>Email:</strong> customer@test.com</p>
                    <p><strong>Password:</strong> password123</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto refresh active sessions every 30 seconds
setInterval(function() {
    if (document.hidden) return; // Don't refresh if tab is not active
    
    fetch('{{ route("multi-session.dashboard") }}')
        .then(response => response.text())
        .then(html => {
            // Update only the active sessions section
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newSessions = doc.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
            const currentSessions = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
            
            if (newSessions && currentSessions) {
                currentSessions.innerHTML = newSessions.innerHTML;
            }
        })
        .catch(error => console.log('Error refreshing sessions:', error));
}, 30000);
</script>
@endsection
