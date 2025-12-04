<div class="p-3 md:p-6">
    <x-page-header 
        :title="__('monitoring.title')"
        :description="__('monitoring.description')"
        :backUrl="route('dashboard')"
        :breadcrumbs="[
            ['label' => __('auth.dashboard'), 'url' => route('dashboard')],
            ['label' => __('monitoring.title'), 'url' => '']
        ]">
        <x-slot name="actions">
            <a href="{{ route('monitoring.item-types') }}" 
               class="inline-flex items-center px-3 py-2 text-sm md:text-base bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors whitespace-nowrap shadow-sm">
                <i class="fas fa-cog mr-2"></i>Kelola Tipe Barang
            </a>
        </x-slot>
    </x-page-header>

    <!-- Error Messages -->
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

        <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-3 md:p-4 mb-4 md:mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 md:gap-4">
            <!-- Search -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('common.search') }}</label>
                <input type="text" wire:model.live="search" 
                       class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="{{ __('monitoring.item_name') }}">
            </div>

            <!-- Room Code Filter -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('monitoring.filter_by_room_code') }}</label>
                <select wire:model.live="roomCodeFilter" 
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('monitoring.all_room_codes') }}</option>
                    @foreach($roomCodes as $code)
                        <option value="{{ $code }}">{{ $code }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Item Type Filter -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('monitoring.filter_by_item_type') }}</label>
                <select wire:model.live="itemTypeFilter" 
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('monitoring.all_item_types') }}</option>
                    @foreach($itemTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('monitoring.filter_by_category') }}</label>
                <select wire:model.live="categoryFilter" 
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('monitoring.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('monitoring.filter_by_status') }}</label>
                <select wire:model.live="statusFilter" 
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('monitoring.all_status') }}</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-3 md:mt-4 flex flex-col sm:flex-row justify-between gap-3">
            <button wire:click="resetFilters" 
                    class="px-3 md:px-4 py-2 text-sm md:text-base bg-gray-500 text-white rounded-md hover:bg-gray-600">
                {{ __('common.reset') }}
            </button>
            
            <div class="flex items-center space-x-2">
                <label class="text-xs md:text-sm text-gray-700">{{ __('common.per_page') }}:</label>
                <select wire:model.live="perPage" 
                        class="px-2 md:px-3 py-1 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    @if($items->count() > 0)
        <!-- Desktop Table View -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Icon
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('monitoring.item_name') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('monitoring.item_type') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                {{ __('monitoring.room_code') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                {{ __('monitoring.barcode') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                                {{ __('monitoring.quantity') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('monitoring.item_status') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('common.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50 cursor-pointer" wire:click="showDetail({{ $item->id }})">
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        @if($item->item_type_template && $item->item_type_template->icon_url)
                                            <img src="{{ $item->item_type_template->icon_url }}" 
                                                 alt="{{ $item->item_name }}" 
                                                 class="w-10 h-10 md:w-12 md:h-12 object-contain">
                                        @else
                                            <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400 text-lg md:text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4">
                                    <div class="text-xs md:text-sm font-medium text-gray-900 truncate max-w-[150px] md:max-w-[200px]" title="{{ $item->item_name }}">
                                        {{ Str::limit($item->item_name, 25) }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $item->item_category }}</div>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4">
                                    <div class="text-xs md:text-sm text-gray-900 truncate max-w-[150px]" title="{{ $item->item_type }}">
                                        {{ Str::limit($item->item_type, 20) }}
                                    </div>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 hidden lg:table-cell">
                                    <div class="text-xs md:text-sm font-medium text-gray-900">{{ $item->room->code }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[100px]" title="{{ $item->room->name }}">{{ Str::limit($item->room->name, 15) }}</div>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap hidden lg:table-cell">
                                    <code class="bg-gray-100 px-1.5 md:px-2 py-0.5 md:py-1 rounded text-xs">{{ Str::limit($item->barcode, 12) }}</code>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-900 hidden xl:table-cell">
                                    {{ $item->qty }} {{ $item->unit }}
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap">
                                    <span class="inline-flex px-1.5 md:px-2 py-0.5 md:py-1 text-xs font-semibold rounded-full {{ $item->status_badge_class }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm font-medium" onclick="event.stopPropagation()">
                                    <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                                        <a href="{{ route('monitoring.detail', $item->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            {{ __('common.view') }}
                                        </a>
                                        <button wire:click="generateQrCode({{ $item->id }})" 
                                                class="text-green-600 hover:text-green-900 text-left">
                                            QR
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-3 mb-4">
            @foreach($items as $item)
                <div wire:click="showDetail({{ $item->id }})" class="bg-white rounded-lg shadow p-3 cursor-pointer hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center space-x-3 flex-1 min-w-0 pr-2">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                @if($item->item_type_template && $item->item_type_template->icon_url)
                                    <img src="{{ $item->item_type_template->icon_url }}" 
                                         alt="{{ $item->item_name }}" 
                                         class="w-12 h-12 object-contain">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400 text-xl"></i>
                                    </div>
                                @endif
                            </div>
                            <!-- Item Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate mb-1" title="{{ $item->item_name }}">
                                    {{ Str::limit($item->item_name, 30) }}
                                </h3>
                                <p class="text-xs text-gray-600 truncate mb-1" title="{{ $item->item_type }}">
                                    <span class="font-medium">Jenis:</span> {{ Str::limit($item->item_type, 25) }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $item->item_category }}</p>
                            </div>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_badge_class }} whitespace-nowrap">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-xs pt-2 border-t border-gray-100">
                        <div>
                            <span class="text-gray-500">Ruangan:</span>
                            <p class="font-medium text-gray-900">{{ $item->room->code }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Jumlah:</span>
                            <p class="font-medium text-gray-900">{{ $item->qty }} {{ $item->unit }}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-500">Barcode:</span>
                            <code class="text-xs bg-gray-100 px-1 py-0.5 rounded ml-1">{{ Str::limit($item->barcode, 15) }}</code>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-lg shadow px-3 md:px-6 py-3 md:py-4">
            {{ $items->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow text-center py-8 md:py-12">
            <div class="text-gray-500 text-sm md:text-lg">{{ __('monitoring.no_items_found') }}</div>
        </div>
    @endif

    <!-- Modal Detail -->
    @if($showModal && $selectedItem)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-2 px-2 pb-10 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full sm:mx-0">
                    <div class="bg-white px-4 pt-4 pb-3 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-3 sm:mb-4">
                            <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900" id="modal-title">
                                {{ __('monitoring.item_details') }}
                            </h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 ml-2">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.item_name') }}</label>
                                <p class="text-sm text-gray-900 font-medium break-words">{{ $selectedItem->item_name }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.item_type') }}</label>
                                <p class="text-sm text-gray-900 break-words">{{ $selectedItem->item_type }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.item_category') }}</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->item_category }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.barcode') }}</label>
                                <code class="text-xs sm:text-sm bg-gray-100 px-2 py-1 rounded break-all block">{{ $selectedItem->barcode }}</code>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.quantity') }}</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->qty }} {{ $selectedItem->unit }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.room_code') }}</label>
                                <p class="text-sm text-gray-900 font-medium">{{ $selectedItem->room->code }}</p>
                                <p class="text-xs text-gray-500">{{ $selectedItem->room->name }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.date_added') }}</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->date_added ? $selectedItem->date_added->format('d/m/Y') : '-' }}</p>
                            </div>

                            @if($selectedItem->purchase_date)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Pembelian</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->purchase_date->format('d/m/Y') }}</p>
                            </div>
                            @endif

                            @if($selectedItem->last_maintenance_date)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Maintenance Terakhir</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->last_maintenance_date->format('d/m/Y') }}</p>
                            </div>
                            @endif

                            @if($selectedItem->next_maintenance_date)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Maintenance Selanjutnya</label>
                                <p class="text-sm text-gray-900">
                                    {{ $selectedItem->next_maintenance_date->format('d/m/Y') }}
                                    @if($selectedItem->next_maintenance_date->isPast())
                                        <span class="ml-2 text-red-600 text-xs font-semibold">(Terlambat)</span>
                                    @elseif($selectedItem->next_maintenance_date->isToday())
                                        <span class="ml-2 text-yellow-600 text-xs font-semibold">(Hari ini)</span>
                                    @elseif($selectedItem->next_maintenance_date->diffInDays(now()) <= 7)
                                        <span class="ml-2 text-orange-600 text-xs font-semibold">(Mendekati)</span>
                                    @endif
                                </p>
                            </div>
                            @endif

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.item_status') }}</label>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $selectedItem->status_badge_class }}">
                                    {{ ucfirst(str_replace('_', ' ', $selectedItem->status)) }}
                                </span>
                            </div>

                            @if($selectedItem->notes)
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('monitoring.notes') }}</label>
                                    <p class="text-sm text-gray-900 break-words">{{ $selectedItem->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                        <button wire:click="closeModal" 
                                type="button"
                                class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-3 sm:px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('common.close') }}
                        </button>
                        <button wire:click="generateQrCode({{ $selectedItem->id }})" 
                                class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-3 sm:px-4 py-2 bg-green-600 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            {{ __('monitoring.generate_qr') }}
                        </button>
                        <a href="{{ route('monitoring.detail', $selectedItem->id) }}"
                           class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-3 sm:px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('common.view') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- QR Code Modal -->
    @if ($showQrModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="qr-modal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <i class="fas fa-qrcode text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('monitoring.qr_code_modal_title') }}</h3>
                    
                    <!-- QR Code Display -->
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <div class="inline-block">
                            {!! session('qr_code_data') !!}
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-center space-x-3">
                        <button onclick="downloadQrCode()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            <i class="fas fa-download mr-2"></i>
                            {{ __('monitoring.download_qr_code') }}
                        </button>
                        <button wire:click="closeQrModal" 
                                class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600">
                            <i class="fas fa-times mr-2"></i>
                            {{ __('monitoring.close_modal') }}
                        </button>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-3">
                        {{ __('monitoring.qr_code_save_instruction') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <script>
        function downloadQrCode() {
            // Get the SVG element from the modal
            const qrModal = document.getElementById('qr-modal');
            const svgElement = qrModal.querySelector('svg');
            
            if (!svgElement) {
                alert('{{ __('monitoring.qr_code_not_found') }}');
                return;
            }
            
            // Get SVG data
            const svgData = new XMLSerializer().serializeToString(svgElement);
            
            // Create canvas to convert SVG to PNG
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            
            // Set canvas size
            canvas.width = 400;
            canvas.height = 400;
            
            img.onload = function() {
                // Draw image on canvas
                ctx.drawImage(img, 0, 0, 400, 400);
                
                // Convert to PNG and download
                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'qr_code_' + new Date().getTime() + '.png';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                }, 'image/png');
            };
            
            // Convert SVG to data URL
            const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
            const url = URL.createObjectURL(svgBlob);
            img.src = url;
        }
    </script>
</div>
