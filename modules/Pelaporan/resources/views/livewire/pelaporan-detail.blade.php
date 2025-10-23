<div class="p-3 md:p-6">
    <!-- Breadcrumb -->
    <nav class="mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('pelaporan.index') }}" class="text-gray-500 hover:text-gray-700">
                    {{ __('pelaporan.title') }}
                </a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-gray-900 font-medium">{{ __('pelaporan.report_details') }}</span>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ __('pelaporan.report_details') }}</h1>
                <p class="text-sm text-gray-600">{{ $report->reporter_name }}</p>
            </div>
            <a href="{{ route('pelaporan.index') }}" 
               class="px-3 py-1.5 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600">
                {{ __('common.back') }}
            </a>
        </div>
    </div>

    <!-- Main Content - 2 Column Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Report Information -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-base font-semibold text-gray-900 mb-3">{{ __('pelaporan.report_info') }}</h2>
            <dl class="space-y-2">
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Pelapor:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">{{ $report->reporter_name }}</dd>
                </div>
                @if($report->reporter_class)
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Kelas:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">{{ $report->reporter_class }}</dd>
                </div>
                @endif
                @if($report->reporter_contact)
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Kontak:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">{{ $report->reporter_contact }}</dd>
                </div>
                @endif
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Ruangan:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">{{ $report->room->code }} - {{ $report->room->name }}</dd>
                </div>
                @if($report->inventoryItem)
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Barang:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">{{ $report->inventoryItem->item_name }}</dd>
                </div>
                @endif
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Urgensi:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $report->urgency_badge_class }}">
                            {{ ucfirst($report->urgency_level) }}
                        </span>
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Status:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">
                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $report->status_badge_class }}">
                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                        </span>
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Dilaporkan:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">{{ $report->reported_at->format('d/m/Y H:i') }}</dd>
                </div>
                @if($report->resolved_at)
                <div class="grid grid-cols-3 gap-2">
                    <dt class="text-xs font-medium text-gray-500">Diselesaikan:</dt>
                    <dd class="text-xs text-gray-900 col-span-2">{{ $report->resolved_at->format('d/m/Y H:i') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-base font-semibold text-gray-900 mb-3">{{ __('pelaporan.damage_description') }}</h2>
            <p class="text-xs text-gray-900 whitespace-pre-wrap mb-3">{{ $report->description }}</p>

            @if($report->photos && count($report->photos) > 0)
                <div>
                    <h3 class="text-xs font-medium text-gray-700 mb-2">Foto:</h3>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($report->photos as $photo)
                            <img src="{{ asset('storage/' . $photo) }}" alt="Foto" 
                                 class="w-full h-16 object-cover rounded cursor-pointer hover:opacity-75"
                                 onclick="window.open('{{ asset('storage/' . $photo) }}', '_blank')">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Admin Actions - Full Width -->
    <div class="mt-4 bg-white rounded-lg shadow p-4">
        <h2 class="text-base font-semibold text-gray-900 mb-3">{{ __('pelaporan.admin_actions') }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Status Update -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">{{ __('pelaporan.update_status') }}</label>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="updateStatus('baru')" 
                            class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200 {{ $report->status === 'baru' ? 'ring-2 ring-blue-500' : '' }}">
                        Baru
                    </button>
                    <button wire:click="updateStatus('sedang_diproses')" 
                            class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded hover:bg-yellow-200 {{ $report->status === 'sedang_diproses' ? 'ring-2 ring-yellow-500' : '' }}">
                        Proses
                    </button>
                    <button wire:click="updateStatus('selesai')" 
                            class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200 {{ $report->status === 'selesai' ? 'ring-2 ring-green-500' : '' }}">
                        Selesai
                    </button>
                </div>
            </div>

            <!-- Admin Notes -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">{{ __('pelaporan.admin_notes') }}</label>
                <textarea wire:model="adminNotes" rows="2" 
                          class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="{{ __('pelaporan.admin_notes') }}"></textarea>
                <button wire:click="updateAdminNotes" 
                        class="mt-2 px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">
                    {{ __('pelaporan.add_admin_notes') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mt-3 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-sm">
            {{ session('message') }}
        </div>
    @endif
</div>
