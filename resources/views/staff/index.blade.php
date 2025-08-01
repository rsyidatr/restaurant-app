<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal - Restaurant System</title>
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .staff-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .staff-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4">Restaurant Staff Portal</h1>
            <p class="text-xl text-white opacity-90">Pilih akses sesuai dengan posisi Anda</p>
        </div>

        <!-- Staff Access Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Admin Card -->
            <div class="staff-card rounded-2xl p-8 text-center transition-all duration-300 cursor-pointer"
                 onclick="redirectToStaffLogin('admin')">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Administrator</h3>
                    <p class="text-white opacity-80">Akses penuh sistem restaurant</p>
                </div>
                
                <div class="space-y-2 text-sm text-white opacity-70 mb-6">
                    <div><i class="fas fa-check mr-2"></i>Manajemen User</div>
                    <div><i class="fas fa-check mr-2"></i>Laporan & Analytics</div>
                    <div><i class="fas fa-check mr-2"></i>Pengaturan Sistem</div>
                    <div><i class="fas fa-check mr-2"></i>Kontrol Inventori</div>
                </div>
                
                <button class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition-colors">
                    Akses Admin
                </button>
            </div>

            <!-- Chef Card -->
            <div class="staff-card rounded-2xl p-8 text-center transition-all duration-300 cursor-pointer"
                 onclick="redirectToStaffLogin('chef')">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chef-hat text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Chef / Koki</h3>
                    <p class="text-white opacity-80">Kelola pesanan dan dapur</p>
                </div>
                
                <div class="space-y-2 text-sm text-white opacity-70 mb-6">
                    <div><i class="fas fa-check mr-2"></i>Monitor Pesanan</div>
                    <div><i class="fas fa-check mr-2"></i>Update Status Masakan</div>
                    <div><i class="fas fa-check mr-2"></i>Manajemen Menu</div>
                    <div><i class="fas fa-check mr-2"></i>Prioritas Pesanan</div>
                </div>
                
                <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 rounded-lg font-semibold transition-colors">
                    Akses Dapur
                </button>
            </div>

            <!-- Waiter Card -->
            <div class="staff-card rounded-2xl p-8 text-center transition-all duration-300 cursor-pointer"
                 onclick="redirectToStaffLogin('waiter')">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-concierge-bell text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Waiter / Pelayan</h3>
                    <p class="text-white opacity-80">Layani meja dan customer</p>
                </div>
                
                <div class="space-y-2 text-sm text-white opacity-70 mb-6">
                    <div><i class="fas fa-check mr-2"></i>Kelola Meja</div>
                    <div><i class="fas fa-check mr-2"></i>Ambil Pesanan</div>
                    <div><i class="fas fa-check mr-2"></i>Proses Pembayaran</div>
                    <div><i class="fas fa-check mr-2"></i>Customer Service</div>
                </div>
                
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition-colors">
                    Akses Pelayan
                </button>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="text-center space-y-4">
            <div class="flex justify-center space-x-6">
                <a href="{{ route('multi-session.dashboard') }}" 
                   class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-layer-group mr-2"></i>Multi-Session Dashboard
                </a>
                <a href="{{ url('/') }}" 
                   class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-home mr-2"></i>Customer Portal
                </a>
                <a href="{{ route('login') }}" 
                   class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Customer Login
                </a>
            </div>
            
            <div class="text-sm text-white opacity-60 mt-8">
                <p>Restaurant Management System v2.0</p>
                <p>© 2024 Restaurant Staff Portal. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        function redirectToStaffLogin(role) {
            // Add role parameter to staff login URL
            const url = new URL('{{ route("staff.login") }}', window.location.origin);
            url.searchParams.set('role', role);
            window.location.href = url.toString();
        }

        // Add some interactive effects
        document.querySelectorAll('.staff-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>
