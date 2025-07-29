@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-amber-50 to-orange-50">
    <div class="max-w-md w-full px-6 py-8 bg-white rounded-xl shadow-lg">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-light text-orange-800">Welcome Back</h1>
            <p class="text-orange-600/60 mt-2">Please sign in to continue</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
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
                    autofocus
                    placeholder="your@email.com"
                >
                @error('email')
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
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="rounded border-orange-300 text-orange-600 focus:ring-orange-500"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label class="ml-2 text-sm text-orange-600" for="remember">
                        Remember me
                    </label>
                </div>
                <a href="{{ route('register') }}" class="text-sm text-orange-600 hover:text-orange-800 transition duration-150 ease-in-out">
                    Register
                </a>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-150 ease-in-out transform hover:scale-[1.02] mb-4">
                Sign in
            </button>
        </form>
        
        <div class="text-center">
            <span class="text-orange-600/60">Don't have an account?</span>
            <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-800 font-medium ml-2">Register now</a>
        </div>
    </div>
</div>
@endsection
