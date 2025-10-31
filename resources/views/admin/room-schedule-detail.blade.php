<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal {{ $room->name }} - SmartSchoolInfra</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('admin.room-schedule.index', ['date' => $selectedDate]) }}" class="mr-4">
                        <i class="fas fa-arrow-left text-gray-600 hover:text-gray-900"></i>
                    </a>
                    <img src="{{ asset('sck-logo.png') }}" alt="SCK Logo" class="h-8 w-8 mr-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">SmartSchoolInfra</h1>
                        <p class="text-sm text-gray-600">Jadwal {{ $room->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ auth()->user()->role_badge_class }}">
                        <i class="fas fa-user mr-2"></i>
                        {{ auth()->user()->name }}
                    </span>
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Room Info -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $room->name }}</h1>
            <div class="text-gray-600">
                <p><strong>Kode:</strong> {{ $room->code }}</p>
                @if($room->building)
                <p><strong>Gedung:</strong> {{ $room->building }}</p>
                @endif
                @if($room->floor)
                <p><strong>Lantai:</strong> {{ $room->floor }}</p>
                @endif
                @if($room->description)
                <p class="mt-2">{{ $room->description }}</p>
                @endif
            </div>
        </div>

        <!-- Header with Date Navigation -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-900">Jadwal Booking</h2>
            
            <!-- Date Navigation -->
            <div class="flex items-center space-x-4 bg-white rounded-lg shadow px-4 py-2">
                <a href="{{ route('admin.room-schedule.show', ['room' => $room->id, 'date' => $previousDate]) }}" 
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
                
                <div class="text-center">
                    <div class="text-lg font-semibold text-gray-900">
                        {{ $date->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                    @if($selectedDate !== $todayDate)
                    <a href="{{ route('admin.room-schedule.show', ['room' => $room->id]) }}" 
                       class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-calendar-day mr-1"></i>Hari ini
                    </a>
                    @endif
                </div>
                
                <a href="{{ route('admin.room-schedule.show', ['room' => $room->id, 'date' => $nextDate]) }}" 
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($bookings->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $booking->status_badge_class }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $booking->subject }}</h3>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock w-5 mr-3 text-gray-400"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">Waktu</div>
                                                <div>{{ $booking->start_time }} - {{ $booking->end_time }}</div>
                                            </div>
                                        </div>
                                        
                                        @if($booking->user)
                                        <div class="flex items-center">
                                            <i class="fas fa-user w-5 mr-3 text-gray-400"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">Guru</div>
                                                <div>{{ $booking->user->name }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if($booking->description)
                                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                        <div class="text-sm font-medium text-gray-700 mb-1">Deskripsi:</div>
                                        <div class="text-sm text-gray-600">{{ $booking->description }}</div>
                                    </div>
                                    @endif
                                    
                                    @if($booking->notes)
                                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="text-sm font-medium text-yellow-800 mb-1">Catatan:</div>
                                        <div class="text-sm text-yellow-700">{{ $booking->notes }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-5xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 text-lg">Tidak ada booking pada tanggal ini</p>
                    <p class="text-gray-500 text-sm mt-2">Ruangan tersedia sepanjang hari</p>
                </div>
            @endif
        </div>
    </main>
</body>
</html>

