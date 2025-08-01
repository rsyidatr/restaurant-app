@extends('layouts.customer')

@section('title', 'Preview Reservasi - Warung Nusantara')

@section('content')
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-light text-center mb-4">Preview Reservasi</h1>
            <p class="text-center text-orange-100 text-lg">Silakan review informasi reservasi dan pilih meja yang tersedia</p>
        </div>
    </div>

    <!-- Content -->
    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Reservation Summary -->
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-orange-800 mb-6 flex items-center">
                            <i class="fas fa-clipboard-list mr-3"></i>
                            Ringkasan Reservasi
                        </h2>

                        <!-- Basic Info -->
                        <div class="space-y-6">
                            <div class="bg-orange-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-orange-800 mb-3 flex items-center">
                                    <i class="fas fa-user mr-2"></i>
                                    Informasi Dasar
                                </h3>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Nama:</span>
                                        <p class="font-medium">{{ $reservationData['name'] }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Jumlah Tamu:</span>
                                        <p class="font-medium">{{ $reservationData['guest_count'] }} orang</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="bg-orange-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-orange-800 mb-3 flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    Waktu Reservasi
                                </h3>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Tanggal:</span>
                                        <p class="font-medium">{{ \Carbon\Carbon::parse($reservationData['reservation_date'])->locale('id')->translatedFormat('l, d F Y') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Jam:</span>
                                        <p class="font-medium">{{ $reservationData['reservation_time'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="bg-orange-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-orange-800 mb-3 flex items-center">
                                    <i class="fas fa-phone mr-2"></i>
                                    Kontak
                                </h3>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">No. Telepon:</span>
                                        <p class="font-medium">{{ $reservationData['phone'] }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Email:</span>
                                        <p class="font-medium">{{ $reservationData['email'] }}</p>
                                    </div>
                                </div>
                            </div>

                            @if(!empty($reservationData['table_preference']) || !empty($reservationData['occasion']) || !empty($reservationData['allergies']) || !empty($reservationData['accessibility_needs']) || !empty($reservationData['special_requests']))
                            <!-- Additional Info -->
                            <div class="bg-orange-50 p-4 rounded-lg">
                                <h3 class="font-semibold text-orange-800 mb-3 flex items-center">
                                    <i class="fas fa-heart mr-2"></i>
                                    Informasi Tambahan
                                </h3>
                                <div class="space-y-2 text-sm">
                                    @if(!empty($reservationData['table_preference']))
                                    <div>
                                        <span class="text-gray-600">Preferensi Meja:</span>
                                        <span class="font-medium">
                                            @switch($reservationData['table_preference'])
                                                @case('window') Dekat jendela @break
                                                @case('quiet') Area tenang @break
                                                @case('center') Area tengah @break
                                                @case('outdoor') Outdoor/teras @break
                                                @default {{ $reservationData['table_preference'] }}
                                            @endswitch
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($reservationData['occasion']))
                                    <div>
                                        <span class="text-gray-600">Acara:</span>
                                        <span class="font-medium">
                                            @switch($reservationData['occasion'])
                                                @case('birthday') Ulang tahun @break
                                                @case('anniversary') Anniversary @break
                                                @case('business') Meeting bisnis @break
                                                @case('romantic') Romantic dinner @break
                                                @case('family') Gathering keluarga @break
                                                @case('celebration') Perayaan @break
                                                @default {{ $reservationData['occasion'] }}
                                            @endswitch
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($reservationData['allergies']))
                                    <div>
                                        <span class="text-gray-600">Alergi:</span>
                                        <span class="font-medium">{{ $reservationData['allergies'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($reservationData['accessibility_needs']))
                                    <div>
                                        <span class="text-gray-600">Aksesibilitas:</span>
                                        <span class="font-medium">{{ $reservationData['accessibility_needs'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($reservationData['special_requests']))
                                    <div>
                                        <span class="text-gray-600">Permintaan Khusus:</span>
                                        <span class="font-medium">{{ $reservationData['special_requests'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Back Button -->
                        <div class="mt-8">
                            <a href="{{ route('customer.reservation') }}" 
                               class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali & Edit Reservasi
                            </a>
                        </div>
                    </div>

                    <!-- Available Tables -->
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-orange-800 mb-6 flex items-center">
                            <i class="fas fa-chair mr-3"></i>
                            Meja Tersedia
                        </h2>

                        @if($availableTables->count() > 0)
                            <p class="text-gray-600 mb-6">
                                Ditemukan {{ $availableTables->count() }} meja yang cocok untuk {{ $reservationData['guest_count'] }} orang.
                                Silakan pilih salah satu meja di bawah ini:
                            </p>

                            <form action="{{ route('customer.reservation.store') }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <!-- Hidden fields untuk data reservasi -->
                                @foreach($reservationData as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <!-- Table Selection -->
                                <div class="space-y-4">
                                    @foreach($availableTables as $table)
                                    <label class="block">
                                        <input type="radio" name="table_id" value="{{ $table->id }}" required
                                               class="sr-only peer">
                                        <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-orange-300 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition duration-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-chair text-orange-600 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-semibold text-gray-900">Meja {{ $table->table_number }}</h3>
                                                        <p class="text-sm text-gray-600">Kapasitas: {{ $table->capacity }} orang</p>
                                                        @if($table->description)
                                                        <p class="text-sm text-gray-500">{{ $table->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Tersedia
                                                    </span>
                                                    <i class="fas fa-check-circle text-orange-500 text-xl opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-6">
                                    <button type="submit" 
                                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                        <i class="fas fa-calendar-check mr-2"></i>
                                        Konfirmasi Reservasi
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- No Available Tables -->
                            <div class="text-center py-8">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Meja Tersedia</h3>
                                <p class="text-gray-600 mb-6">
                                    Maaf, tidak ada meja yang tersedia untuk {{ $reservationData['guest_count'] }} orang 
                                    pada tanggal {{ \Carbon\Carbon::parse($reservationData['reservation_date'])->locale('id')->translatedFormat('d F Y') }} 
                                    jam {{ $reservationData['reservation_time'] }}.
                                </p>
                                <div class="space-y-3">
                                    <p class="text-sm text-gray-500">Saran:</p>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li>• Coba ubah tanggal atau jam reservasi</li>
                                        <li>• Kurangi jumlah tamu jika memungkinkan</li>
                                        <li>• Hubungi kami untuk alternatif lainnya</li>
                                    </ul>
                                </div>
                                <div class="mt-6">
                                    <a href="{{ route('customer.reservation') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition duration-300">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Ubah Detail Reservasi
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
