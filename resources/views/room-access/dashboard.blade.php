<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SmartSchoolInfra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3 md:py-4">
                <div class="flex items-center space-x-2 md:space-x-3">
                    <i class="fas fa-school text-xl md:text-2xl text-blue-600"></i>
                    <div>
                        <h1 class="text-base md:text-xl font-bold text-gray-900">SmartSchoolInfra</h1>
                        <p class="text-xs text-gray-500 hidden md:block">{{ strtoupper($roomCode) }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 md:space-x-4">
                    @if($role === 'guru')
                        <span class="hidden md:inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            {{ Session::get('guru_name') }}
                        </span>
                    @else
                        <span class="hidden md:inline-flex items-center px-3 py-1 rounded-full text-xs md:text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-user-graduate mr-2"></i>
                            Siswa
                        </span>
                    @endif
                    <a href="{{ route('room.logout', $roomCode) }}" class="text-gray-600 hover:text-gray-900 text-sm md:text-base">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="hidden md:inline ml-2">Keluar</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 md:py-8">
        <!-- Welcome Section -->
        <div class="mb-6 md:mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                Selamat Datang
                @if($role === 'guru')
                    , {{ Session::get('guru_name') }}!
                @endif
            </h2>
            <p class="text-sm md:text-base text-gray-600">Akses sistem manajemen sarana dan prasarana sekolah</p>
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Daftar Inventaris - Available for both -->
            <a href="{{ route('room.inventaris', $roomCode) }}" 
               class="group bg-white hover:bg-green-50 border-2 border-green-200 hover:border-green-400 rounded-xl p-5 md:p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start space-x-4">
                    <div class="bg-green-100 group-hover:bg-green-500 rounded-lg w-12 h-12 md:w-14 md:h-14 flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                        <i class="fas fa-list text-xl md:text-2xl text-green-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1">Daftar Inventaris</h3>
                        <p class="text-xs md:text-sm text-gray-600">Lihat semua inventaris ruangan</p>
                    </div>
                </div>
            </a>

            <!-- Monitoring Sarpras - Only for Guru -->
            @if($role === 'guru')
            <a href="{{ route('room.monitoring', $roomCode) }}" 
               class="group bg-white hover:bg-blue-50 border-2 border-blue-200 hover:border-blue-400 rounded-xl p-5 md:p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-100 group-hover:bg-blue-500 rounded-lg w-12 h-12 md:w-14 md:h-14 flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                        <i class="fas fa-desktop text-xl md:text-2xl text-blue-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1">Monitoring Sarpras</h3>
                        <p class="text-xs md:text-sm text-gray-600">Monitor kondisi perangkat elektronik</p>
                    </div>
                </div>
            </a>
            @endif

            <!-- Pelaporan Kerusakan - Available for both -->
            <a href="{{ route('room.pelaporan', $roomCode) }}" 
               class="group bg-white hover:bg-red-50 border-2 border-red-200 hover:border-red-400 rounded-xl p-5 md:p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start space-x-4">
                    <div class="bg-red-100 group-hover:bg-red-500 rounded-lg w-12 h-12 md:w-14 md:h-14 flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                        <i class="fas fa-exclamation-triangle text-xl md:text-2xl text-red-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1">Pelaporan Kerusakan</h3>
                        <p class="text-xs md:text-sm text-gray-600">Laporkan kerusakan barang</p>
                    </div>
                </div>
            </a>

            <!-- Pantau Pelaporan - Available for both -->
            <a href="{{ route('room.pantau-pelaporan', $roomCode) }}" 
               class="group bg-white hover:bg-yellow-50 border-2 border-yellow-200 hover:border-yellow-400 rounded-xl p-5 md:p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start space-x-4">
                    <div class="bg-yellow-100 group-hover:bg-yellow-500 rounded-lg w-12 h-12 md:w-14 md:h-14 flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                        <i class="fas fa-eye text-xl md:text-2xl text-yellow-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1">Pantau Pelaporan</h3>
                        <p class="text-xs md:text-sm text-gray-600">Lihat status laporan kerusakan</p>
                    </div>
                </div>
            </a>

            <!-- Jadwal Ruangan / Booking Ruangan - Both Siswa and Guru go to same page -->
            <a href="{{ route('room.booking', $roomCode) }}" 
               class="group bg-white hover:bg-purple-50 border-2 border-purple-200 hover:border-purple-400 rounded-xl p-5 md:p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start space-x-4">
                    <div class="bg-purple-100 group-hover:bg-purple-500 rounded-lg w-12 h-12 md:w-14 md:h-14 flex items-center justify-center flex-shrink-0 transition-colors duration-300">
                        <i class="fas fa-calendar-alt text-xl md:text-2xl text-purple-600 group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1">
                            @if($role === 'siswa')
                                Jadwal Ruangan
                            @else
                                Booking Ruangan
                            @endif
                        </h3>
                        <p class="text-xs md:text-sm text-gray-600">
                            @if($role === 'siswa')
                                Lihat jadwal pemakaian ruangan (read-only)
                            @else
                                Lihat & booking ruangan
                            @endif
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Info Banner -->
        <div class="mt-6 md:mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4 md:p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3 flex-shrink-0"></i>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm md:text-base font-semibold text-blue-900 mb-1">Informasi</h4>
                    <p class="text-xs md:text-sm text-blue-800">
                        @if($role === 'siswa')
                            Anda login sebagai siswa. Akses menu di atas untuk mengelola inventaris dan pelaporan kerusakan.
                        @else
                            Anda login sebagai guru ({{ Session::get('guru_name') }}). Anda memiliki akses penuh untuk monitoring dan booking ruangan.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </main>

    @livewireScripts
</body>
</html>


