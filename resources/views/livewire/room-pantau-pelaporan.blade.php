<div>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pantau Pelaporan - SmartSchoolInfra</title>
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
                            <h1 class="text-base md:text-xl font-bold text-gray-900">Pantau Pelaporan</h1>
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
            <!-- Filter -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select wire:model.live="statusFilter" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="baru">Baru</option>
                    <option value="sedang_diproses">Sedang Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white rounded-lg shadow-sm overflow-hidden">
                @if($reports->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pelapor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Barang
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi Problem
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Urgensi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu Lapor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('pelaporan.admin_notes') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $report->reporter_name }}</div>
                                                    @if($report->reporter_contact)
                                                        <div class="text-xs text-gray-500">{{ $report->reporter_contact }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $report->inventoryItem ? $report->inventoryItem->item_name : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs">
                                                {{ Str::limit($report->description, 60) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $report->urgency_badge_class }}">
                                                {{ ucfirst($report->urgency_level) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $report->status_badge_class }}">
                                                @if($report->status === 'baru')
                                                    Pending
                                                @elseif($report->status === 'sedang_diproses')
                                                    Dalam Proses
                                                @elseif($report->status === 'selesai')
                                                    Selesai
                                                @else
                                                    {{ ucfirst($report->status) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->reported_at->format('d/m/Y H:i') }}
                                            <div class="text-xs text-gray-400">{{ $report->reported_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                            @if($report->admin_notes)
                                                <div class="truncate" title="{{ $report->admin_notes }}">
                                                    {{ Str::limit($report->admin_notes, 50) }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-clipboard-list text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Belum ada laporan kerusakan</p>
                    </div>
                @endif
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4">
                @forelse($reports as $report)
                    <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 
                        @if($report->urgency_level === 'tinggi') border-red-500
                        @elseif($report->urgency_level === 'sedang') border-yellow-500
                        @else border-green-500
                        @endif">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $report->reporter_name }}</h3>
                                @if($report->inventoryItem)
                                    <p class="text-sm text-gray-600">{{ $report->inventoryItem->item_name }}</p>
                                @endif
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $report->status_badge_class }}">
                                @if($report->status === 'baru')
                                    Pending
                                @elseif($report->status === 'sedang_diproses')
                                    Proses
                                @elseif($report->status === 'selesai')
                                    Selesai
                                @else
                                    {{ ucfirst($report->status) }}
                                @endif
                            </span>
                        </div>

                        <p class="text-sm text-gray-700 mb-3">{{ $report->description }}</p>

                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex px-2 py-1 rounded-full {{ $report->urgency_badge_class }}">
                                    {{ ucfirst($report->urgency_level) }}
                                </span>
                                @if($report->reporter_contact)
                                    <span class="text-gray-500">
                                        <i class="fas fa-phone mr-1"></i>{{ $report->reporter_contact }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($report->admin_notes)
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">{{ __('pelaporan.admin_notes_label') }}</div>
                                <div class="text-xs text-gray-700">{{ $report->admin_notes }}</div>
                            </div>
                        @endif

                        <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-500">
                            <i class="fas fa-clock mr-1"></i>{{ $report->reported_at->format('d/m/Y H:i') }} ({{ $report->reported_at->diffForHumans() }})
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg p-8 text-center">
                        <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Belum ada laporan kerusakan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($reports->count() > 0)
                <div class="mt-6">
                    {{ $reports->links() }}
                </div>
            @endif
        </main>
    </body>
    </html>
</div>
