<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('pelaporan.title') }}</h1>
        <p class="text-gray-600">{{ __('pelaporan.description') }}</p>
    </div>

    @if($room)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-blue-900">{{ __('room.room_info') }}</h3>
            <p class="text-blue-700">{{ $room->name }} - {{ $room->code }}</p>
        </div>
    @endif

    <form wire:submit.prevent="submitReport" class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Reporter Information -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('pelaporan.reporter_info') }}</h3>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('pelaporan.reporter_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="reporterName" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reporterName') border-red-500 @enderror"
                       placeholder="{{ __('pelaporan.reporter_name') }}">
                @error('reporterName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('pelaporan.reporter_class') }}
                </label>
                <input type="text" wire:model="reporterClass" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="{{ __('pelaporan.reporter_class') }}">
            </div>

            <!-- Damage Information -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('pelaporan.damage_info') }}</h3>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('pelaporan.select_item') }}
                </label>
                <select wire:model="inventoryItemId" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('pelaporan.select_item') }}</option>
                    @foreach($inventoryItems as $item)
                        <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_type }})</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('pelaporan.damage_description') }} <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="description" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                          placeholder="{{ __('pelaporan.damage_description') }}"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('pelaporan.urgency_level') }} <span class="text-red-500">*</span>
                </label>
                <select wire:model="urgencyLevel" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('urgencyLevel') border-red-500 @enderror">
                    <option value="rendah">{{ __('pelaporan.urgency_low') }} - {{ __('pelaporan.urgency_low_desc') }}</option>
                    <option value="sedang">{{ __('pelaporan.urgency_medium') }} - {{ __('pelaporan.urgency_medium_desc') }}</option>
                    <option value="tinggi">{{ __('pelaporan.urgency_high') }} - {{ __('pelaporan.urgency_high_desc') }}</option>
                </select>
                @error('urgencyLevel') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('pelaporan.damage_photos') }}
                </label>
                <input type="file" wire:model="photos" multiple accept="image/*" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">{{ __('pelaporan.photos_optional') }}</p>
                @error('photos.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Photo Preview -->
            @if($photos)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('pelaporan.upload_photos') }}</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($photos as $index => $photo)
                            <div class="relative">
                                <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-24 object-cover rounded-md">
                                <button type="button" wire:click="removePhoto({{ $index }})" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end space-x-4">
            <button type="button" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                {{ __('common.cancel') }}
            </button>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                {{ __('pelaporan.submit_report') }}
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
