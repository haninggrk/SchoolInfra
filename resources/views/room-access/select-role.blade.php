<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Role - SmartSchoolInfra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 md:py-16">
        <!-- Header -->
        <div class="text-center mb-8 md:mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-blue-600 rounded-full mb-4">
                <i class="fas fa-school text-3xl md:text-4xl text-white"></i>
            </div>
            <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2">SmartSchoolInfra</h1>
            <p class="text-sm md:text-lg text-gray-600">Sistem Manajemen Sarana & Prasarana Sekolah</p>
            <div class="mt-4 inline-flex items-center px-4 py-2 bg-white rounded-full shadow-sm">
                <i class="fas fa-door-open text-blue-600 mr-2"></i>
                <span class="text-sm md:text-base font-medium text-gray-700">Ruangan: <span class="text-blue-600">{{ strtoupper($roomCode) }}</span></span>
            </div>
        </div>

        <!-- Room Layout Images Slider -->
        @if($room->image_urls && count($room->image_urls) > 0)
        <div class="max-w-4xl mx-auto mb-8 md:mb-12">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="relative">
                    <!-- Slider Container -->
                    <div class="room-slider" id="roomSlider">
                        @foreach($room->image_urls as $index => $imageUrl)
                            <div class="room-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                                <img src="{{ $imageUrl }}?w=1280&h=720&fit=crop" 
                                     alt="Standard Penataan Ruangan {{ $room->name }} - {{ $index + 1 }}"
                                     class="w-full h-64 md:h-96 object-cover"
                                     loading="lazy">
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Navigation Arrows -->
                    @if(count($room->image_urls) > 1)
                    <button onclick="previousSlide()" 
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-70 text-white rounded-full p-2 md:p-3 transition-all">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="nextSlide()" 
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-70 text-white rounded-full p-2 md:p-3 transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <!-- Dots Indicator -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                        @foreach($room->image_urls as $index => $imageUrl)
                            <button onclick="goToSlide({{ $index }})" 
                                    class="slide-dot {{ $index === 0 ? 'active' : '' }} w-2 h-2 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition-all"
                                    data-dot="{{ $index }}"></button>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Slide Counter -->
                    <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                        <span id="slideCounter">1</span> / {{ count($room->image_urls) }}
                    </div>
                </div>
                
                <!-- Caption -->
                <div class="px-4 md:px-6 py-4 bg-gray-50">
                    <p class="text-sm md:text-base text-gray-700 text-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <strong>Standard Penataan Ruangan:</strong> {{ $room->name }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Role Selection Cards -->
        <div class="max-w-4xl mx-auto">
            <h2 class="text-xl md:text-2xl font-bold text-center text-gray-900 mb-6 md:mb-8">Masuk Sebagai</h2>
            
            <form action="{{ route('room.set-role', $roomCode) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Siswa Card -->
                    <button type="submit" name="role" value="siswa" 
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 md:p-8 text-center border-2 border-transparent hover:border-green-500 transform hover:-translate-y-1">
                        <div class="bg-green-100 group-hover:bg-green-500 transition-colors duration-300 rounded-full w-20 h-20 md:w-24 md:h-24 mx-auto mb-4 md:mb-6 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-3xl md:text-4xl text-green-600 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2 md:mb-3">Siswa</h3>
                        <p class="text-sm md:text-base text-gray-600 mb-4">Akses untuk siswa</p>
                        <div class="space-y-2 text-left">
                            <div class="flex items-start text-xs md:text-sm text-gray-700">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                                <span>Daftar Inventaris</span>
                            </div>
                            <div class="flex items-start text-xs md:text-sm text-gray-700">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                                <span>Pelaporan Kerusakan</span>
                            </div>
                            <div class="flex items-start text-xs md:text-sm text-gray-700">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                                <span>Pantau Pelaporan</span>
                            </div>
                            <div class="flex items-start text-xs md:text-sm text-gray-700">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                                <span>Jadwal Ruangan</span>
                            </div>
                        </div>
                        <div class="mt-6 inline-flex items-center text-green-600 font-semibold group-hover:text-green-700">
                            <span>Masuk</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </button>

                    <!-- Guru Card -->
                    <button type="submit" name="role" value="guru"
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 md:p-8 text-center border-2 border-transparent hover:border-blue-500 transform hover:-translate-y-1">
                        <div class="bg-blue-100 group-hover:bg-blue-500 transition-colors duration-300 rounded-full w-20 h-20 md:w-24 md:h-24 mx-auto mb-4 md:mb-6 flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-3xl md:text-4xl text-blue-600 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2 md:mb-3">Guru</h3>
                        <p class="text-sm md:text-base text-gray-600 mb-4">Akses dengan kode verifikasi</p>
                        <div class="space-y-2 text-left">
                            <div class="flex items-start text-xs md:text-sm text-gray-700">
                                <i class="fas fa-check-circle text-blue-500 mt-1 mr-2 flex-shrink-0"></i>
                                <span>Semua Fitur Siswa</span>
                            </div>
                            <div class="flex items-start text-xs md:text-sm text-gray-700">
                                <i class="fas fa-check-circle text-blue-500 mt-1 mr-2 flex-shrink-0"></i>
                                <span>Monitoring Sarpras</span>
                            </div>
                            <div class="flex items-start text-xs md:text-sm text-gray-700">
                                <i class="fas fa-check-circle text-blue-500 mt-1 mr-2 flex-shrink-0"></i>
                                <span>Booking Ruangan</span>
                            </div>
                            <div class="flex items-start text-xs md:text-sm text-gray-700">
                                <i class="fas fa-check-circle text-blue-500 mt-1 mr-2 flex-shrink-0"></i>
                                <span>Kelola Inventaris</span>
                            </div>
                        </div>
                        <div class="mt-6 inline-flex items-center text-blue-600 font-semibold group-hover:text-blue-700">
                            <span>Masuk</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Footer -->
        <div class="mt-8 md:mt-12 text-center">
            <div class="inline-flex items-center px-4 md:px-6 py-3 bg-white rounded-full shadow-sm">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                <span class="text-xs md:text-sm text-gray-700">Pilih role sesuai dengan status Anda untuk mengakses sistem</span>
            </div>
        </div>
    </div>

    @if($room->image_urls && count($room->image_urls) > 1)
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.room-slide');
        const dots = document.querySelectorAll('.slide-dot');
        const totalSlides = {{ count($room->image_urls) }};

        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Show current slide
            if (slides[index]) {
                slides[index].classList.add('active');
            }
            if (dots[index]) {
                dots[index].classList.add('active');
            }
            
            // Update counter
            document.getElementById('slideCounter').textContent = index + 1;
            currentSlide = index;
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function previousSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        function goToSlide(index) {
            showSlide(index);
        }

        // Auto-play slider (optional, uncomment to enable)
        // setInterval(nextSlide, 5000);

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') previousSlide();
            if (e.key === 'ArrowRight') nextSlide();
        });
    </script>
    
    <style>
        .room-slide {
            display: none;
        }
        .room-slide.active {
            display: block;
        }
        .slide-dot.active {
            background-color: rgba(255, 255, 255, 1) !important;
            width: 8px;
            height: 8px;
        }
    </style>
    @endif
</body>
</html>


