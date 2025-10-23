<div>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Booking Ruangan - SmartSchoolInfra</title>
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
                            <h1 class="text-base md:text-xl font-bold text-gray-900">Booking Ruangan</h1>
                            <p class="text-xs text-gray-500">{{ $room->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <button 
                            wire:click="openBookingForm"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            <span class="hidden md:inline">Tambah Booking</span>
                            <span class="md:hidden">Booking</span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6 md:py-8">
            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

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
                    <p class="text-gray-500 mb-4">Tidak ada jadwal untuk periode ini</p>
                    <button 
                        wire:click="openBookingForm"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Booking Baru
                    </button>
                </div>
            @else
                <div class="space-y-4">
                    @if($viewMode === 'day')
                        <!-- Day View - Timeline -->
                        @foreach($bookings as $booking)
                            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 border-l-4 {{ $booking->user_id === $guruId ? 'border-blue-500' : 'border-purple-500' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                            </span>
                                            @if($booking->user_id === $guruId)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-user mr-1"></i>Booking Saya
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $booking->subject }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ $booking->user->name }}</p>
                                        @if($booking->description)
                                            <p class="text-sm text-gray-700">{{ $booking->description }}</p>
                                        @endif
                                    </div>
                                    @if($booking->user_id === $guruId && $booking->status === 'approved')
                                        <button 
                                            wire:click="cancelBooking({{ $booking->id }})"
                                            wire:confirm="Apakah Anda yakin ingin membatalkan booking ini?"
                                            class="ml-4 text-red-600 hover:text-red-700 text-sm"
                                        >
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    @endif
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
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-start space-x-4 flex-1">
                                                    <div class="bg-purple-100 rounded-lg px-3 py-2 text-center flex-shrink-0">
                                                        <div class="text-xs font-medium text-purple-900">{{ Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</div>
                                                        <div class="text-xs text-purple-700">{{ Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                                            <h4 class="font-semibold text-gray-900">{{ $booking->subject }}</h4>
                                                            @if($booking->user_id === $guruId)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                    <i class="fas fa-user mr-1"></i>Saya
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-sm text-gray-600">{{ $booking->user->name }}</p>
                                                        @if($booking->description)
                                                            <p class="text-sm text-gray-700 mt-1">{{ $booking->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($booking->user_id === $guruId && $booking->status === 'approved')
                                                    <button 
                                                        wire:click="cancelBooking({{ $booking->id }})"
                                                        wire:confirm="Apakah Anda yakin ingin membatalkan booking ini?"
                                                        class="ml-4 text-red-600 hover:text-red-700 text-sm flex-shrink-0"
                                                    >
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif
        </main>

        <!-- Booking Form Modal -->
        @if($showBookingForm)
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click.self="closeBookingForm">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Tambah Booking Baru</h3>
                        <button wire:click="closeBookingForm" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="createBooking" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Booking <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                wire:model="booking_date"
                                min="{{ now()->toDateString() }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('booking_date') border-red-500 @enderror"
                                required
                            >
                            @error('booking_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="time" 
                                    wire:model="start_time"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_time') border-red-500 @enderror"
                                    required
                                >
                                @error('start_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Selesai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="time" 
                                    wire:model="end_time"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_time') border-red-500 @enderror"
                                    required
                                >
                                @error('end_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran / Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="subject"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                                placeholder="Contoh: Matematika Kelas 10A"
                                required
                            >
                            @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea 
                                wire:model="description"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                placeholder="Keterangan tambahan tentang kegiatan..."
                            ></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan
                            </label>
                            <textarea 
                                wire:model="notes"
                                rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                placeholder="Catatan tambahan (opsional)..."
                            ></textarea>
                            @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button 
                                type="button" 
                                wire:click="closeBookingForm"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium"
                            >
                                <i class="fas fa-save mr-2"></i>Simpan Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </body>
    </html>
</div>


