@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-amber-50 to-orange-50">
    <div class="max-w-md w-full px-6 py-8 bg-white rounded-xl shadow-lg">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-light text-orange-800">Create Account</h1>
            <p class="text-orange-600/60 mt-2">Join our restaurant community</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-orange-700 text-sm mb-2" for="name">
                    Full Name
                </label>
                <input 
                    class="w-full px-4 py-3 rounded-lg bg-orange-50 border border-orange-100 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 transition duration-150 ease-in-out
                    @error('name') border-red-300 bg-red-50 @enderror"
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    autofocus
                    placeholder="John Doe"
                >
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-orange-700 text-sm mb-2" for="email">
                    Email Address
                </label>
                <input 
                    class="w-full px-4 py-3 rounded-lg bg-orange-50 border border-orange-100 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 transition duration-150 ease-in-out
                    @error('email') border-red-300 bg-red-50 @enderror"
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="your@email.com"
                >
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-orange-700 text-sm mb-2" for="role">
                    Select Role
                </label>
                <select 
                    class="w-full px-4 py-3 rounded-lg bg-orange-50 border border-orange-100 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 transition duration-150 ease-in-out
                    @error('role') border-red-300 bg-red-50 @enderror"
                    id="role"
                    name="role"
                    required
                >
                    <option value="" disabled selected>Choose your role</option>
                    <option value="pelanggan" {{ old('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                    <option value="pelayan" {{ old('role') == 'pelayan' ? 'selected' : '' }}>Pelayan</option>
                    <option value="koki" {{ old('role') == 'koki' ? 'selected' : '' }}>Koki</option>
                </select>
                @error('role')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-orange-700 text-sm mb-2" for="password">
                    Password
                </label>
                <input 
                    class="w-full px-4 py-3 rounded-lg bg-orange-50 border border-orange-100 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 transition duration-150 ease-in-out
                    @error('password') border-red-300 bg-red-50 @enderror"
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-orange-700 text-sm mb-2" for="password-confirm">
                    Confirm Password
                </label>
                <input 
                    class="w-full px-4 py-3 rounded-lg bg-orange-50 border border-orange-100 focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 transition duration-150 ease-in-out"
                    id="password-confirm"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                >
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-150 ease-in-out transform hover:scale-[1.02] mb-4">
                Create Account
            </button>
        </form>

        <div class="text-center">
            <span class="text-orange-600/60">Already have an account?</span>
            <a href="{{ route('login') }}" class="text-orange-600 hover:text-orange-800 font-medium ml-2">Sign in</a>
        </div>
    </div>
</div>
@endsection
