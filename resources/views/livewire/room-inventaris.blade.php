<div>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daftar Inventaris - SmartSchoolInfra</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-3 md:py-4">
                    <div class="flex items-center space-x-2 md:space-x-3">
                        <a href="{{ route('room.dashboard', $roomCode) }}" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-arrow-left text-lg md:text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-base md:text-xl font-bold text-gray-900">Daftar Inventaris</h1>
                            <p class="text-xs text-gray-500">{{ $room->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('room.logout', $roomCode) }}" class="text-gray-600 hover:text-gray-900 text-sm md:text-base">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6 md:py-8">
            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-2"></i>Pencarian
                        </label>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Cari nama barang, kode, atau merek..."
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-filter mr-2"></i>Kategori
                        </label>
                        <select 
                            wire:model.live="categoryFilter"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Items Grid (Mobile-friendly) -->
            <div class="grid grid-cols-1 gap-4 md:hidden">
                @forelse($items as $item)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-100 rounded-lg w-12 h-12 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-box text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $item->item_name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $item->item_code }}</p>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-gray-500">Kategori:</span>
                                        <span class="font-medium text-gray-900">{{ $item->item_category }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Kondisi:</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $item->condition_badge_class }}">
                                            {{ $item->item_condition }}
                                        </span>
                                    </div>
                                    @if($item->brand)
                                    <div>
                                        <span class="text-gray-500">Merek:</span>
                                        <span class="font-medium text-gray-900">{{ $item->brand }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="text-gray-500">Jumlah:</span>
                                        <span class="font-medium text-gray-900">{{ $item->quantity }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                        <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Tidak ada inventaris yang ditemukan</p>
                    </div>
                @endforelse
            </div>

            <!-- Items Table (Desktop) -->
            <div class="hidden md:block bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->item_code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->item_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->item_category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->brand ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->condition_badge_class }}">
                                            {{ $item->item_condition }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center">
                                        <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-500">Tidak ada inventaris yang ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $items->links() }}
            </div>
        </main>
    </body>
    </html>
</div>

