@extends('layouts.waiter_clean')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Pelayan</h1>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $todayOrders ?? 0 }}</h3>
                    <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
                </div>
            </div>
        </div>
        
        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $pendingOrders ?? 0 }}</h3>
                    <p class="text-sm text-gray-500">Pesanan Menunggu</p>
                </div>
            </div>
        </div>
        
        <!-- Processing Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-fire text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $processingOrders ?? 0 }}</h3>
                    <p class="text-sm text-gray-500">Sedang Diproses</p>
                </div>
            </div>
        </div>
        
        <!-- Available Tables -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chair text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $availableTables ?? 0 }}</h3>
                    <p class="text-sm text-gray-500">Meja Tersedia</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Welcome Message -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-600"><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelayan - Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">Dashboard Pelayan</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-green-600 mb-4">
                        <i class="fas fa-check-circle"></i> Login Berhasil!
                    </h2>
                    <p class="text-lg text-gray-700 mb-4">
                        Selamat datang di dashboard pelayan, <strong>{{ Auth::user()->name }}</strong>!
                    </p>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Pesanan Hari Ini
                                            </dt>
                                            <dd class="text-lg font-medium text-gray-900">
                                                {{ $todayOrders ?? 0 }}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Pesanan Menunggu
                                            </dt>
                                            <dd class="text-lg font-medium text-gray-900">
                                                {{ $pendingOrders ?? 0 }}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chair text-green-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Meja Tersedia
                                            </dt>
                                            <dd class="text-lg font-medium text-gray-900">
                                                {{ $availableTables ?? 0 }}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar-check text-purple-600 text-2xl"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Reservasi Hari Ini
                                            </dt>
                                            <dd class="text-lg font-medium text-gray-900">
                                                {{ $todayReservations ?? 0 }}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Navigasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('waiter.orders.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition-colors">
                                <i class="fas fa-shopping-cart text-2xl mb-2 block"></i>
                                Kelola Pesanan
                            </a>
                            <a href="{{ route('waiter.tables.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition-colors">
                                <i class="fas fa-chair text-2xl mb-2 block"></i>
                                Kelola Meja
                            </a>
                            <a href="{{ route('waiter.reservations.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center transition-colors">
                                <i class="fas fa-calendar-check text-2xl mb-2 block"></i>
                                Kelola Reservasi
                            </a>
                            <a href="#" class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg text-center transition-colors">
                                <i class="fas fa-chart-bar text-2xl mb-2 block"></i>
                                Laporan
                            </a>
                        </div>
                    </div>

                    <!-- Debug Info -->
                    <div class="mt-8 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Debug Info</h3>
                        <p><strong>User ID:</strong> {{ Auth::user()->id }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Role:</strong> {{ Auth::user()->role }}</p>
                        <p><strong>Login Time:</strong> {{ now() }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html></p>
    </div>
</div>
@endsection
