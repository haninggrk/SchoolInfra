<!DOCTYPE html>
<html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('room.system_name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    </head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <img src="{{ asset('sck-logo.png') }}" alt="SCK Logo" class="h-10 w-10 mr-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">SmartSchoolInfra</h1>
                        <p class="text-sm text-gray-600">{{ __('room.system_name') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            {{ __('room.go_to_dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            {{ __('auth.login') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                {{ __('room.system_name') }}
            </h1>
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                {{ __('room.system_description') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        {{ __('room.go_to_dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        {{ __('auth.login') }}
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('room.features_title') }}</h2>
                <p class="text-lg text-gray-600">{{ __('room.features_description') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Monitoring Feature -->
                <div class="text-center">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-desktop text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('room.monitoring_title') }}</h3>
                    <p class="text-gray-600">{{ __('room.monitoring_description') }}</p>
                </div>
                
                <!-- Pelaporan Feature -->
                <div class="text-center">
                    <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('room.pelaporan_title') }}</h3>
                    <p class="text-gray-600">{{ __('room.pelaporan_description') }}</p>
                </div>
                
                <!-- Inventaris Feature -->
                <div class="text-center">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-list text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('room.inventaris_title') }}</h3>
                    <p class="text-gray-600">{{ __('room.inventaris_description') }}</p>
                </div>
                
                <!-- QR Code Feature -->
                <div class="text-center">
                    <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-qrcode text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('room.qr_code') }}</h3>
                    <p class="text-gray-600">{{ __('room.qr_code_info') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('room.how_it_works') }}</h2>
                <p class="text-lg text-gray-600">{{ __('room.how_it_works_description') }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('room.step_1_title') }}</h3>
                    <p class="text-gray-600">{{ __('room.step_1_description') }}</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('room.step_2_title') }}</h3>
                    <p class="text-gray-600">{{ __('room.step_2_description') }}</p>
        </div>

                <div class="text-center">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('room.step_3_title') }}</h3>
                    <p class="text-gray-600">{{ __('room.step_3_description') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Browse Rooms Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('room.browse_rooms') }}</h2>
                <p class="text-lg text-gray-600">{{ __('room.browse_rooms_description') }}</p>
            </div>

            @if($rooms->count() > 0)
                <!-- Room Filters -->
                <div class="mb-8 flex flex-wrap gap-4 justify-center">
                    <button onclick="filterRooms('all')" 
                            class="room-filter px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors active"
                            data-filter="all">
                        {{ __('room.all_rooms') }}
                    </button>
                    @foreach($rooms->pluck('building')->unique()->sort() as $building)
                        @if($building)
                            <button onclick="filterRooms('{{ $building }}')" 
                                    class="room-filter px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors"
                                    data-filter="{{ $building }}">
                                {{ __('room.building') }} {{ $building }}
                            </button>
                        @endif
                    @endforeach
                </div>

                <!-- Rooms Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="roomsGrid">
                    @foreach($rooms as $room)
                        <div class="room-card bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-shadow p-6" 
                             data-building="{{ $room->building }}">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $room->code }} </h3>
                                    <p class="text-sm text-gray-500">{{ $room->name }} </p>
                                </div>
                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-qrcode mr-1"></i>
                                    {{ __('common.active') }}
                                </div>
                            </div>

                            @if($room->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $room->description }}</p>
                            @endif

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                @if($room->building)
                                    <div class="flex items-center">
                                        <i class="fas fa-building mr-2 text-gray-400"></i>
                                        <span>{{ __('room.building') }} {{ $room->building }}</span>
                                    </div>
                                @endif
                                @if($room->floor)
                                    <div class="flex items-center">
                                        <i class="fas fa-layer-group mr-2 text-gray-400"></i>
                                        <span>{{ __('room.floor') }} {{ $room->floor }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4 text-xs">
                                <div class="bg-gray-50 rounded p-2 text-center">
                                    <div class="font-semibold text-gray-900">{{ $room->inventoryItems()->count() }}</div>
                                    <div class="text-gray-500">{{ __('room.total_items') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded p-2 text-center">
                                    <div class="font-semibold text-gray-900">{{ $room->damageReports()->where('status', '!=', 'selesai')->count() }}</div>
                                    <div class="text-gray-500">{{ __('room.active_reports') }}</div>
                                </div>
                            </div>

                            <a href="{{ route('room.select-role', $room->code) }}" 
                               class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-arrow-right mr-2"></i>
                                {{ __('room.access_room') }}
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('room.click_room_to_access') }}
                    </p>
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <i class="fas fa-door-open text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">{{ __('room.no_rooms_available') }}</p>
                </div>
            @endif
        </div>
    </section>

    <script>
        function filterRooms(building) {
            const cards = document.querySelectorAll('.room-card');
            const filters = document.querySelectorAll('.room-filter');
            
            // Update filter buttons
            filters.forEach(btn => {
                if (btn.dataset.filter === building) {
                    btn.classList.remove('bg-gray-200', 'text-gray-700');
                    btn.classList.add('bg-blue-600', 'text-white', 'active');
                } else {
                    btn.classList.remove('bg-blue-600', 'text-white', 'active');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                }
            });
            
            // Filter cards
            cards.forEach(card => {
                if (building === 'all' || card.dataset.building === building) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

    <!-- CTA Section -->
    <section class="py-20 bg-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">{{ __('room.get_started') }}</h2>
            <p class="text-xl text-blue-100 mb-8">{{ __('room.get_started_description') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        {{ __('room.go_to_dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        {{ __('auth.login') }}
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <img src="{{ asset('sck-logo.png') }}" alt="SCK Logo" class="h-8 w-8 mr-3">
                    <div>
                        <h3 class="text-xl font-bold">SmartSchoolInfra</h3>
                        <p class="text-sm text-gray-400">{{ __('room.system_name') }}</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-4">{{ __('room.footer_description') }}</p>
                <p class="text-sm text-gray-500">{{ __('room.powered_by') }} Laravel + Livewire</p>
            </div>
        </div>
    </footer>
    </body>
</html>