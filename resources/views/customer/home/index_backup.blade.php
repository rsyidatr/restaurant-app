@extends('layouts.customer')

@section('title', 'Home - Restaurant')

@section('content')
    <!-- Hero Section -->
    <div class="relative h-screen">
        <div class="absolute inset-0">
            <img src="{{ url('images/hero/banner.jpg') }}" 
                 alt="Fine Dining Experience" 
                 class="w-full h-full object-cover">
            <div class="gradient-overlay absolute inset-0"></div>
        </div>
        <div class="relative container mx-auto px-4 h-full flex items-center">
            <div class="text-white max-w-3xl animate-slide-up">
                <h1 class="font-playfair text-7xl font-bold mb-6 text-shadow">Restaurant</h1>
                <p class="text-2xl mb-4 text-gold-200 font-light">Premium Dining Experience</p>
                <p class="text-xl mb-12 text-gray-200 leading-relaxed">
                    Indulge in culinary artistry where every dish tells a story of passion, precision, and perfection. 
                    Experience the finest dining in an atmosphere of unparalleled elegance.
                </p>
                <div class="flex flex-col sm:flex-row gap-6">
                    <a href="{{ route('customer.menu') }}" 
                       class="luxury-button text-charcoal-900 font-semibold px-10 py-4 rounded-full text-lg inline-flex items-center justify-center group">
                        <i class="fas fa-utensils mr-3 group-hover:animate-pulse"></i>
                        Explore Our Menu
                    </a>
                    <a href="{{ route('customer.reservation') }}" 
                       class="glass-effect text-white font-semibold px-10 py-4 rounded-full text-lg inline-flex items-center justify-center hover:bg-white hover:bg-opacity-20 transition-all duration-300">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Reserve Table
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-white text-2xl opacity-70"></i>
        </div>
    </div>

    <!-- About Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="animate-on-scroll">
                    <h2 class="font-playfair text-5xl font-bold text-charcoal-900 mb-8">
                        Culinary <span class="text-gold-500">Excellence</span>
                    </h2>
                    <p class="text-lg text-charcoal-600 mb-6 leading-relaxed">
                        At Restaurant, we believe dining is an art form. Our master chefs craft each dish with the finest ingredients, 
                        creating flavors that dance on your palate and memories that last a lifetime.
                    </p>
                    <p class="text-lg text-charcoal-600 mb-8 leading-relaxed">
                        From intimate dinners to grand celebrations, we provide an atmosphere of sophistication 
                        that complements our extraordinary cuisine.
                    </p>
                    <div class="grid grid-cols-2 gap-8">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-gold-600 mb-2">15+</div>
                            <div class="text-charcoal-600">Years of Excellence</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-gold-600 mb-2">50+</div>
                            <div class="text-charcoal-600">Signature Dishes</div>
                        </div>
                    </div>
                </div>
                <div class="animate-on-scroll">
                    <div class="elegant-card rounded-2xl p-8">
                        <img src="{{ url('images/about/chef.jpg') }}" 
                             alt="Our Chef" 
                             class="w-full h-96 object-cover rounded-xl mb-6">
                        <h3 class="font-playfair text-2xl font-semibold text-charcoal-900 mb-2">Chef Marcus</h3>
                        <p class="text-gold-600 mb-4">Executive Chef</p>
                        <p class="text-charcoal-600">
                            "Every dish is a canvas, every flavor a brushstroke in the masterpiece of fine dining."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Menu Section -->
    <section id="menu" class="py-24 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="font-playfair text-5xl font-bold text-charcoal-900 mb-6">
                    Signature <span class="text-gold-500">Dishes</span>
                </h2>
                <p class="text-xl text-charcoal-600 max-w-3xl mx-auto">
                    Discover our chef's carefully curated selection of extraordinary dishes, 
                    each crafted with passion and the finest ingredients.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Dish 1 -->
                <div class="elegant-card rounded-2xl overflow-hidden group cursor-pointer transition-all duration-500 hover:scale-105 animate-on-scroll">
                    <div class="relative overflow-hidden">
                        <img src="{{ url('images/menu/signature-dish-1.jpg') }}" 
                             alt="Premium Wagyu Steak" 
                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="bg-gold-500 text-white px-3 py-1 rounded-full text-sm font-medium">Chef's Choice</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="font-playfair text-2xl font-semibold text-charcoal-900 mb-3">Premium Wagyu Steak</h3>
                        <p class="text-charcoal-600 mb-4 line-clamp-2">
                            Grade A5 Wagyu beef, perfectly grilled and served with truffle sauce and seasonal vegetables.
                        </p>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-2xl font-bold text-gold-600">$89</span>
                            <div class="flex text-gold-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <button class="w-full luxury-button text-charcoal-900 font-semibold py-3 rounded-full transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Dish 2 -->
                <div class="elegant-card rounded-2xl overflow-hidden group cursor-pointer transition-all duration-500 hover:scale-105 animate-on-scroll">
                    <div class="relative overflow-hidden">
                        <img src="{{ url('images/menu/signature-dish-2.jpg') }}" 
                             alt="Lobster Thermidor" 
                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="bg-gold-500 text-white px-3 py-1 rounded-full text-sm font-medium">Premium</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="font-playfair text-2xl font-semibold text-charcoal-900 mb-3">Lobster Thermidor</h3>
                        <p class="text-charcoal-600 mb-4 line-clamp-2">
                            Fresh Atlantic lobster in a rich cognac cream sauce, baked to perfection with gruyere cheese.
                        </p>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-2xl font-bold text-gold-600">$72</span>
                            <div class="flex text-gold-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <button class="w-full luxury-button text-charcoal-900 font-semibold py-3 rounded-full transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Dish 3 -->
                <div class="elegant-card rounded-2xl overflow-hidden group cursor-pointer transition-all duration-500 hover:scale-105 animate-on-scroll">
                    <div class="relative overflow-hidden">
                        <img src="{{ url('images/menu/signature-dish-3.jpg') }}" 
                             alt="Truffle Risotto" 
                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="bg-gold-500 text-white px-3 py-1 rounded-full text-sm font-medium">Seasonal</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="font-playfair text-2xl font-semibold text-charcoal-900 mb-3">Truffle Risotto</h3>
                        <p class="text-charcoal-600 mb-4 line-clamp-2">
                            Creamy Arborio rice with black truffle shavings, parmesan, and a touch of white wine.
                        </p>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-2xl font-bold text-gold-600">$58</span>
                            <div class="flex text-gold-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <button class="w-full luxury-button text-charcoal-900 font-semibold py-3 rounded-full transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12 animate-on-scroll">
                <a href="{{ route('customer.menu') }}" 
                   class="inline-flex items-center bg-charcoal-900 text-white font-semibold px-10 py-4 rounded-full hover:bg-charcoal-800 transition-all duration-300 shadow-lg">
                    <span>View Full Menu</span>
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 bg-charcoal-900 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="font-playfair text-5xl font-bold mb-6">
                    What Our <span class="text-gold-400">Guests Say</span>
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Experience the words of satisfaction from our valued patrons who have enjoyed our culinary journey.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="elegant-card-dark rounded-2xl p-8 animate-on-scroll">
                    <div class="flex text-gold-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-300 mb-6 italic text-lg">
                        "Absolutely extraordinary! Every bite was a symphony of flavors. The service was impeccable and the atmosphere truly magical."
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">S</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white">Sarah Johnson</h4>
                            <p class="text-gray-400 text-sm">Food Critic</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="elegant-card-dark rounded-2xl p-8 animate-on-scroll">
                    <div class="flex text-gold-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-300 mb-6 italic text-lg">
                        "The perfect venue for our anniversary. The chef's special was beyond our expectations and the wine pairing was divine."
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">M</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white">Michael Chen</h4>
                            <p class="text-gray-400 text-sm">Executive</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="elegant-card-dark rounded-2xl p-8 animate-on-scroll">
                    <div class="flex text-gold-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-300 mb-6 italic text-lg">
                        "A culinary masterpiece! The attention to detail in every aspect, from presentation to taste, is simply unmatched."
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold-400 to-gold-600 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">E</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white">Emily Rodriguez</h4>
                            <p class="text-gray-400 text-sm">Chef</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-gold-400 to-gold-600">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto animate-on-scroll">
                <h2 class="font-playfair text-5xl font-bold text-charcoal-900 mb-6">
                    Reserve Your Table Today
                </h2>
                <p class="text-xl text-charcoal-700 mb-12">
                    Join us for an unforgettable dining experience. Book your table now and let us create 
                    magical moments for you and your loved ones.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('customer.reservation') }}" 
                       class="bg-charcoal-900 text-white font-semibold px-10 py-4 rounded-full hover:bg-charcoal-800 transition-all duration-300 shadow-lg inline-flex items-center justify-center">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Make Reservation
                    </a>
                    <a href="{{ route('customer.menu') }}" 
                       class="bg-white text-charcoal-900 font-semibold px-10 py-4 rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg inline-flex items-center justify-center">
                        <i class="fas fa-utensils mr-3"></i>
                        Browse Menu
                    </a>
                </div>
            </div>
        </div>
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
