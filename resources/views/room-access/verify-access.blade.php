<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode Akses - SmartSchoolInfra</title>
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
            <p class="text-sm md:text-base text-gray-600">Verifikasi Kode Akses Guru</p>
        </div>

        <!-- Verification Form -->
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                <!-- Icon -->
                <div class="bg-blue-100 rounded-full w-16 h-16 md:w-20 md:h-20 mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-key text-2xl md:text-3xl text-blue-600"></i>
                </div>

                <h2 class="text-xl md:text-2xl font-bold text-center text-gray-900 mb-2">Masukkan Kode Akses</h2>
                <p class="text-sm md:text-base text-center text-gray-600 mb-6 md:mb-8">Masukkan kode akses Anda untuk melanjutkan sebagai guru</p>

                <form action="{{ route('room.verify-access-code', $roomCode) }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="access_code" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Kode Akses
                        </label>
                        <input 
                            type="text" 
                            id="access_code" 
                            name="access_code" 
                            class="w-full px-4 py-3 md:py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-lg md:text-xl font-mono tracking-wider @error('access_code') border-red-500 @enderror"
                            placeholder="Masukkan kode akses"
                            required
                            autofocus
                            autocomplete="off"
                        >
                        @error('access_code')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 md:py-4 rounded-lg transition-colors duration-300 flex items-center justify-center group"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span>Verifikasi & Masuk</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('room.select-role', $roomCode) }}" class="text-sm md:text-base text-blue-600 hover:text-blue-700 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Pilihan Role
                    </a>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3 flex-shrink-0"></i>
                    <div class="text-xs md:text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Informasi Kode Akses:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Kode akses bersifat unik untuk setiap guru</li>
                            <li>Hubungi admin jika lupa kode akses</li>
                            <li>Jangan membagikan kode akses kepada orang lain</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


