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
    </section>
@endsection

@push('scripts')
<script>
    // Add scroll animations
    document.addEventListener('DOMContentLoaded', function() {
        const animateElements = document.querySelectorAll('.animate-on-scroll');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        animateElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(50px)';
            el.style.transition = 'all 0.8s ease-out';
            observer.observe(el);
        });
    });
</script>
@endpush
