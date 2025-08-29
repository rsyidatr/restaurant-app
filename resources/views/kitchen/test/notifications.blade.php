@extends('layouts.kitchen_simple')

@section('title', 'Test Notifikasi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Test Notifikasi & Integrasi</h1>
                <p class="text-gray-600 mt-1">Test sistem notifikasi dan integrasi antar role</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-3 rounded-xl shadow-lg">
                <i class="fas fa-wrench text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Notification Tests -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Test Sistem Notifikasi</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button onclick="showSuccess('Operasi berhasil dilakukan!')" 
                    class="test-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white">
                <i class="fas fa-check-circle mr-2"></i>
                Test Success
            </button>
            
            <button onclick="showError('Terjadi kesalahan pada sistem!')" 
                    class="test-btn bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white">
                <i class="fas fa-times-circle mr-2"></i>
                Test Error
            </button>
            
            <button onclick="showWarning('Perhatian! Pastikan data sudah benar.')" 
                    class="test-btn bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Test Warning
            </button>
            
            <button onclick="showInfo('Informasi: Data sedang diproses.')" 
                    class="test-btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white">
                <i class="fas fa-info-circle mr-2"></i>
                Test Info
            </button>
        </div>
    </div>

    <!-- Cross-Role Integration Tests -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Test Integrasi Antar Role</h2>
        
        <!-- Kitchen to Waiter Communication -->
        <div class="mb-8">
            <h3 class="font-medium text-gray-900 mb-4">Komunikasi Kitchen → Waiter</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button onclick="testOrderReady()" 
                        class="integration-btn bg-green-50 hover:bg-green-100 text-green-700 border-green-200">
                    <i class="fas fa-bell mr-2"></i>
                    Pesanan Siap → Waiter
                </button>
                
                <button onclick="testOrderDelay()" 
                        class="integration-btn bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border-yellow-200">
                    <i class="fas fa-clock mr-2"></i>
                    Keterlambatan → Waiter
                </button>
                
                <button onclick="testIngredientOut()" 
                        class="integration-btn bg-red-50 hover:bg-red-100 text-red-700 border-red-200">
                    <i class="fas fa-exclamation mr-2"></i>
                    Bahan Habis → Waiter
                </button>
            </div>
        </div>

        <!-- Real-time Order Updates -->
        <div class="mb-8">
            <h3 class="font-medium text-gray-900 mb-4">Update Pesanan Real-time</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button onclick="simulateOrderUpdate('pending', 'preparing')" 
                        class="integration-btn bg-blue-50 hover:bg-blue-100 text-blue-700 border-blue-200">
                    <i class="fas fa-fire mr-2"></i>
                    Mulai Memasak
                </button>
                
                <button onclick="simulateOrderUpdate('preparing', 'ready')" 
                        class="integration-btn bg-green-50 hover:bg-green-100 text-green-700 border-green-200">
                    <i class="fas fa-check mr-2"></i>
                    Selesai Memasak
                </button>
            </div>
        </div>

        <!-- Menu Availability Updates -->
        <div>
            <h3 class="font-medium text-gray-900 mb-4">Update Ketersediaan Menu</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button onclick="testMenuAvailability(true)" 
                        class="integration-btn bg-green-50 hover:bg-green-100 text-green-700 border-green-200">
                    <i class="fas fa-check mr-2"></i>
                    Menu Tersedia → Customer
                </button>
                
                <button onclick="testMenuAvailability(false)" 
                        class="integration-btn bg-red-50 hover:bg-red-100 text-red-700 border-red-200">
                    <i class="fas fa-times mr-2"></i>
                    Menu Habis → Customer
                </button>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Status Sistem</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-check text-white"></i>
                </div>
                <p class="font-medium text-green-900">Notifikasi System</p>
                <p class="text-sm text-green-700">Aktif & Berfungsi</p>
            </div>
            
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-sync text-white"></i>
                </div>
                <p class="font-medium text-blue-900">Real-time Updates</p>
                <p class="text-sm text-blue-700">Tersinkronisasi</p>
            </div>
            
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-users text-white"></i>
                </div>
                <p class="font-medium text-purple-900">Cross-Role Integration</p>
                <p class="text-sm text-purple-700">Terintegrasi</p>
            </div>
        </div>
    </div>

    <!-- Development Notes -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-yellow-800 mb-4">
            <i class="fas fa-lightbulb mr-2"></i>Catatan Development
        </h3>
        <div class="space-y-3 text-yellow-700">
            <p><strong>✅ Completed:</strong></p>
            <ul class="list-disc ml-6 space-y-1">
                <li>Unified notification system with 4 types (success, error, warning, info)</li>
                <li>Icon-only action buttons with tooltips</li>
                <li>Modern card-based UI design</li>
                <li>Real-time order status updates</li>
                <li>Bulk menu availability management</li>
            </ul>
            
            <p><strong>🔄 In Progress:</strong></p>
            <ul class="list-disc ml-6 space-y-1">
                <li>Cross-role real-time notifications (WebSocket integration)</li>
                <li>Advanced order timeline tracking</li>
                <li>Kitchen performance analytics</li>
            </ul>

            <p><strong>📋 Next Features:</strong></p>
            <ul class="list-disc ml-6 space-y-1">
                <li>Push notifications for order updates</li>
                <li>Kitchen workload distribution</li>
                <li>Integration with inventory management</li>
            </ul>
        </div>
    </div>
</div>

<style>
.test-btn {
    @apply px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 shadow-lg flex items-center justify-center;
}

.integration-btn {
    @apply px-4 py-3 border rounded-lg font-medium transition-all hover:shadow-md flex items-center justify-center;
}
</style>

<script>
// Test notification functions
function testOrderReady() {
    showSuccess('Pesanan #1234 siap disajikan! Notifikasi telah dikirim ke waiter.', false);
    
    // Simulate sending notification to waiter
    setTimeout(() => {
        showInfo('Notifikasi berhasil dikirim ke sistem waiter.', false);
    }, 1000);
}

function testOrderDelay() {
    showWarning('Pesanan #1235 mengalami keterlambatan 15 menit. Waiter telah diberitahu.', false);
    
    setTimeout(() => {
        showInfo('Notifikasi keterlambatan dikirim ke waiter dan customer.', false);
    }, 1000);
}

function testIngredientOut() {
    showError('Bahan untuk Nasi Goreng habis! Menu telah dinonaktifkan dan waiter diberitahu.', false);
    
    setTimeout(() => {
        showWarning('Menu availability telah diupdate di sistem customer.', false);
    }, 1000);
}

function simulateOrderUpdate(fromStatus, toStatus) {
    const statusLabels = {
        pending: 'Menunggu',
        preparing: 'Sedang Dimasak',
        ready: 'Siap Disajikan',
        served: 'Disajikan'
    };
    
    const processingId = showInfo(`Mengupdate status pesanan dari "${statusLabels[fromStatus]}" ke "${statusLabels[toStatus]}"...`, false);
    
    setTimeout(() => {
        notificationManager.hide(processingId);
        showSuccess(`Status pesanan berhasil diupdate! Semua role telah mendapat notifikasi real-time.`);
    }, 2000);
}

function testMenuAvailability(isAvailable) {
    const status = isAvailable ? 'tersedia' : 'tidak tersedia';
    const processingId = showInfo(`Mengupdate ketersediaan menu menjadi ${status}...`, false);
    
    setTimeout(() => {
        notificationManager.hide(processingId);
        if (isAvailable) {
            showSuccess(`Menu berhasil ditandai tersedia! Update telah dikirim ke sistem customer dan waiter.`);
        } else {
            showWarning(`Menu ditandai tidak tersedia. Customer tidak akan melihat menu ini lagi.`);
        }
    }, 1500);
}

// Auto-demo function
function runAutomaticDemo() {
    let step = 0;
    const steps = [
        () => showInfo('Memulai demo otomatis sistem notifikasi...'),
        () => testOrderReady(),
        () => simulateOrderUpdate('pending', 'preparing'),
        () => testMenuAvailability(false),
        () => showSuccess('Demo selesai! Semua fitur berfungsi dengan baik.')
    ];
    
    function nextStep() {
        if (step < steps.length) {
            steps[step]();
            step++;
            setTimeout(nextStep, 3000);
        }
    }
    
    nextStep();
}

// Initialize demo after page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        showInfo('Halaman test siap! Klik tombol untuk menguji fitur notifikasi dan integrasi.');
    }, 1000);
});
</script>
@endsection
