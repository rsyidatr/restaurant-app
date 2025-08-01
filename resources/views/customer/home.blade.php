@extends('layouts.customer')

@section('title', 'Home - Restoran')

@section('content')
    <!-- Hero Section -->
    <div class="relative h-[600px]">
        <div class="absolute inset-0">
            <img src="{{ url('images/hero/banner.jpg') }}" 
                 alt="Indonesian Food" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>
        <div class="relative container mx-auto px-4 h-full flex items-center">
            <div class="text-white max-w-2xl">
                <h1 class="text-5xl font-light mb-4">Warung Nusantara</h1>
                <p class="text-xl mb-8 text-gray-200">Nikmati kelezatan autentik masakan Indonesia dalam suasana yang nyaman dan hangat</p>
                <div class="space-x-4">
                    <a href="{{ route('customer.menu') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-lg inline-block transition duration-300 transform hover:scale-105">
                        Lihat Menu
                    </a>
                    <a href="{{ route('customer.reservation') }}" class="bg-transparent border-2 border-white hover:bg-white hover:text-orange-600 text-white px-8 py-3 rounded-lg inline-block transition duration-300">
                        Reservasi Meja
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Favorit Section -->
    <div id="menu" class="py-20 bg-amber-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-light text-center mb-12 text-orange-800">Menu Favorit</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Nasi Goreng -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-md">
                    <img src="{{ url('images/menu/nasi-goreng.jpg') }}" 
                         alt="Nasi Goreng" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-orange-600 text-sm">Makanan Utama</span>
                        <h3 class="text-xl font-semibold mb-2">Nasi Goreng</h3>
                        <p class="text-gray-600 mb-4">Rp 35.000</p>
                        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02]">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>

                <!-- Sate Kelapa -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-md">
                    <img src="{{ url('images/menu/sate-kelapa.jpg') }}" 
                         alt="Sate Kelapa" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-orange-600 text-sm">Makanan Utama</span>
                        <h3 class="text-xl font-semibold mb-2">Sate Kelapa</h3>
                        <p class="text-gray-600 mb-4">Rp 30.000</p>
                        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02]">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>

                <!-- Ayam Bakar -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-md">
                    <img src="{{ url('images/menu/ayam-bakar.jpg') }}" 
                         alt="Ayam Bakar" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-orange-600 text-sm">Makanan Utama</span>
                        <h3 class="text-xl font-semibold mb-2">Ayam Bakar</h3>
                        <p class="text-gray-600 mb-4">Rp 45.000</p>
                        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02]">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>

                <!-- Ikan Bakar -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-md">
                    <img src="{{ url('images/menu/ikan-bakar.jpg') }}" 
                         alt="Ikan Bakar" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-orange-600 text-sm">Makanan Utama</span>
                        <h3 class="text-xl font-semibold mb-2">Ikan Bakar</h3>
                        <p class="text-gray-600 mb-4">Rp 40.000</p>
                        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02]">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>

                <!-- Mie Aceh -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-md">
                    <img src="{{ url('images/menu/mie-aceh.jpg') }}" 
                         alt="Mie Aceh" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-orange-600 text-sm">Makanan Utama</span>
                        <h3 class="text-xl font-semibold mb-2">Mie Aceh</h3>
                        <p class="text-gray-600 mb-4">Rp 38.000</p>
                        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02]">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>

                <!-- Udang Saus Padang -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition duration-300 hover:shadow-md">
                    <img src="{{ url('images/menu/udang-saus-padang.jpg') }}" 
                         alt="Udang Saus Padang" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <span class="text-orange-600 text-sm">Makanan Utama</span>
                        <h3 class="text-xl font-semibold mb-2">Udang Saus Padang</h3>
                        <p class="text-gray-600 mb-4">Rp 50.000</p>
                        <button class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02]">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Layanan Section -->
    <div class="py-20">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-light text-center mb-12 text-orange-800">Layanan Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Dine In -->
                <div class="text-center p-6">
                    <div class="bg-orange-100 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Makan di Tempat</h3>
                    <p class="text-gray-600">Nikmati suasana nyaman restoran kami</p>
                </div>
                
                <!-- Take Away -->
                <div class="text-center p-6">
                    <div class="bg-orange-100 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Bawa Pulang</h3>
                    <p class="text-gray-600">Pesan dan ambil langsung di restoran</p>
                </div>

                <!-- Delivery -->
                <div class="text-center p-6">
                    <div class="bg-orange-100 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Pengiriman</h3>
                    <p class="text-gray-600">Antar langsung ke lokasi Anda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lokasi dan Kontak Section -->
    <div id="contact" class="py-20 bg-amber-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Map -->
                <div class="rounded-xl overflow-hidden shadow-lg">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2904260352924!2d106.82687531476882!3d-6.175392095527974!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1627890723673!5m2!1sen!2sid" 
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
                
                <!-- Info -->
                <div class="space-y-8">
                    <div>
                        <h3 class="text-2xl font-light text-orange-800 mb-4">Jam Operasional</h3>
                        <div class="space-y-2 text-gray-600">
                            <p>Senin - Jumat: 10:00 - 22:00</p>
                            <p>Sabtu - Minggu: 08:00 - 23:00</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-light text-orange-800 mb-4">Lokasi</h3>
                        <p class="text-gray-600">Jl. Contoh No. 123<br>Jakarta Pusat, 10110</p>
                    </div>

                    <div>
                        <h3 class="text-2xl font-light text-orange-800 mb-4">Kontak</h3>
                        <div class="space-y-2 text-gray-600">
                            <p>Phone: (021) 123-4567</p>
                            <p>Email: info@warungnusantara.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ulasan Section -->
    <div class="py-20">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-light text-center mb-12 text-orange-800">Ulasan Pelanggan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 3; $i++)
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-orange-600 font-semibold">{{ chr(64 + $i) }}</span>
                        </div>
                        <div>
                            <h4 class="font-semibold">Pelanggan {{ $i }}</h4>
                            <div class="flex text-orange-400">
                                @for ($j = 0; $j < 5; $j++)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"Makanan lezat dengan cita rasa autentik Indonesia. Pelayanan ramah dan suasana nyaman."</p>
                </div>
                @endfor
            </div>
        </div>
    </div>

@endsection
