<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.dashboard') }} - {{ __('room.system_name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @livewireStyles
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <img src="{{ asset('sck-logo.png') }}" alt="SCK Logo" class="h-8 w-8 mr-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">SmartSchoolInfra</h1>
                        <p class="text-sm text-gray-600">{{ __('room.system_name') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ auth()->user()->role_badge_class }}">
                        <i class="fas fa-user mr-2"></i>
                        {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            {{ __('auth.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('auth.welcome') }}, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">{{ __('auth.dashboard_description') }}</p>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
            <a href="{{ route('monitoring.index') }}" 
               class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-6 text-center transition-colors">
                <i class="fas fa-desktop text-3xl text-blue-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-blue-900">{{ __('auth.monitoring') }}</h3>
                <p class="text-sm text-blue-700">{{ __('room.monitoring_description') }}</p>
            </a>
            
            <a href="{{ route('pelaporan.index') }}" 
               class="bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg p-6 text-center transition-colors">
                <i class="fas fa-exclamation-triangle text-3xl text-red-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-red-900">{{ __('auth.pelaporan') }}</h3>
                <p class="text-sm text-red-700">{{ __('room.pelaporan_description') }}</p>
            </a>
            
            <a href="{{ route('inventaris.index') }}" 
               class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-6 text-center transition-colors">
                <i class="fas fa-list text-3xl text-green-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-green-900">{{ __('auth.inventaris') }}</h3>
                <p class="text-sm text-green-700">{{ __('room.inventaris_description') }}</p>
            </a>
            
            @if(auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}" 
               class="bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg p-6 text-center transition-colors">
                <i class="fas fa-users text-3xl text-indigo-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-indigo-900">Manajemen User</h3>
                <p class="text-sm text-indigo-700">Kelola user (Guru, Housekeeping, Student)</p>
            </a>
            
            <a href="{{ route('admin.room-schedule.index') }}" 
               class="bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-lg p-6 text-center transition-colors">
                <i class="fas fa-calendar-alt text-3xl text-teal-600 mb-3"></i>
                <h3 class="text-lg font-semibold text-teal-900">Jadwal Ruangan</h3>
                <p class="text-sm text-teal-700">Lihat jadwal booking semua ruangan</p>
            </a>
            @endif
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center">
                        <i class="fas fa-boxes text-xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('room.total_items') }}</h3>
                        <p class="text-2xl font-bold text-blue-600">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-full w-12 h-12 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-xl text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('room.damage_reports') }}</h3>
                        <p class="text-2xl font-bold text-yellow-600">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('room.resolved_reports') }}</h3>
                        <p class="text-2xl font-bold text-green-600">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('room.recent_activities') }}</h2>
            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">{{ __('room.no_recent_activities') }}</p>
                </div>
            </div>
        </div>
    </main>

    @livewireScripts
</body>
</html>
