# 🏫 Sistem Manajemen Sarana & Prasarana Sekolah

Aplikasi Laravel + Livewire untuk manajemen sarana dan prasarana sekolah dengan akses QR Code.

## ✨ Fitur Utama

### 🖥️ Monitoring Sarana & Prasarana (Digital Inventaris)
- Menampilkan daftar sarpras elektronik atau digital seperti PC, proyektor, printer, CCTV, dll.
- Filter berdasarkan ruangan, kategori, dan status
- Status: Baik, Rusak, Dalam Perbaikan

### 📋 Pelaporan Kerusakan
- Guru/siswa dapat melaporkan kerusakan di ruangan mereka
- Form laporan berisi:
  - Pilihan item dari daftar sarpras ruangan
  - Deskripsi kerusakan
  - Upload foto (opsional)
  - Tingkat urgensi (Rendah, Sedang, Tinggi)
  - Identitas pelapor (nama dan kelas) — diisi manual
- Status laporan: Baru, Sedang Diproses, Selesai

### 📊 Pantau Laporan Kerusakan
- Admin/petugas sarpras dapat melihat semua laporan
- Filter berdasarkan ruangan, tanggal, kategori, atau status
- Update status laporan dan tambahkan catatan tindak lanjut

### 📦 Daftar Inventaris (Semua Jenis Sarpras)
- Menampilkan semua inventaris sekolah
- Admin dapat menambah, mengedit, menghapus, dan ekspor data (CSV/Excel)
- Monitoring adalah subset dari inventaris ini, khusus untuk aset digital

## 🚀 Teknologi

- **Framework**: Laravel 12
- **Frontend**: Livewire 3 + TailwindCSS 4
- **Database**: MySQL
- **Arsitektur**: Modular (modules/Monitoring, modules/Pelaporan, modules/Inventaris)
- **Autentikasi**: Login sederhana untuk admin/guru
- **Akses QR**: Route `/ruangan/{room_code}` menampilkan seluruh fitur ruangan

## 📁 Struktur Modular

```
modules/
├── Monitoring/          # Monitoring Sarana & Prasarana
├── Pelaporan/           # Pelaporan Kerusakan
├── Inventaris/          # Daftar Inventaris
└── Shared/              # Model dan Controller bersama
```

## 🗄️ Struktur Database

### Tabel Utama
- **rooms**: Data ruangan
- **inventory_items**: Data inventaris
- **damage_reports**: Laporan kerusakan
- **users**: Data pengguna (admin/guru)

## 🌐 Lokalisasi

Semua teks menggunakan Laravel translation helper (`__('string.key')`) dengan file bahasa Indonesia di `lang/id/`.

## ⚙️ Instalasi

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd Mychael
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sarpras_school
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

## 👥 Akun Default

Setelah menjalankan seeder, tersedia akun default:

- **Admin**: admin@school.com / password
- **Guru**: guru@school.com / password

## 🎯 Cara Penggunaan

### 1. Akses Admin/Guru
- Login di `/login`
- Akses dashboard di `/dashboard`
- Kelola inventaris, monitoring, dan laporan

### 2. Akses QR Code
- Scan QR Code ruangan
- Langsung akses halaman ruangan di `/ruangan/{room_code}`
- Tampil semua fitur ruangan dalam satu tampilan

### 3. Fitur QR Code
- Setiap ruangan memiliki QR Code unik
- Siswa tidak perlu login untuk akses
- Langsung ke halaman ruangan dengan semua fitur

## 📱 Responsive Design

Aplikasi dirancang mobile-friendly dengan TailwindCSS untuk kemudahan akses di berbagai perangkat.

## 🔧 Development

### Menambah Modul Baru
1. Buat direktori di `modules/NamaModul/`
2. Tambahkan namespace di `ModuleServiceProvider`
3. Buat Livewire components
4. Tambahkan routes di `web.php`

### Menambah Bahasa
1. Buat file di `lang/{locale}/`
2. Update `config/app.php` locale
3. Gunakan `__('key')` di views

## 📄 License

MIT License - bebas digunakan untuk keperluan pendidikan.

## 🤝 Kontribusi

Silakan buat issue atau pull request untuk perbaikan dan fitur baru.

---

**Dibuat dengan ❤️ untuk kemudahan manajemen sarana dan prasarana sekolah**