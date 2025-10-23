<div>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Monitoring Sarpras - SmartSchoolInfra</title>
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
                            <h1 class="text-base md:text-xl font-bold text-gray-900">Monitoring Sarpras</h1>
                            <p class="text-xs text-gray-500">{{ $room->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('room.logout', $roomCode) }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </nav>

        <main class="container mx-auto px-4 py-6 md:py-8">
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-6">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Cari perangkat elektronik..."
                >
            </div>

            <div class="grid grid-cols-1 gap-4">
                @forelse($items as $item)
                    <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $item->item_name }}</h3>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div><span class="text-gray-600">Kode:</span> {{ $item->item_code }}</div>
                            <div><span class="text-gray-600">Kondisi:</span> <span class="px-2 py-1 rounded {{ $item->condition_badge_class }}">{{ $item->item_condition }}</span></div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg p-8 text-center">
                        <i class="fas fa-desktop text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Tidak ada perangkat yang dimonitor</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $items->links() }}</div>
        </main>
    </body>
    </html>
</div>


