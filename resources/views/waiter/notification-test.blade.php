@extends('layouts.waiter')

@section('title', 'Test Notifikasi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Test Sistem Notifikasi Pelayan</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <button onclick="showSuccess('Pesanan berhasil dikonfirmasi!')" 
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-lg">
                <i class="fas fa-check mr-2"></i>Notifikasi Sukses
            </button>
            
            <button onclick="showError('Gagal memproses pesanan!')" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg">
                <i class="fas fa-times mr-2"></i>Notifikasi Error
            </button>
            
            <button onclick="showWarning('Yakin ingin melanjutkan?')" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>Notifikasi Warning
            </button>
            
            <button onclick="showInfo('Memproses pesanan...')" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg">
                <i class="fas fa-info mr-2"></i>Notifikasi Info
            </button>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">Simulasi Konfirmasi Pesanan</h3>
            <button onclick="testOrderConfirmation()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                <i class="fas fa-clipboard-check mr-2"></i>Test Konfirmasi Pesanan
            </button>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mt-4">
            <h3 class="text-lg font-semibold mb-4">Simulasi Edit Pesanan</h3>
            <button onclick="testOrderEdit()" 
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Test Edit Pesanan
            </button>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mt-4">
            <h3 class="text-lg font-semibold mb-4">Simulasi Mark as Served</h3>
            <button onclick="testMarkAsServed()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>Test Mark as Served
            </button>
        </div>
    </div>
</div>

<script>
function testOrderConfirmation() {
    // Show confirmation notification first
    const confirmId = showWarning('Yakin ingin mengkonfirmasi pesanan ini? Pesanan akan dikirim ke dapur.', false);
    
    // Create custom confirmation dialog
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    // Add confirm/cancel buttons
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedTestConfirm('${confirmId}')" 
                class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
            Ya, Konfirmasi
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
}

function proceedTestConfirm(confirmId) {
    // Hide confirmation dialog
    notificationManager.hide(confirmId);
    
    // Show processing notification
    const processingId = showInfo('Mengkonfirmasi pesanan...', false);
    
    // Simulate API call
    setTimeout(() => {
        // Hide processing notification
        notificationManager.hide(processingId);
        showSuccess('Pesanan berhasil dikonfirmasi dan dikirim ke dapur!');
    }, 2000);
}

function testOrderEdit() {
    const confirmId = showWarning('Yakin ingin menyimpan perubahan pesanan ini?', false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedTestEdit('${confirmId}')" 
                class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
            Ya, Simpan
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
}

function proceedTestEdit(confirmId) {
    notificationManager.hide(confirmId);
    const processingId = showInfo('Menyimpan perubahan...', false);
    
    setTimeout(() => {
        notificationManager.hide(processingId);
        showSuccess('Perubahan pesanan berhasil disimpan!');
    }, 1500);
}

function testMarkAsServed() {
    const confirmId = showWarning('Yakin ingin menandai pesanan ini sebagai telah disajikan?', false);
    
    const notification = document.getElementById(`notification-${confirmId}`);
    const content = notification.querySelector('.notification-content');
    
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'flex space-x-2 mt-3';
    buttonContainer.innerHTML = `
        <button onclick="proceedTestServed('${confirmId}')" 
                class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
            Ya, Sudah Disajikan
        </button>
        <button onclick="notificationManager.hide('${confirmId}')" 
                class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500">
            Batal
        </button>
    `;
    content.appendChild(buttonContainer);
}

function proceedTestServed(confirmId) {
    notificationManager.hide(confirmId);
    const processingId = showInfo('Menandai pesanan sebagai disajikan...', false);
    
    setTimeout(() => {
        notificationManager.hide(processingId);
        showSuccess('Pesanan berhasil ditandai sebagai disajikan!');
    }, 1500);
}
</script>
@endsection
