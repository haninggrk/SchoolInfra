<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - SmartSchoolInfra</title>
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
                        <p class="text-sm text-gray-600">Tambah Siswa</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Tambah Siswa Baru</h2>

            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIS <span class="text-red-500">*</span></label>
                        <input type="text" name="nis" value="{{ old('nis') }}" required class="w-full px-4 py-2 border rounded-lg @error('nis') border-red-500 @enderror" placeholder="23.07.5.10.0001">
                        @error('nis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @enderror" placeholder="Nama Lengkap Siswa">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                        <select name="unit" required class="w-full px-4 py-2 border rounded-lg @error('unit') border-red-500 @enderror">
                            <option value="">Pilih Unit</option>
                            <option value="Senior High School" {{ old('unit') == 'Senior High School' ? 'selected' : '' }}>Senior High School</option>
                            <option value="Junior High School" {{ old('unit') == 'Junior High School' ? 'selected' : '' }}>Junior High School</option>
                        </select>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                        <input type="text" name="class" value="{{ old('class') }}" required class="w-full px-4 py-2 border rounded-lg @error('class') border-red-500 @enderror" placeholder="Kelas 12, Kelas 11, dll">
                        @error('class')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

