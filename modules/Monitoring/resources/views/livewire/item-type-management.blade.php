<div class="p-3 md:p-6">
    <x-page-header 
        :title="'Manajemen Tipe Barang'"
        :description="'Kelola tipe barang dan icon untuk monitoring'"
        :backUrl="route('monitoring.index')"
        :breadcrumbs="[
            ['label' => __('auth.dashboard'), 'url' => route('dashboard')],
            ['label' => __('monitoring.title'), 'url' => route('monitoring.index')],
            ['label' => 'Manajemen Tipe Barang', 'url' => '']
        ]">
    </x-page-header>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search and Add Button -->
    <div class="bg-white rounded-lg shadow p-3 md:p-4 mb-4 md:mb-6">
        <div class="flex flex-col sm:flex-row justify-between gap-3 md:gap-4">
            <div class="flex-1">
                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Cari Tipe Barang</label>
                <input type="text" wire:model.live="search" 
                       class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nama tipe barang...">
            </div>
            <div class="flex items-end">
                <button wire:click="openModal" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm md:text-base">
                    <i class="fas fa-plus mr-2"></i>Tambah Tipe Barang
                </button>
            </div>
        </div>
    </div>

    <!-- Item Types Grid -->
    @if($itemTypes->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-4">
            @foreach($itemTypes as $itemType)
                <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                    <div class="flex flex-col items-center text-center mb-3">
                        @if($itemType->icon_url)
                            <img src="{{ $itemType->icon_url }}" alt="{{ $itemType->name }}" 
                                 class="w-16 h-16 object-contain mb-2">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mb-2">
                                <i class="fas fa-box text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        <h3 class="text-sm md:text-base font-semibold text-gray-900 mb-1">{{ $itemType->name }}</h3>
                        @if($itemType->description)
                            <p class="text-xs text-gray-600 mb-2">{{ Str::limit($itemType->description, 50) }}</p>
                        @endif
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $itemType->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $itemType->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="flex justify-center space-x-2 pt-3 border-t">
                        <button wire:click="openModal({{ $itemType->id }})" 
                                class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button wire:click="toggleActive({{ $itemType->id }})" 
                                class="px-3 py-1 text-xs {{ $itemType->is_active ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded">
                            <i class="fas fa-{{ $itemType->is_active ? 'eye-slash' : 'eye' }}"></i> {{ $itemType->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        <button wire:click="delete({{ $itemType->id }})" 
                                wire:confirm="Apakah Anda yakin ingin menghapus tipe barang ini?"
                                class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-lg shadow px-3 md:px-6 py-3 md:py-4">
            {{ $itemTypes->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow text-center py-8 md:py-12">
            <div class="text-gray-500 text-sm md:text-lg">Tidak ada tipe barang ditemukan</div>
        </div>
    @endif

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-2 px-2 pb-10 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                                {{ $editingId ? 'Edit Tipe Barang' : 'Tambah Tipe Barang' }}
                            </h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="save">
                            <div class="space-y-4">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tipe Barang <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="name" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea wire:model="description" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>

                                <!-- Icon -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                                    @if($existingIconPath)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $existingIconPath) }}" alt="Current icon" class="w-16 h-16 object-contain border rounded">
                                            <p class="text-xs text-gray-500 mt-1">Icon saat ini</p>
                                        </div>
                                    @endif
                                    <input type="file" wire:model="icon" accept="image/*" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('icon') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB</p>
                                    @error('icon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    @if($icon)
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-600">Preview:</p>
                                            <img src="{{ $icon->temporaryUrl() }}" alt="Preview" class="w-16 h-16 object-contain border rounded mt-1">
                                        </div>
                                    @endif
                                </div>

                                <!-- Active Status -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" wire:click="closeModal" 
                                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    {{ $editingId ? 'Simpan Perubahan' : 'Tambah' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
