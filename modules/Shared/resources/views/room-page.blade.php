<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room->name }} - {{ __('room.system_name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <img src="{{ asset('sck-logo.png') }}" alt="SCK Logo" class="h-8 w-8 mr-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">SmartSchoolInfra - {{ $room->name }}</h1>
                        <p class="text-sm text-gray-600">{{ $room->code }} - {{ $room->description }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-circle text-xs mr-2"></i>
                        {{ __('room.room_active') }}
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Room Information Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-boxes text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $totalItems }}</h3>
                    <p class="text-sm text-gray-600">{{ __('room.total_items') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-laptop text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $digitalItems }}</h3>
                    <p class="text-sm text-gray-600">{{ __('room.digital_items') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-exclamation-triangle text-2xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $damageReports }}</h3>
                    <p class="text-sm text-gray-600">{{ __('room.damage_reports') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-red-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $activeReports }}</h3>
                    <p class="text-sm text-gray-600">{{ __('room.active_reports') }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button onclick="showTab('overview')" id="tab-overview" 
                            class="tab-button py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        <i class="fas fa-chart-pie mr-2"></i>
                        {{ __('room.tab_overview') }}
                    </button>
                    <button onclick="showTab('monitoring')" id="tab-monitoring" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-desktop mr-2"></i>
                        {{ __('room.tab_monitoring') }}
                    </button>
                    <button onclick="showTab('pelaporan')" id="tab-pelaporan" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ __('room.tab_pelaporan') }}
                    </button>
                    <button onclick="showTab('inventaris')" id="tab-inventaris" 
                            class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <i class="fas fa-list mr-2"></i>
                        {{ __('room.tab_inventaris') }}
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div id="content-overview" class="tab-content">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('room.overview') }}</h2>
                    
                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <a href="#pelaporan" onclick="showTab('pelaporan')" 
                           class="bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-600 mb-2"></i>
                            <h3 class="font-semibold text-red-900">{{ __('room.report_damage') }}</h3>
                            <p class="text-sm text-red-700">{{ __('room.quick_actions') }}</p>
                        </a>
                        <a href="#monitoring" onclick="showTab('monitoring')" 
                           class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-desktop text-2xl text-blue-600 mb-2"></i>
                            <h3 class="font-semibold text-blue-900">{{ __('room.view_inventory') }}</h3>
                            <p class="text-sm text-blue-700">{{ __('room.quick_actions') }}</p>
                        </a>
                        <a href="#inventaris" onclick="showTab('inventaris')" 
                           class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-list text-2xl text-green-600 mb-2"></i>
                            <h3 class="font-semibold text-green-900">{{ __('room.view_reports') }}</h3>
                            <p class="text-sm text-green-700">{{ __('room.quick_actions') }}</p>
                        </a>
                        <button onclick="generateQrCode()" 
                                class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-4 text-center transition-colors">
                            <i class="fas fa-qrcode text-2xl text-purple-600 mb-2"></i>
                            <h3 class="font-semibold text-purple-900">{{ __('room.generate_qr') }}</h3>
                            <p class="text-sm text-purple-700">{{ __('room.quick_actions') }}</p>
                        </button>
                    </div>

                    <!-- Recent Activities -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recent Damage Reports -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('room.recent_damage_reports') }}</h3>
                            @if($recentReports->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentReports as $report)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $report->reporter_name }}</p>
                                                <p class="text-xs text-gray-600">{{ $report->description }}</p>
                                            </div>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $report->status_badge_class }}">
                                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">{{ __('room.no_recent_activities') }}</p>
                            @endif
                        </div>

                        <!-- Recent Inventory Changes -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('room.recent_inventory_changes') }}</h3>
                            @if($recentItems->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentItems as $item)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $item->item_name }}</p>
                                                <p class="text-xs text-gray-600">{{ $item->item_type }}</p>
                                            </div>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_badge_class }}">
                                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">{{ __('room.no_recent_activities') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Monitoring Tab -->
                <div id="content-monitoring" class="tab-content hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('room.tab_monitoring') }}</h2>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                        <i class="fas fa-desktop text-4xl text-blue-600 mb-4"></i>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">{{ __('room.monitoring_title') }}</h3>
                        <p class="text-blue-700">{{ __('room.monitoring_description') }}</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $digitalItems }} {{ __('room.digital_items') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Pelaporan Tab -->
                <div id="content-pelaporan" class="tab-content hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('room.tab_pelaporan') }}</h2>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-600 mb-4"></i>
                        <h3 class="text-lg font-semibold text-red-900 mb-2">{{ __('room.pelaporan_title') }}</h3>
                        <p class="text-red-700">{{ __('room.pelaporan_description') }}</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ $activeReports }} {{ __('room.active_reports') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Inventaris Tab -->
                <div id="content-inventaris" class="tab-content hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('room.tab_inventaris') }}</h2>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                        <i class="fas fa-list text-4xl text-green-600 mb-4"></i>
                        <h3 class="text-lg font-semibold text-green-900 mb-2">{{ __('room.inventaris_title') }}</h3>
                        <p class="text-green-700">{{ __('room.inventaris_description') }}</p>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $totalItems }} {{ __('room.total_items') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-sm text-gray-600">
                <div class="flex items-center justify-center mb-2">
                    <img src="{{ asset('sck-logo.png') }}" alt="SCK Logo" class="h-6 w-6 mr-2">
                    <span class="font-semibold">SmartSchoolInfra</span>
                </div>
                <p>{{ __('room.powered_by') }} <strong>{{ __('room.system_name') }}</strong></p>
                <p class="mt-1">{{ __('room.last_updated') }}: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab button
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-blue-500', 'text-blue-600');
        }

        function generateQrCode() {
            // Generate QR code logic here
            alert('{{ __('room.qr_code_generated') }}');
        }
    </script>
</body>
</html>
