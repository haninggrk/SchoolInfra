<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $item->item_name }}</h1>
                <p class="text-gray-600">{{ $item->item_type }} - {{ $item->item_category }}</p>
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
                    <dd class="text-sm text-gray-900">{{ $item->date_added->format('d/m/Y') }}</dd>
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

            <!-- Barcode Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('monitoring.barcode_status') }}</label>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $item->barcode_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $item->barcode_status === 'active' ? __('common::common.status_active') : __('common::common.status_inactive') }}
                </span>
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
