@extends('layouts.staff')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Login Staff Restaurant
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Khusus untuk Admin, Chef, dan Waiter
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('staff.login.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="remember" value="true">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Email Address"
                           value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Password">
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" 
                           name="remember" 
                           type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Ingat saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('staff.password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Lupa password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Masuk
                </button>
            </div>

            <!-- Role Selection -->
            <div class="mt-6">
                <p class="text-center text-sm text-gray-600 mb-4">
                    Pilih posisi Anda:
                </p>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="admin" class="sr-only" required>
                        <div class="border-2 border-gray-300 rounded-lg p-3 text-center hover:border-blue-500 focus-within:border-blue-500 transition-colors">
                            <svg class="w-6 h-6 mx-auto mb-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="text-sm font-medium">Admin</span>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="chef" class="sr-only">
                        <div class="border-2 border-gray-300 rounded-lg p-3 text-center hover:border-green-500 focus-within:border-green-500 transition-colors">
                            <svg class="w-6 h-6 mx-auto mb-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            <span class="text-sm font-medium">Chef</span>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="waiter" class="sr-only">
                        <div class="border-2 border-gray-300 rounded-lg p-3 text-center hover:border-purple-500 focus-within:border-purple-500 transition-colors">
                            <svg class="w-6 h-6 mx-auto mb-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-sm font-medium">Waiter</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Pelanggan? 
                    <a href="{{ route('home') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Kembali ke halaman utama
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
// Handle role selection styling
document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Reset all borders
        document.querySelectorAll('input[name="role"]').forEach(r => {
            r.parentElement.querySelector('div').classList.remove('border-blue-500', 'border-green-500', 'border-purple-500', 'bg-blue-50', 'bg-green-50', 'bg-purple-50');
            r.parentElement.querySelector('div').classList.add('border-gray-300');
        });
        
        // Highlight selected
        const color = this.value === 'admin' ? 'blue' : this.value === 'chef' ? 'green' : 'purple';
        this.parentElement.querySelector('div').classList.remove('border-gray-300');
        this.parentElement.querySelector('div').classList.add(`border-${color}-500`, `bg-${color}-50`);
    });
});
</script>
@endsection
