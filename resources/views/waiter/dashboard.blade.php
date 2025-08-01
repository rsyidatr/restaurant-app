@extends('layouts.staff_dashboard')

@section('title', 'Dashboard Pelayan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Pelayan</h1>
        <p class="text-gray-600">Kelola pesanan dan layani meja dengan efisien</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-table"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Meja Aktif</p>
                    <p class="text-2xl font-bold text-gray-900" id="active-tables">8</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Pesanan Baru</p>
                    <p class="text-2xl font-bold text-gray-900" id="new-orders">5</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Menunggu</p>
                    <p class="text-2xl font-bold text-gray-900" id="pending-orders">3</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Selesai Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900" id="completed-today">12</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Table Status -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Status Meja</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-4 gap-4">
                    @for($i = 1; $i <= 12; $i++)
                        <div class="table-card border-2 border-gray-200 rounded-lg p-4 text-center cursor-pointer hover:shadow-md transition-shadow"
                             data-table="{{ $i }}"
                             onclick="openTableModal({{ $i }})">
                            <div class="text-2xl font-bold text-gray-700">{{ $i }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($i <= 3)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full">Terisi</span>
                                @elseif($i <= 6)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Pesan</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Kosong</span>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h2>
                <button onclick="refreshOrders()" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-4" id="recent-orders">
                    <!-- Sample Orders -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-900">Meja 3</h3>
                                <p class="text-sm text-gray-600">Nasi Gudeg, Es Teh Manis x2</p>
                                <p class="text-xs text-gray-500">5 menit yang lalu</p>
                            </div>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                Proses
                            </span>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-900">Meja 7</h3>
                                <p class="text-sm text-gray-600">Sate Ayam, Nasi Putih, Es Jeruk</p>
                                <p class="text-xs text-gray-500">12 menit yang lalu</p>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                Siap
                            </span>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-900">Meja 1</h3>
                                <p class="text-sm text-gray-600">Gado-gado, Kerupuk, Es Campur</p>
                                <p class="text-xs text-gray-500">18 menit yang lalu</p>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                Diantar
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <button onclick="openNewOrderModal()" 
                class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg text-center transition-colors">
            <i class="fas fa-plus text-2xl mb-2"></i>
            <div class="text-lg font-semibold">Pesanan Baru</div>
            <div class="text-sm opacity-90">Tambah pesanan untuk meja</div>
        </button>

        <button onclick="viewAllOrders()" 
                class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg text-center transition-colors">
            <i class="fas fa-list text-2xl mb-2"></i>
            <div class="text-lg font-semibold">Semua Pesanan</div>
            <div class="text-sm opacity-90">Lihat daftar pesanan lengkap</div>
        </button>

        <button onclick="openPaymentModal()" 
                class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg text-center transition-colors">
            <i class="fas fa-cash-register text-2xl mb-2"></i>
            <div class="text-lg font-semibold">Pembayaran</div>
            <div class="text-sm opacity-90">Proses pembayaran meja</div>
        </button>
    </div>
</div>

<!-- Table Modal -->
<div id="tableModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="tableModalTitle">Meja #</h3>
                <button onclick="closeTableModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="tableModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- New Order Modal -->
<div id="newOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Pesanan Baru</h3>
                <button onclick="closeNewOrderModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="newOrderForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Meja</label>
                    <select id="tableNumber" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">Meja {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Menu Items</label>
                    <div class="border border-gray-300 rounded-md p-3 max-h-40 overflow-y-auto">
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="menu[]" value="nasi_gudeg" class="mr-2">
                                <span>Nasi Gudeg - Rp 15,000</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menu[]" value="sate_ayam" class="mr-2">
                                <span>Sate Ayam - Rp 20,000</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menu[]" value="gado_gado" class="mr-2">
                                <span>Gado-gado - Rp 12,000</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menu[]" value="es_teh" class="mr-2">
                                <span>Es Teh Manis - Rp 5,000</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="menu[]" value="es_jeruk" class="mr-2">
                                <span>Es Jeruk - Rp 6,000</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeNewOrderModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Buat Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    setInterval(function() {
        refreshOrders();
        updateStats();
    }, 30000);

    function openTableModal(tableNumber) {
        document.getElementById('tableModalTitle').textContent = 'Meja ' + tableNumber;
        
        // Simulate table info
        const tableInfo = getTableInfo(tableNumber);
        document.getElementById('tableModalContent').innerHTML = `
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-900">Status:</h4>
                    <span class="px-2 py-1 ${tableInfo.statusClass} rounded-full text-xs">
                        ${tableInfo.status}
                    </span>
                </div>
                ${tableInfo.order ? `
                    <div>
                        <h4 class="font-medium text-gray-900">Pesanan Saat Ini:</h4>
                        <ul class="text-sm text-gray-600 mt-1">
                            ${tableInfo.order.map(item => `<li>• ${item}</li>`).join('')}
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Total: Rp ${tableInfo.total.toLocaleString()}</h4>
                    </div>
                ` : ''}
                <div class="flex space-x-2 mt-4">
                    ${tableInfo.order ? `
                        <button onclick="markAsServed(${tableNumber})" 
                                class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                            Sudah Diantar
                        </button>
                        <button onclick="processPayment(${tableNumber})" 
                                class="px-3 py-1 bg-purple-600 text-white rounded text-sm hover:bg-purple-700">
                            Bayar
                        </button>
                    ` : `
                        <button onclick="closeTableModal(); openNewOrderModal();" 
                                class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                            Buat Pesanan
                        </button>
                    `}
                </div>
            </div>
        `;
        
        document.getElementById('tableModal').classList.remove('hidden');
    }

    function closeTableModal() {
        document.getElementById('tableModal').classList.add('hidden');
    }

    function openNewOrderModal() {
        document.getElementById('newOrderModal').classList.remove('hidden');
    }

    function closeNewOrderModal() {
        document.getElementById('newOrderModal').classList.add('hidden');
    }

    function openPaymentModal() {
        alert('Fitur pembayaran akan segera tersedia');
    }

    function viewAllOrders() {
        alert('Membuka halaman daftar pesanan lengkap');
    }

    function refreshOrders() {
        console.log('Memperbarui daftar pesanan...');
        // Simulate API call
    }

    function updateStats() {
        // Simulate updating statistics
        document.getElementById('active-tables').textContent = Math.floor(Math.random() * 12) + 1;
        document.getElementById('new-orders').textContent = Math.floor(Math.random() * 10);
        document.getElementById('pending-orders').textContent = Math.floor(Math.random() * 5);
        document.getElementById('completed-today').textContent = Math.floor(Math.random() * 20) + 10;
    }

    function getTableInfo(tableNumber) {
        // Simulate table data
        const tables = {
            1: { status: 'Terisi', statusClass: 'bg-red-100 text-red-800', order: ['Gado-gado', 'Es Teh Manis'], total: 17000 },
            2: { status: 'Terisi', statusClass: 'bg-red-100 text-red-800', order: ['Sate Ayam', 'Nasi Putih'], total: 25000 },
            3: { status: 'Terisi', statusClass: 'bg-red-100 text-red-800', order: ['Nasi Gudeg', 'Es Jeruk'], total: 21000 },
            4: { status: 'Pesan', statusClass: 'bg-yellow-100 text-yellow-800', order: ['Nasi Gudeg'], total: 15000 },
            5: { status: 'Pesan', statusClass: 'bg-yellow-100 text-yellow-800', order: ['Sate Ayam', 'Es Teh'], total: 25000 },
            6: { status: 'Pesan', statusClass: 'bg-yellow-100 text-yellow-800', order: ['Gado-gado', 'Es Jeruk'], total: 18000 }
        };
        
        return tables[tableNumber] || { status: 'Kosong', statusClass: 'bg-green-100 text-green-800', order: null, total: 0 };
    }

    function markAsServed(tableNumber) {
        alert(`Pesanan meja ${tableNumber} ditandai sudah diantar`);
        closeTableModal();
        refreshOrders();
    }

    function processPayment(tableNumber) {
        alert(`Memproses pembayaran untuk meja ${tableNumber}`);
        closeTableModal();
    }

    // Handle new order form submission
    document.getElementById('newOrderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const tableNumber = document.getElementById('tableNumber').value;
        const selectedMenus = Array.from(document.querySelectorAll('input[name="menu[]"]:checked'))
                                  .map(checkbox => checkbox.nextElementSibling.textContent);
        
        if (selectedMenus.length === 0) {
            alert('Pilih minimal satu menu');
            return;
        }
        
        alert(`Pesanan baru untuk Meja ${tableNumber}:\n${selectedMenus.join('\n')}`);
        closeNewOrderModal();
        refreshOrders();
    });
</script>
@endpush
