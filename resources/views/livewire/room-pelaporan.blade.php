<div>
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
                    <a href="{{ route('room.pantau-pelaporan', $roomCode) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-list mr-2"></i>Lihat Laporan
                    </a>
                </div>
            </div>
        </nav>

        <main class="container mx-auto px-4 py-6 md:py-8">
            @if (session()->has('message'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm max-w-3xl mx-auto">
                <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4 rounded-t-lg">
                    <h2 class="text-xl font-bold">Lapor Kerusakan</h2>
                    <p class="text-red-100 text-sm">Lengkapi formulir di bawah ini untuk melaporkan kerusakan</p>
                </div>
                
                <form wire:submit.prevent="createReport" class="p-4 md:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Pelapor -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Pelapor <span class="text-red-500">*</span></label>
                            <input type="text" id="reporterNameInput" wire:model="reporter_name" class="w-full px-3 py-2 border rounded-lg @error('reporter_name') border-red-500 @enderror" placeholder="Ketik nama student..." required autocomplete="off">
                            <div id="studentSuggestions" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"></div>
                            @error('reporter_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kontak Pelapor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kontak Pelapor</label>
                            <input type="text" wire:model="reporter_contact" class="w-full px-3 py-2 border rounded-lg" placeholder="No. HP atau email">
                        </div>

                        <!-- Barang yang Rusak -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Barang yang Rusak</label>
                            <select wire:model="inventory_item_id" class="w-full px-3 py-2 border rounded-lg @error('inventory_item_id') border-red-500 @enderror">
                                <option value="">Pilih Barang (Opsional)</option>
                                @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                @endforeach
                            </select>
                            @error('inventory_item_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tingkat Urgensi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tingkat Urgensi <span class="text-red-500">*</span></label>
                            <select wire:model="urgency_level" class="w-full px-3 py-2 border rounded-lg" required>
                                <option value="rendah">Rendah</option>
                                <option value="sedang">Sedang</option>
                                <option value="tinggi">Tinggi</option>
                            </select>
                        </div>

                        <!-- Deskripsi Kerusakan - Full Width -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                            <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border rounded-lg @error('description') border-red-500 @enderror" placeholder="Jelaskan kerusakan yang terjadi..." required></textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-2 mt-4 pt-4 border-t">
                        <a href="{{ route('room.dashboard', $roomCode) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 text-sm">
                            <i class="fas fa-times mr-1"></i>Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">
                            <i class="fas fa-paper-plane mr-1"></i>Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <script>
            let searchTimeout;
            const nameInput = document.getElementById('reporterNameInput');
            const suggestionsDiv = document.getElementById('studentSuggestions');

            nameInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`/users/search-students?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(students => {
                            if (students.length === 0) {
                                suggestionsDiv.classList.add('hidden');
                                return;
                            }

                            suggestionsDiv.innerHTML = students.map(student => `
                                <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" 
                                     onclick="selectStudent('${student.name.replace(/'/g, "\\'")}', '${student.email || ''}')">
                                    <div class="font-medium text-gray-900">${student.name}</div>
                                    ${student.email ? `<div class="text-xs text-gray-500">${student.email}</div>` : ''}
                                </div>
                            `).join('');
                            suggestionsDiv.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Error searching students:', error);
                        });
                }, 300);
            });

            function selectStudent(name, email) {
                nameInput.value = name;
                @this.set('reporter_name', name);
                suggestionsDiv.classList.add('hidden');
            }

            // Close suggestions when clicking outside
            document.addEventListener('click', function(event) {
                if (!nameInput.contains(event.target) && !suggestionsDiv.contains(event.target)) {
                    suggestionsDiv.classList.add('hidden');
                }
            });
        </script>
    </body>
    </html>
</div>

