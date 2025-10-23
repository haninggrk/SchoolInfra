<div class="p-3 md:p-6">
    <x-page-header 
        :title="__('inventaris.title')"
        :description="__('inventaris.description')"
        :backUrl="route('dashboard')"
        :breadcrumbs="[
            ['label' => __('auth.dashboard'), 'url' => route('dashboard')],
            ['label' => __('inventaris.title'), 'url' => '']
        ]">
        <x-slot name="actions">
            <a href="{{ route('inventaris.create') }}" 
               class="inline-flex items-center px-3 py-2 text-sm md:text-base bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap shadow-sm">
                <i class="fas fa-plus mr-2"></i>
                {{ __('inventaris.add_new_item') }}
            </a>
            <button wire:click="exportCsv" 
                    class="inline-flex items-center px-3 py-2 text-sm md:text-base bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors whitespace-nowrap shadow-sm">
                <i class="fas fa-file-csv mr-2"></i>
                CSV
            </button>
            <button wire:click="exportExcel" 
                    class="inline-flex items-center px-3 py-2 text-sm md:text-base bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors whitespace-nowrap shadow-sm">
                <i class="fas fa-file-excel mr-2"></i>
                Excel
            </button>
        </x-slot>
    </x-page-header>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-3 md:p-4 mb-4 md:mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 md:gap-4">
            <!-- Search -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('common.search') }}</label>
                <input type="text" wire:model.live="search" 
                       class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="{{ __('inventaris.item_name') }}">
            </div>

            <!-- Room Code Filter -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('inventaris.filter_by_room_code') }}</label>
                <select wire:model.live="roomCodeFilter" 
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('inventaris.all_room_codes') }}</option>
                    @foreach($roomCodes as $code)
                        <option value="{{ $code }}">{{ $code }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('inventaris.filter_by_category') }}</label>
                <select wire:model.live="categoryFilter" 
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('inventaris.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('inventaris.filter_by_status') }}</label>
                <select wire:model.live="statusFilter" 
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('inventaris.all_status') }}</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Barcode Status Filter -->
            <div>
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('inventaris.filter_by_barcode_status') }}</label>
                <select wire:model.live="barcodeStatusFilter" 
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('inventaris.all_barcode_status') }}</option>
                    @foreach($barcodeStatuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
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

    @if(session()->has('message'))
        <div class="mb-4 p-3 md:p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    @if(count($selectedItems) > 0)
        <div class="mb-4 p-3 md:p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-md">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <span class="text-sm md:text-base">{{ count($selectedItems) }} {{ __('inventaris.items_selected') }}</span>
                <div class="flex gap-2">
                    <button wire:click="deselectAll" 
                            class="px-3 py-1.5 text-sm bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        {{ __('inventaris.deselect_all') }}
                    </button>
                    <button wire:click="deleteSelected" 
                            onclick="return confirm('{{ __('inventaris.confirm_delete_items') }}')"
                            class="px-3 py-1.5 text-sm bg-red-500 text-white rounded-md hover:bg-red-600">
                        {{ __('common.delete') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($items->count() > 0)
        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-3 mb-4">
            @foreach($items as $item)
                <div wire:click="showDetail({{ $item->id }})" class="bg-white rounded-lg shadow p-3 cursor-pointer hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1 min-w-0 pr-2">
                            <div class="flex items-center gap-2 mb-1">
                                <div onclick="event.stopPropagation()">
                                    <input type="checkbox" wire:click="toggleItem({{ $item->id }})" 
                                           @if(in_array($item->id, $selectedItems)) checked @endif class="rounded">
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900 truncate" title="{{ $item->item_name }}">
                                    {{ Str::limit($item->item_name, 30) }}
                                </h3>
                            </div>
                            <p class="text-xs text-gray-500 mb-1">{{ $item->prefix }}</p>
                            <p class="text-xs text-gray-600 truncate" title="{{ $item->item_type }}">
                                <span class="font-medium">Jenis:</span> {{ Str::limit($item->item_type, 25) }}
                            </p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $item->status_badge_class }} whitespace-nowrap">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-500">Kategori:</span>
                            <p class="font-medium text-gray-900 truncate" title="{{ $item->item_category }}">{{ Str::limit($item->item_category, 15) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Jumlah:</span>
                            <p class="font-medium text-gray-900">{{ $item->qty }} {{ $item->unit }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Ruangan:</span>
                            <p class="font-medium text-gray-900">{{ $item->room->code }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Barcode:</span>
                            <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">{{ Str::limit($item->barcode, 10) }}</code>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" wire:click="selectAll" class="rounded">
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('inventaris.item_name') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('inventaris.item_type') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                {{ __('inventaris.item_category') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                {{ __('inventaris.barcode') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                                {{ __('inventaris.quantity') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                                {{ __('inventaris.room_code') }}
                            </th>
                            <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('inventaris.item_status') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50 cursor-pointer" wire:click="showDetail({{ $item->id }})">
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                    <input type="checkbox" wire:click="toggleItem({{ $item->id }})" 
                                           @if(in_array($item->id, $selectedItems)) checked @endif class="rounded">
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4">
                                    <div class="text-xs md:text-sm font-medium text-gray-900 truncate max-w-[150px] md:max-w-[200px]" title="{{ $item->item_name }}">
                                        {{ Str::limit($item->item_name, 25) }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $item->prefix }}</div>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4">
                                    <div class="text-xs md:text-sm text-gray-900 truncate max-w-[150px]" title="{{ $item->item_type }}">
                                        {{ Str::limit($item->item_type, 20) }}
                                    </div>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 hidden lg:table-cell">
                                    <div class="text-xs md:text-sm text-gray-900 truncate max-w-[120px]" title="{{ $item->item_category }}">
                                        {{ Str::limit($item->item_category, 15) }}
                                    </div>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap hidden lg:table-cell">
                                    <code class="bg-gray-100 px-1.5 md:px-2 py-0.5 md:py-1 rounded text-xs">{{ Str::limit($item->barcode, 12) }}</code>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-900 hidden xl:table-cell">
                                    {{ $item->qty }} {{ $item->unit }}
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap hidden xl:table-cell">
                                    <div class="text-xs md:text-sm font-medium text-gray-900">{{ $item->room->code }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[100px]" title="{{ $item->room->name }}">{{ Str::limit($item->room->name, 15) }}</div>
                                </td>
                                <td class="px-3 md:px-6 py-2 md:py-4 whitespace-nowrap">
                                    <span class="inline-flex px-1.5 md:px-2 py-0.5 md:py-1 text-xs font-semibold rounded-full {{ $item->status_badge_class }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-lg shadow px-3 md:px-6 py-3 md:py-4">
            {{ $items->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow text-center py-8 md:py-12">
            <div class="text-gray-500 text-sm md:text-lg">{{ __('inventaris.no_items_found') }}</div>
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
                                {{ __('inventaris.item_details') }}
                            </h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 ml-2">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.item_name') }}</label>
                                <p class="text-sm text-gray-900 font-medium break-words">{{ $selectedItem->item_name }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.item_type') }}</label>
                                <p class="text-sm text-gray-900 break-words">{{ $selectedItem->item_type }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.item_category') }}</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->item_category }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.barcode') }}</label>
                                <code class="text-xs sm:text-sm bg-gray-100 px-2 py-1 rounded break-all block">{{ $selectedItem->barcode }}</code>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.prefix') }}</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->prefix }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.quantity') }}</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->qty }} {{ $selectedItem->unit }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.room_code') }}</label>
                                <p class="text-sm text-gray-900 font-medium">{{ $selectedItem->room->code }}</p>
                                <p class="text-xs text-gray-500">{{ $selectedItem->room->name }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.date_added') }}</label>
                                <p class="text-sm text-gray-900">{{ $selectedItem->date_added }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.item_status') }}</label>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $selectedItem->status_badge_class }}">
                                    {{ ucfirst(str_replace('_', ' ', $selectedItem->status)) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.barcode_status') }}</label>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $selectedItem->barcode_status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($selectedItem->barcode_status) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.is_monitored') }}</label>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $selectedItem->is_monitored ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $selectedItem->is_monitored ? __('common.yes') : __('common.no') }}
                                </span>
                            </div>

                            @if($selectedItem->notes)
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('inventaris.notes') }}</label>
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
                        <a href="{{ route('inventaris.edit', $selectedItem->id) }}"
                           class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-3 sm:px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('common.edit') }}
                        </a>
                        <button wire:click="deleteItem({{ $selectedItem->id }})" 
                                onclick="return confirm('{{ __('inventaris.confirm_delete_item') }}')"
                                class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-3 sm:px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ __('common.delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
