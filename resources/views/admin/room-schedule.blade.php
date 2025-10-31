<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Ruangan - SmartSchoolInfra</title>
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
                    <a href="{{ route('dashboard') }}" class="mr-4">
                        <i class="fas fa-arrow-left text-gray-600 hover:text-gray-900"></i>
                    </a>
                    <img src="{{ asset('sck-logo.png') }}" alt="SCK Logo" class="h-8 w-8 mr-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">SmartSchoolInfra</h1>
                        <p class="text-sm text-gray-600">Jadwal Ruangan</p>
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
        <!-- Header with Date Navigation -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Jadwal Ruangan</h1>
                <p class="text-gray-600 mt-1">Lihat jadwal booking semua ruangan</p>
            </div>
            
            <!-- Date Navigation -->
            <div class="flex items-center space-x-4 bg-white rounded-lg shadow px-4 py-2">
                <a href="{{ route('admin.room-schedule.index', ['date' => $previousDate]) }}" 
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
                
                <div class="text-center">
                    <div class="text-lg font-semibold text-gray-900">
                        {{ $date->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                    @if($selectedDate !== $todayDate)
                    <a href="{{ route('admin.room-schedule.index') }}" 
                       class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-calendar-day mr-1"></i>Hari ini
                    </a>
                    @endif
                </div>
                
                <a href="{{ route('admin.room-schedule.index', ['date' => $nextDate]) }}" 
                   class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Room Schedules -->
        <div class="space-y-6">
            @forelse($roomSchedules as $schedule)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold">{{ $schedule['room']->name }}</h2>
                                <p class="text-blue-100 text-sm mt-1">
                                    {{ $schedule['room']->code }} 
                                    @if($schedule['room']->building)
                                        • {{ $schedule['room']->building }}
                                    @endif
                                    @if($schedule['room']->floor)
                                        • Lantai {{ $schedule['room']->floor }}
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('admin.room-schedule.show', ['room' => $schedule['room']->id, 'date' => $selectedDate]) }}" 
                               class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium">
                                <i class="fas fa-eye mr-2"></i>Detail
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @if($schedule['bookings']->count() > 0)
                            <div class="space-y-3">
                                @foreach($schedule['bookings'] as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $booking->status_badge_class }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                    <span class="text-sm font-medium text-gray-900">{{ $booking->subject }}</span>
                                                </div>
                                                <div class="text-sm text-gray-600 space-y-1">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-clock w-4 mr-2"></i>
                                                        <span>{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                                                    </div>
                                                    @if($booking->user)
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user w-4 mr-2"></i>
                                                        <span>{{ $booking->user->name }}</span>
                                                    </div>
                                                    @endif
                                                    @if($booking->description)
                                                    <div class="mt-2 text-gray-700">
                                                        {{ $booking->description }}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                <p>Tidak ada booking pada tanggal ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <i class="fas fa-door-open text-5xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 text-lg">Tidak ada ruangan tersedia</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>

