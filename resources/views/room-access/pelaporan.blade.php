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
                    onclick="openReportForm()"
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

    <!-- Report Form Modal -->
    <div id="reportFormModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden" onclick="closeReportFormOnBackdrop(event)">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold">Lapor Kerusakan</h3>
                <button onclick="closeReportForm()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('room.store-pelaporan', $roomCode) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barang yang Rusak</label>
                    <select name="inventory_item_id" class="w-full px-4 py-2 border rounded-lg @error('inventory_item_id') border-red-500 @enderror">
                        <option value="">Pilih Barang (Opsional)</option>
                        @foreach($inventoryItems as $item)
                            <option value="{{ $item->id }}" {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->item_name }} ({{ $item->item_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('inventory_item_id') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Urgensi <span class="text-red-500">*</span></label>
                    <select name="urgency_level" class="w-full px-4 py-2 border rounded-lg @error('urgency_level') border-red-500 @enderror" required>
                        <option value="rendah" {{ old('urgency_level', 'sedang') == 'rendah' ? 'selected' : '' }}>
                            Rendah - Kerusakan ringan, tidak mengganggu aktivitas
                        </option>
                        <option value="sedang" {{ old('urgency_level', 'sedang') == 'sedang' ? 'selected' : '' }}>
                            Sedang - Kerusakan sedang, sedikit mengganggu aktivitas
                        </option>
                        <option value="tinggi" {{ old('urgency_level', 'sedang') == 'tinggi' ? 'selected' : '' }}>
                            Tinggi - Kerusakan parah, sangat mengganggu aktivitas
                        </option>
                    </select>
                    @error('urgency_level') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @enderror" placeholder="Jelaskan kerusakan yang terjadi..." required>{{ old('description') }}</textarea>
                    @error('description') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelapor (Siswa) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="studentSearchInput"
                            autocomplete="off"
                            placeholder="Cari siswa (nama, NIS, atau kelas)..."
                            class="w-full px-4 py-2 border rounded-lg @error('student_id') border-red-500 @enderror focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('student_search', '') }}"
                        >
                        <input type="hidden" name="student_id" id="studentIdInput" value="{{ old('student_id') }}" required>
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                    <div id="studentSuggestions" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <!-- Suggestions will be populated here -->
                    </div>
                    @error('student_id') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kontak Pelapor</label>
                    <input 
                        type="text" 
                        name="reporter_contact" 
                        value="{{ old('reporter_contact') }}" 
                        class="w-full px-4 py-2 border rounded-lg" 
                        placeholder="Nomor HP atau email (opsional)"
                    >
                    @error('reporter_contact') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="closeReportForm()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReportForm() {
            document.getElementById('reportFormModal').classList.remove('hidden');
        }

        function closeReportForm() {
            document.getElementById('reportFormModal').classList.add('hidden');
        }

        function closeReportFormOnBackdrop(event) {
            if (event.target === event.currentTarget) {
                closeReportForm();
            }
        }

        // Student search functionality
        document.addEventListener('DOMContentLoaded', function() {
            let searchTimeout;
            const studentSearchInput = document.getElementById('studentSearchInput');
            const studentIdInput = document.getElementById('studentIdInput');
            const studentSuggestions = document.getElementById('studentSuggestions');
            const selectedStudent = {!! json_encode(old('student_id') ? $students->firstWhere('id', old('student_id')) : null) !!};

            // Set initial value if there's a selected student from old input
            if (selectedStudent) {
                studentSearchInput.value = selectedStudent.name + ' (' + selectedStudent.nis + ')';
            }

            if (!studentSearchInput || !studentIdInput || !studentSuggestions) {
                return; // Elements not found, skip initialization
            }

            studentSearchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    studentSuggestions.classList.add('hidden');
                    studentIdInput.value = '';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('room.search-students', $roomCode) }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(students => {
                            if (students.length === 0) {
                                studentSuggestions.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 text-center">Tidak ada siswa ditemukan</div>';
                                studentSuggestions.classList.remove('hidden');
                                return;
                            }

                            studentSuggestions.innerHTML = students.map(student => {
                                const escapedName = student.name.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                                const escapedNis = student.nis.replace(/'/g, "\\'").replace(/"/g, '&quot;');
                                const escapedClass = (student.class || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                                return `
                                    <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors" 
                                         onclick="selectStudent(${student.id}, '${escapedName}', '${escapedNis}', '${escapedClass}')">
                                        <div class="font-medium text-gray-900">${student.name}</div>
                                        <div class="text-xs text-gray-500 mt-1">NIS: ${student.nis}${student.class ? ' | Kelas: ' + student.class : ''}</div>
                                    </div>
                                `;
                            }).join('');
                            studentSuggestions.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Error searching students:', error);
                            studentSuggestions.innerHTML = '<div class="px-4 py-3 text-sm text-red-500 text-center">Terjadi kesalahan saat mencari siswa</div>';
                            studentSuggestions.classList.remove('hidden');
                        });
                }, 300);
            });

            // Close suggestions when clicking outside
            document.addEventListener('click', function(event) {
                const searchContainer = studentSearchInput.closest('.relative');
                if (searchContainer && !searchContainer.contains(event.target)) {
                    studentSuggestions.classList.add('hidden');
                }
            });
        });

        function selectStudent(id, name, nis, className) {
            const studentSearchInput = document.getElementById('studentSearchInput');
            const studentIdInput = document.getElementById('studentIdInput');
            const studentSuggestions = document.getElementById('studentSuggestions');
            
            if (studentIdInput) {
                studentIdInput.value = id;
            }
            if (studentSearchInput) {
                studentSearchInput.value = name + ' (' + nis + ')';
            }
            if (studentSuggestions) {
                studentSuggestions.classList.add('hidden');
            }
        }

        // Show form if there are validation errors
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                openReportForm();
            });
        @endif
    </script>
</body>
</html>

