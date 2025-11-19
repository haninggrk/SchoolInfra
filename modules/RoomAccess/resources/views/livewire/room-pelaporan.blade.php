<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelaporan Kerusakan - SmartSchoolInfra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
        <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-3 md:py-4">
                    <div class="flex items-center space-x-2 md:space-x-3">
                        <a href="{{ route('room.dashboard', $roomCode) }}" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-arrow-left text-lg md:text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-base md:text-xl font-bold text-gray-900">Pelaporan Kerusakan</h1>
                            <p class="text-xs text-gray-500">{{ $room->name }}</p>
                        </div>
                    </div>
                    <button 
                        wire:click="openReportForm"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 md:px-4 py-2 rounded-lg text-sm"
                    >
                        <i class="fas fa-plus mr-2"></i>Lapor
                    </button>
                </div>
            </div>
        </nav>

        <main class="container mx-auto px-4 py-6 md:py-8">
            @if (session()->has('message'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Laporkan Kerusakan</h2>
                <p class="text-gray-600 mb-6">Klik tombol "Lapor" di atas untuk melaporkan kerusakan barang</p>
            </div>
        </main>

        @if($showReportForm)
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" wire:click.self="closeReportForm">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
                        <h3 class="text-xl font-bold">Lapor Kerusakan</h3>
                        <button wire:click="closeReportForm" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="createReport" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Barang yang Rusak</label>
                            <select wire:model="inventory_item_id" class="w-full px-4 py-2 border rounded-lg @error('inventory_item_id') border-red-500 @enderror">
                                <option value="">Pilih Barang (Opsional)</option>
                                @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->item_code }})</option>
                                @endforeach
                            </select>
                            @error('inventory_item_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Urgensi <span class="text-red-500">*</span></label>
                            <select wire:model="urgency_level" class="w-full px-4 py-2 border rounded-lg" required>
                                <option value="rendah">Rendah - Kerusakan ringan, tidak mengganggu aktivitas</option>
                                <option value="sedang">Sedang - Kerusakan sedang, sedikit mengganggu aktivitas</option>
                                <option value="tinggi">Tinggi - Kerusakan parah, sangat mengganggu aktivitas</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                            <textarea wire:model="description" rows="4" class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @enderror" placeholder="Jelaskan kerusakan yang terjadi..." required></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelapor <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="reporter_name" class="w-full px-4 py-2 border rounded-lg @error('reporter_name') border-red-500 @enderror" required>
                            @error('reporter_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kontak Pelapor</label>
                            <input type="text" wire:model="reporter_contact" class="w-full px-4 py-2 border rounded-lg" placeholder="Nomor HP atau email (opsional)">
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button type="button" wire:click="closeReportForm" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
</body>
</html>


