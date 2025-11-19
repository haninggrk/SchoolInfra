<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Siswa - SmartSchoolInfra</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('admin.students.index') }}" class="mr-4">
                        <i class="fas fa-arrow-left text-gray-600 hover:text-gray-900"></i>
                    </a>
                    <img src="{{ asset('sck-logo.png') }}" alt="SCK Logo" class="h-8 w-8 mr-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">SmartSchoolInfra</h1>
                        <p class="text-sm text-gray-600">Import Siswa dari CSV</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Import Siswa dari CSV</h2>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Format CSV yang Diperlukan
                </h3>
                <p class="text-sm text-blue-800 mb-2">File CSV harus memiliki header dengan kolom berikut (dalam urutan ini):</p>
                <ul class="list-disc list-inside text-sm text-blue-800 space-y-1">
                    <li><strong>NIS</strong> - Nomor Induk Siswa (unik, wajib)</li>
                    <li><strong>Unit</strong> - Senior High School atau Junior High School</li>
                    <li><strong>Class</strong> - Kelas (contoh: Kelas 12, Kelas 11)</li>
                    <li><strong>Name</strong> - Nama lengkap siswa</li>
                </ul>
                <p class="text-sm text-blue-800 mt-3">
                    <strong>Catatan:</strong> Jika NIS sudah ada, data siswa akan diperbarui. Jika belum ada, siswa baru akan ditambahkan.
                </p>
            </div>

            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File CSV <span class="text-red-500">*</span></label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required class="w-full px-4 py-2 border rounded-lg @error('csv_file') border-red-500 @enderror">
                    @error('csv_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: CSV atau TXT, maksimal 10MB</p>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        <i class="fas fa-file-import mr-2"></i>Import CSV
                    </button>
                </div>
            </form>

            <div class="mt-8 border-t pt-6">
                <h3 class="font-semibold text-gray-900 mb-3">Contoh Format CSV:</h3>
                <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                    <pre class="text-sm">NIS,Unit,Class,Name
23.07.5.10.0001,Senior High School,Kelas 12 ,Aurelia Jesslyn Gunawan
23.07.5.10.0003,Senior High School,Kelas 12 ,Ernest Anthony Rombe
24.07.4.07.0002,Junior High School,Kelas 9 ,Alesdy Ananta Jeng</pre>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

