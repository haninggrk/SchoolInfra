<div class="p-3 md:p-6">
    <x-page-header 
        :title="$itemId ? __('inventaris.edit_item') : __('inventaris.add_new_item')"
        :description="$itemId ? __('inventaris.edit_item_description') : __('inventaris.add_item_description')"
        :backUrl="route('inventaris.index')"
        :breadcrumbs="[
            ['label' => __('auth.dashboard'), 'url' => route('dashboard')],
            ['label' => __('inventaris.title'), 'url' => route('inventaris.index')],
            ['label' => $itemId ? __('common.edit') : __('common.add'), 'url' => '']
        ]">
    </x-page-header>

    <form wire:submit.prevent="save" class="bg-white rounded-lg shadow p-4 md:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('inventaris.basic_information') }}</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.select_room') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model="roomId" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('roomId') border-red-500 @enderror">
                    <option value="">{{ __('inventaris.select_room') }}</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->code }} - {{ $room->name }}</option>
                    @endforeach
                </select>
                @error('roomId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.item_category') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model="itemCategory" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('itemCategory') border-red-500 @enderror">
                    <option value="">{{ __('inventaris.select_category') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
                @error('itemCategory') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.item_type') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="itemType" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('itemType') border-red-500 @enderror"
                       placeholder="{{ __('inventaris.item_type_placeholder') }}">
                @error('itemType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.item_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="itemName" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('itemName') border-red-500 @enderror"
                       placeholder="{{ __('inventaris.item_name_placeholder') }}">
                @error('itemName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Barcode and Prefix -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.barcode') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="barcode" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('barcode') border-red-500 @enderror"
                       placeholder="{{ __('inventaris.barcode_placeholder') }}">
                @error('barcode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.prefix') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="prefix" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prefix') border-red-500 @enderror"
                       placeholder="{{ __('inventaris.prefix_placeholder') }}">
                @error('prefix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Quantity and Unit -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.quantity') }} <span class="text-red-500">*</span>
                </label>
                <input type="number" wire:model="qty" min="1" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('qty') border-red-500 @enderror"
                       placeholder="{{ __('inventaris.quantity_placeholder') }}">
                @error('qty') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.unit') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model="unit" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('unit') border-red-500 @enderror">
                    @foreach($units as $unitOption)
                        <option value="{{ $unitOption }}">{{ $unitOption }}</option>
                    @endforeach
                </select>
                @error('unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Date and Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.date_added') }} <span class="text-red-500">*</span>
                </label>
                <input type="date" wire:model="dateAdded" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dateAdded') border-red-500 @enderror">
                @error('dateAdded') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.item_status') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                    @foreach($statuses as $statusOption)
                        <option value="{{ $statusOption }}">{{ ucfirst(str_replace('_', ' ', $statusOption)) }}</option>
                    @endforeach
                </select>
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Barcode Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.barcode_status') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model="barcodeStatus" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('barcodeStatus') border-red-500 @enderror">
                    @foreach($barcodeStatuses as $statusOption)
                        <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                    @endforeach
                </select>
                @error('barcodeStatus') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Notes -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('inventaris.notes') }}
                </label>
                <textarea wire:model="notes" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                          placeholder="{{ __('inventaris.notes_placeholder') }}"></textarea>
                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('inventaris.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                {{ __('common.cancel') }}
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                {{ $itemId ? __('common.update') : __('common.save') }}
            </button>
        </div>
    </form>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif
</div>
