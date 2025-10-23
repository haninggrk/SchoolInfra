<div>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Jadwal Ruangan - SmartSchoolInfra</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-3 md:py-4">
                    <div class="flex items-center space-x-2 md:space-x-3">
                        <a href="{{ route('room.dashboard', $roomCode) }}" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-arrow-left text-lg md:text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-base md:text-xl font-bold text-gray-900">Jadwal Ruangan</h1>
                            <p class="text-xs text-gray-500">{{ $room->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('room.logout', $roomCode) }}" class="text-gray-600 hover:text-gray-900 text-sm md:text-base">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6 md:py-8">
            <!-- Controls -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6">
                <!-- View Mode Selector -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <button 
                        wire:click="setViewMode('day')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $viewMode === 'day' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        <i class="fas fa-calendar-day mr-2"></i>Hari
                    </button>
                    <button 
                        wire:click="setViewMode('week')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $viewMode === 'week' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        <i class="fas fa-calendar-week mr-2"></i>Minggu
                    </button>
                    <button 
                        wire:click="setViewMode('month')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $viewMode === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        <i class="fas fa-calendar-alt mr-2"></i>Bulan
                    </button>
                </div>

                <!-- Date Navigation -->
                <div class="flex items-center justify-between">
                    <button 
                        wire:click="previousPeriod"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors"
                    >
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="text-center flex-1 mx-4">
                        <h2 class="text-lg md:text-xl font-bold text-gray-900">
                            @if($viewMode === 'day')
                                {{ $selectedDate->isoFormat('dddd, D MMMM YYYY') }}
                            @elseif($viewMode === 'week')
                                {{ $selectedDate->copy()->startOfWeek()->isoFormat('D MMM') }} - {{ $selectedDate->copy()->endOfWeek()->isoFormat('D MMM YYYY') }}
                            @else
                                {{ $selectedDate->isoFormat('MMMM YYYY') }}
                            @endif
                        </h2>
                        <button 
                            wire:click="today"
                            class="text-sm text-blue-600 hover:text-blue-700 mt-1"
                        >
                            Hari Ini
                        </button>
                    </div>
                    
                    <button 
                        wire:click="nextPeriod"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors"
                    >
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Schedule Display -->
            @if($bookings->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">Tidak ada jadwal untuk periode ini</p>
                </div>
            @else
                <div class="space-y-4">
                    @if($viewMode === 'day')
                        <!-- Day View - Timeline -->
                        @foreach($bookings as $booking)
                            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 border-l-4 border-purple-500">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $booking->subject }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ $booking->user->name }}</p>
                                        @if($booking->description)
                                            <p class="text-sm text-gray-700">{{ $booking->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Week/Month View - Grouped by date -->
                        @foreach($bookings->groupBy('booking_date') as $date => $dayBookings)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="bg-purple-50 px-4 md:px-6 py-3 border-b border-purple-100">
                                    <h3 class="font-semibold text-purple-900">
                                        {{ Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}
                                    </h3>
                                </div>
                                <div class="divide-y divide-gray-200">
                                    @foreach($dayBookings as $booking)
                                        <div class="p-4 md:p-6 hover:bg-gray-50">
                                            <div class="flex items-start space-x-4">
                                                <div class="bg-purple-100 rounded-lg px-3 py-2 text-center flex-shrink-0">
                                                    <div class="text-xs font-medium text-purple-900">{{ Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</div>
                                                    <div class="text-xs text-purple-700">{{ Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $booking->subject }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $booking->user->name }}</p>
                                                    @if($booking->description)
                                                        <p class="text-sm text-gray-700 mt-1">{{ $booking->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif

            <!-- Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3 flex-shrink-0"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Informasi Jadwal</p>
                        <p>Jadwal yang ditampilkan adalah jadwal pemakaian ruangan yang sudah disetujui. Hubungi guru atau admin untuk booking ruangan.</p>
                    </div>
                </div>
            </div>
        </main>
    </body>
    </html>
</div>


