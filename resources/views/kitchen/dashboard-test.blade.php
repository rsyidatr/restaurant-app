<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Dashboard Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-6 px-4 space-y-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Kitchen Dashboard Test</h1>
                    <p class="text-gray-600 mt-2">Clean design version - {{ now()->format('d M Y') }}</p>
                </div>
                <div class="text-orange-500">
                    <i class="fas fa-fire text-3xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Pesanan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $todayOrders ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Hari ini</p>
                    </div>
                    <div class="text-gray-400">
                        <i class="fas fa-calendar-day text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Menunggu</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $pendingOrders ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Antrian masak</p>
                    </div>
                    <div class="text-gray-400">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Sedang Dimasak</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $processingOrders ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Dalam proses</p>
                    </div>
                    <div class="text-gray-400">
                        <i class="fas fa-fire text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Siap Disajikan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $readyOrders ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Siap diantar</p>
                    </div>
                    <div class="text-gray-400">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <p class="text-sm text-gray-500 mt-1">Clean design without colored backgrounds</p>
            </div>
            <div class="p-6">
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900">#{{ $order->id }} - {{ $order->user->name ?? 'Guest' }}</h4>
                            <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-300 mb-4">
                            <i class="fas fa-utensils text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No orders today</h3>
                        <p class="text-gray-500">Orders will appear here when received</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Success Message -->
        <div class="bg-white border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="text-green-500 mr-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-green-800 font-semibold">✅ Clean Kitchen Dashboard Design Successful!</h3>
                    <p class="text-green-700 text-sm mt-1">
                        Redesigned without colored backgrounds, following UX laws for better user experience.
                        User: {{ Auth::user()->name ?? 'Unknown' }} | Role: {{ Auth::user()->role ?? 'Unknown' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
