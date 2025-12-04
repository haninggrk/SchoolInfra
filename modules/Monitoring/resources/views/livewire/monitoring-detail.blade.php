<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if($item->item_type_template && $item->item_type_template->icon_url)
                    <img src="{{ $item->item_type_template->icon_url }}" 
                         alt="{{ $item->item_name }}" 
                         class="w-16 h-16 object-contain bg-gray-100 rounded-lg p-2">
                @else
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-gray-400 text-3xl"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $item->item_name }}</h1>
                    <p class="text-gray-600">{{ $item->item_type }} - {{ $item->item_category }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('monitoring.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    {{ __('common.back') }}
                </a>
                <button wire:click="generateQrCode" 
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                    {{ __('monitoring.generate_qr') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Item Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('common.information') }}</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('monitoring.item_name') }}</dt>
                    <dd class="text-sm text-gray-900">{{ $item->item_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('monitoring.item_type') }}</dt>
                    <dd class="text-sm text-gray-900">{{ $item->item_type }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('monitoring.item_category') }}</dt>
                    <dd class="text-sm text-gray-900">{{ $item->item_category }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('monitoring.barcode') }}</dt>
                    <dd class="text-sm text-gray-900">
                        <code class="bg-gray-100 px-2 py-1 rounded">{{ $item->barcode }}</code>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('monitoring.quantity') }}</dt>
                    <dd class="text-sm text-gray-900">{{ $item->qty }} {{ $item->unit }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('monitoring.room_name') }}</dt>
                    <dd class="text-sm text-gray-900">{{ $item->room->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">{{ __('monitoring.date_added') }}</dt>
                    <dd class="text-sm text-gray-900">{{ $item->date_added ? $item->date_added->format('d/m/Y') : '-' }}</dd>
                </div>
                <!-- Maintenance Dates Section -->
                <div class="col-span-full border-t pt-4 mt-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900">Maintenance</h3>
                        @if(!$isEditingDates)
                            <button wire:click="toggleEditDates" 
                                    class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                        @endif
                    </div>

                    @if($isEditingDates)
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian</label>
                                <input type="date" wire:model="purchase_date" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Maintenance Terakhir</label>
                                <input type="date" wire:model="last_maintenance_date" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Maintenance Selanjutnya</label>
                                <input type="date" wire:model="next_maintenance_date" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="flex space-x-2 pt-2">
                                <button wire:click="saveMaintenanceDates" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    Simpan
                                </button>
                                <button wire:click="toggleEditDates" 
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">
                                    Batal
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Pembelian</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Maintenance Terakhir</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    {{ $item->last_maintenance_date ? $item->last_maintenance_date->format('d/m/Y') : '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Maintenance Selanjutnya</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    @if($item->next_maintenance_date)
                                        {{ $item->next_maintenance_date->format('d/m/Y') }}
                                        @if($item->next_maintenance_date->isPast())
                                            <span class="ml-2 text-red-600 text-xs font-semibold">(Terlambat)</span>
                                        @elseif($item->next_maintenance_date->isToday())
                                            <span class="ml-2 text-yellow-600 text-xs font-semibold">(Hari ini)</span>
                                        @elseif($item->next_maintenance_date->diffInDays(now()) <= 7)
                                            <span class="ml-2 text-orange-600 text-xs font-semibold">(Mendekati - {{ $item->next_maintenance_date->diffInDays(now()) }} hari lagi)</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </dd>
                            </div>
                        </div>
                    @endif
                </div>
            </dl>
            
        </div>

        <!-- Status and Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('monitoring.item_status') }}</h2>
            
            <!-- Current Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.current_status') }}</label>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $item->status_badge_class }}">
                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                </span>
            </div>

            <!-- Update Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.update_status') }}</label>
                <div class="flex space-x-2">
                    <button wire:click="updateStatus('baik')" 
                            class="px-3 py-1 bg-green-100 text-green-800 rounded-md hover:bg-green-200 {{ $item->status === 'baik' ? 'ring-2 ring-green-500' : '' }}">
                        {{ __('common.status_good') }}
                    </button>
                    <button wire:click="updateStatus('rusak')" 
                            class="px-3 py-1 bg-red-100 text-red-800 rounded-md hover:bg-red-200 {{ $item->status === 'rusak' ? 'ring-2 ring-red-500' : '' }}">
                        {{ __('common.status_damaged') }}
                    </button>
                    <button wire:click="updateStatus('dalam_perbaikan')" 
                            class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 {{ $item->status === 'dalam_perbaikan' ? 'ring-2 ring-yellow-500' : '' }}">
                        {{ __('common.status_repairing') }}
                    </button>
                </div>
            </div>

            <!-- Notes -->
            @if($item->notes)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.notes') }}</label>
                    <p class="text-sm text-gray-900">{{ $item->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif
</div>
