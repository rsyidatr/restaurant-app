@extends('layouts.customer')

@section('title', 'Reservasi - Warung Nusantara')

@section('content')
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-light text-center mb-4">Reservasi Meja</h1>
            <p class="text-center text-orange-100 text-lg">Nikmati pengalaman makan yang tak terlupakan bersama kami</p>
        </div>
    </div>

    <!-- Reservation Form -->
    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium">Terjadi kesalahan:</h4>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <form action="{{ route('customer.reservation.preview') }}" method="POST" class="space-y-6" id="reservationForm">
                            @csrf
                            
                            <!-- Basic Information -->
                            <div class="border-b border-gray-200 pb-6">
                                <h2 class="text-2xl font-semibold text-orange-800 mb-6">Informasi Dasar</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-150 ease-in-out @error('name') border-red-300 @enderror">
                                        @error('name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="guest_count" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jumlah Tamu <span class="text-red-500">*</span>
                                        </label>
                                        <select id="guest_count" name="guest_count" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('guest_count') border-red-300 @enderror">
                                            <option value="">Pilih jumlah tamu</option>
                                            @for($i = 1; $i <= 20; $i++)
                                                <option value="{{ $i }}" {{ old('guest_count') == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i == 1 ? 'orang' : 'orang' }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('guest_count')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Date and Time -->
                            <div class="border-b border-gray-200 pb-6">
                                <h2 class="text-2xl font-semibold text-orange-800 mb-6">Waktu Reservasi</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tanggal Reservasi <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" id="date" name="date" value="{{ old('date') }}" required
                                               min="{{ date('Y-m-d') }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('date') border-red-300 @enderror">
                                        @error('date')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jam Reservasi <span class="text-red-500">*</span>
                                        </label>
                                        <select id="time" name="time" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('time') border-red-300 @enderror">
                                            <option value="">Pilih jam</option>
                                            <option value="10:00" {{ old('time') == '10:00' ? 'selected' : '' }}>10:00</option>
                                            <option value="11:00" {{ old('time') == '11:00' ? 'selected' : '' }}>11:00</option>
                                            <option value="12:00" {{ old('time') == '12:00' ? 'selected' : '' }}>12:00</option>
                                            <option value="13:00" {{ old('time') == '13:00' ? 'selected' : '' }}>13:00</option>
                                            <option value="14:00" {{ old('time') == '14:00' ? 'selected' : '' }}>14:00</option>
                                            <option value="15:00" {{ old('time') == '15:00' ? 'selected' : '' }}>15:00</option>
                                            <option value="16:00" {{ old('time') == '16:00' ? 'selected' : '' }}>16:00</option>
                                            <option value="17:00" {{ old('time') == '17:00' ? 'selected' : '' }}>17:00</option>
                                            <option value="18:00" {{ old('time') == '18:00' ? 'selected' : '' }}>18:00</option>
                                            <option value="19:00" {{ old('time') == '19:00' ? 'selected' : '' }}>19:00</option>
                                            <option value="20:00" {{ old('time') == '20:00' ? 'selected' : '' }}>20:00</option>
                                            <option value="21:00" {{ old('time') == '21:00' ? 'selected' : '' }}>21:00</option>
                                        </select>
                                        @error('time')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Table Preference -->
                                <div class="mt-6">
                                    <label for="table_preference" class="block text-sm font-medium text-gray-700 mb-2">
                                        Preferensi Meja
                                    </label>
                                    <select id="table_preference" name="table_preference"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('table_preference') border-red-300 @enderror">
                                        <option value="">Pilih preferensi meja (opsional)</option>
                                        <option value="window" {{ old('table_preference') == 'window' ? 'selected' : '' }}>Dekat jendela</option>
                                        <option value="quiet" {{ old('table_preference') == 'quiet' ? 'selected' : '' }}>Area tenang</option>
                                        <option value="center" {{ old('table_preference') == 'center' ? 'selected' : '' }}>Area tengah</option>
                                        <option value="outdoor" {{ old('table_preference') == 'outdoor' ? 'selected' : '' }}>Outdoor/teras</option>
                                    </select>
                                    @error('table_preference')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="border-b border-gray-200 pb-6">
                                <h2 class="text-2xl font-semibold text-orange-800 mb-6">Informasi Kontak</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nomor Telepon <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                               placeholder="08xxxxxxxxx"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('phone') border-red-300 @enderror">
                                        @error('phone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                               placeholder="email@example.com"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-300 @enderror">
                                        @error('email')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Special Requests -->
                            <div>
                                <h2 class="text-2xl font-semibold text-orange-800 mb-6">Permintaan Khusus</h2>
                                
                                <!-- Smoking Preference -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Area Ruangan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex space-x-6">
                                        <label class="flex items-center">
                                            <input type="radio" name="smoking_preference" value="non-smoking" required
                                                   {{ old('smoking_preference') == 'non-smoking' ? 'checked' : '' }}
                                                   class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Non Smoking</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="smoking_preference" value="smoking" required
                                                   {{ old('smoking_preference') == 'smoking' ? 'checked' : '' }}
                                                   class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Smoking</span>
                                        </label>
                                    </div>
                                    @error('smoking_preference')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Food Allergies -->
                                <div class="mb-6">
                                    <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">
                                        Alergi Makanan
                                    </label>
                                    <textarea id="allergies" name="allergies" rows="3"
                                              placeholder="Sebutkan jika ada alergi makanan tertentu..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('allergies') border-red-300 @enderror">{{ old('allergies') }}</textarea>
                                    @error('allergies')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Special Celebration -->
                                <div class="mb-6">
                                    <label for="occasion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Perayaan Khusus
                                    </label>
                                    <select id="occasion" name="occasion"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('occasion') border-red-300 @enderror">
                                        <option value="">Pilih jenis acara (opsional)</option>
                                        <option value="birthday" {{ old('occasion') == 'birthday' ? 'selected' : '' }}>Ulang tahun</option>
                                        <option value="anniversary" {{ old('occasion') == 'anniversary' ? 'selected' : '' }}>Anniversary</option>
                                        <option value="business" {{ old('occasion') == 'business' ? 'selected' : '' }}>Meeting bisnis</option>
                                        <option value="romantic" {{ old('occasion') == 'romantic' ? 'selected' : '' }}>Romantic dinner</option>
                                        <option value="family" {{ old('occasion') == 'family' ? 'selected' : '' }}>Gathering keluarga</option>
                                        <option value="celebration" {{ old('occasion') == 'celebration' ? 'selected' : '' }}>Perayaan</option>
                                    </select>
                                    @error('occasion')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Accessibility Needs -->
                                <div class="mb-6">
                                    <label for="accessibility_needs" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kebutuhan Aksesibilitas
                                    </label>
                                    <textarea id="accessibility_needs" name="accessibility_needs" rows="3"
                                              placeholder="Kursi roda, high chair, dll..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('accessibility_needs') border-red-300 @enderror">{{ old('accessibility_needs') }}</textarea>
                                    @error('accessibility_needs')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Additional Special Requests -->
                                <div class="mb-6">
                                    <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">
                                        Permintaan Tambahan
                                    </label>
                                    <textarea id="special_requests" name="special_requests" rows="4"
                                              placeholder="Permintaan khusus lainnya..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('special_requests') border-red-300 @enderror">{{ old('special_requests') }}</textarea>
                                    @error('special_requests')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-6">
                                <button type="submit" 
                                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                    <i class="fas fa-eye mr-2"></i>
                                    Preview Reservasi & Pilih Meja
                                </button>
                            </div>

                            <!-- Terms -->
                            <div class="pt-4 text-center">
                                <p class="text-sm text-gray-600">
                                    Dengan mengirim formulir ini, Anda menyetujui bahwa data akan diproses untuk keperluan reservasi.
                                    Tim kami akan menghubungi Anda dalam 24 jam untuk konfirmasi.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reservation Info -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock text-2xl text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Jam Operasional</h3>
                        <p class="text-gray-600 text-sm">Senin - Jumat: 10:00 - 22:00</p>
                        <p class="text-gray-600 text-sm">Sabtu - Minggu: 08:00 - 23:00</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-phone text-2xl text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Kontak Langsung</h3>
                        <p class="text-gray-600 text-sm">(021) 123-4567</p>
                        <p class="text-gray-600 text-sm">info@warungnusantara.com</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md text-center">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-info-circle text-2xl text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">Kebijakan</h3>
                        <p class="text-gray-600 text-sm">Maks. 20 orang per reservasi</p>
                        <p class="text-gray-600 text-sm">Konfirmasi dalam 24 jam</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
