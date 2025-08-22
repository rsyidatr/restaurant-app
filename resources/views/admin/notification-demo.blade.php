@extends('layouts.admin_clean')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Demo Sistem Notifikasi Universal</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">Notifikasi Manual</h3>
                <div class="space-y-3">
                    <button onclick="notificationManager.success('Operasi berhasil! Data telah disimpan.')" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg w-full">
                        <i class="fas fa-check-circle mr-2"></i>
                        Show Success
                    </button>
                    
                    <button onclick="notificationManager.error('Terjadi kesalahan! Silakan coba lagi.')" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg w-full">
                        <i class="fas fa-times-circle mr-2"></i>
                        Show Error
                    </button>
                    
                    <button onclick="notificationManager.warning('Peringatan! Data akan dihapus permanen.')" 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg w-full">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Show Warning
                    </button>
                    
                    <button onclick="notificationManager.info('Informasi: Sistem akan maintenance pada malam hari.')" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full">
                        <i class="fas fa-info-circle mr-2"></i>
                        Show Info
                    </button>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold mb-4">Notifikasi Khusus</h3>
                <div class="space-y-3">
                    <button onclick="showCustomNotification()" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg w-full">
                        <i class="fas fa-star mr-2"></i>
                        Notifikasi Tanpa Auto-Hide
                    </button>
                    
                    <button onclick="showMultipleNotifications()" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg w-full">
                        <i class="fas fa-layer-group mr-2"></i>
                        Multiple Notifications
                    </button>
                    
                    <button onclick="notificationManager.hideAll()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg w-full">
                        <i class="fas fa-times mr-2"></i>
                        Hide All Notifications
                    </button>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-3">Fitur Sistem Notifikasi</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li><strong>4 Tipe Notifikasi:</strong> Success (hijau), Error (merah), Warning (kuning), Info (biru)</li>
                <li><strong>Animasi Smooth:</strong> Slide in dari kanan dengan efek cubic-bezier</li>
                <li><strong>Auto-Hide:</strong> Hilang otomatis setelah 5 detik (dapat dimatikan)</li>
                <li><strong>Click to Dismiss:</strong> Klik di mana saja pada notifikasi untuk menutup</li>
                <li><strong>Progress Bar:</strong> Indikator visual countdown auto-hide</li>
                <li><strong>Multiple Notifications:</strong> Dapat menampilkan beberapa notifikasi bersamaan</li>
                <li><strong>Responsive Design:</strong> Menyesuaikan dengan ukuran layar</li>
                <li><strong>Laravel Integration:</strong> Otomatis menampilkan session flash messages</li>
                <li><strong>Global Access:</strong> Dapat dipanggil dari mana saja dengan <code>notificationManager</code></li>
            </ul>
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h4 class="font-semibold text-blue-800 mb-2">Cara Penggunaan</h4>
            <div class="text-sm text-blue-700">
                <p class="mb-2"><strong>JavaScript:</strong></p>
                <code class="bg-blue-100 px-2 py-1 rounded">notificationManager.success('Pesan sukses');</code><br>
                <code class="bg-blue-100 px-2 py-1 rounded">notificationManager.error('Pesan error');</code><br>
                <code class="bg-blue-100 px-2 py-1 rounded">notificationManager.warning('Pesan warning');</code><br>
                <code class="bg-blue-100 px-2 py-1 rounded">notificationManager.info('Pesan info');</code>
                
                <p class="mt-3 mb-2"><strong>Laravel Controller:</strong></p>
                <code class="bg-blue-100 px-2 py-1 rounded">return redirect()->back()->with('success', 'Data berhasil disimpan');</code>
            </div>
        </div>
    </div>
</div>

<script>
function showCustomNotification() {
    // Notifikasi yang tidak hilang otomatis
    notificationManager.success('Notifikasi ini tidak akan hilang otomatis. Klik untuk menutup.', false);
}

function showMultipleNotifications() {
    // Tampilkan beberapa notifikasi bersamaan
    notificationManager.success('Notifikasi pertama berhasil!');
    
    setTimeout(() => {
        notificationManager.warning('Notifikasi kedua adalah peringatan!');
    }, 500);
    
    setTimeout(() => {
        notificationManager.info('Notifikasi ketiga adalah informasi!');
    }, 1000);
    
    setTimeout(() => {
        notificationManager.error('Notifikasi keempat adalah error!');
    }, 1500);
}
</script>
@endsection
