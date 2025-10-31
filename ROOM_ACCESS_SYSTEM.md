# SmartSchoolInfra - Sistem Akses Berbasis Ruangan

## 📋 Deskripsi Sistem

Sistem akses berbasis ruangan untuk SmartSchoolInfra yang memungkinkan siswa dan guru mengakses fitur-fitur aplikasi melalui QR code ruangan dengan role-based access control.

## 🚀 Alur Penggunaan

### 1. Akses Melalui QR Code/URL
- User mengakses website melalui URL: `smartschoolinfra.com/{kode_ruangan}`
- Contoh: `smartschoolinfra.com/LAB-KOMP-1`

### 2. Pilihan Role
User akan diminta memilih role:
- **Siswa** - Akses langsung tanpa autentikasi
- **Guru** - Memerlukan kode akses verifikasi

### 3. Verifikasi Guru (jika memilih Guru)
- Input kode akses guru
- Sistem memverifikasi kode akses
- Jika valid, sistem menyimpan informasi guru di session

### 4. Dashboard Berbasis Role

#### Menu untuk Siswa:
- ✅ Daftar Inventaris
- ✅ Pelaporan Kerusakan
- ✅ Pantau Pelaporan
- ✅ Jadwal Ruangan (view only)

#### Menu untuk Guru:
- ✅ Daftar Inventaris
- ✅ Monitoring Sarpras (Elektronik)
- ✅ Pelaporan Kerusakan
- ✅ Pantau Pelaporan
- ✅ Booking Ruangan (view + create)

## 🗂️ Struktur File

```
modules/RoomAccess/
├── Http/
│   └── Livewire/
│       ├── RoomInventaris.php
│       ├── RoomMonitoring.php
│       ├── RoomPelaporan.php
│       ├── RoomPantauPelaporan.php
│       ├── RoomJadwal.php
│       └── RoomBookingPage.php
└── resources/
    └── views/
        └── livewire/
            ├── room-inventaris.blade.php
            ├── room-monitoring.blade.php
            ├── room-pelaporan.blade.php
            ├── room-pantau-pelaporan.blade.php
            ├── room-jadwal.blade.php
            └── room-booking.blade.php

resources/views/room-access/
├── select-role.blade.php
├── verify-access.blade.php
└── dashboard.blade.php

app/Http/Controllers/
└── RoomAccessController.php
```

## 🗄️ Database

### Migrasi Baru:
1. `create_room_bookings_table` - Tabel untuk booking ruangan
2. `add_access_code_to_users_table` - Menambah kolom access_code untuk guru

### Models:
- `RoomBooking` - Model untuk booking ruangan
- Update `User` - Menambah relasi roomBookings
- Update `Room` - Menambah relasi bookings

## 👥 Data Guru & Kode Akses

Gunakan seeder untuk membuat data guru:

```bash
php artisan db:seed --class=TeacherSeeder
```

**Kode Akses Guru:**
- Pak Budi Santoso: `GURU001`
- Bu Siti Rahayu: `GURU002`
- Pak Ahmad Hidayat: `GURU003`
- Bu Dewi Lestari: `GURU004`

## 🏢 Data Ruangan

Gunakan seeder untuk membuat data ruangan:

```bash
php artisan db:seed --class=RoomSeeder
```

**Kode Ruangan:**
- Lab Komputer 1: `LAB-KOMP-1`
- Lab IPA: `LAB-IPA-1`
- Ruang Multimedia: `MULTIMEDIA-1`
- Perpustakaan: `PERPUS-1`

## 🛣️ Routes

```php
// Role Selection & Authentication
GET  /{roomCode}/select-role         - Halaman pilih role
POST /{roomCode}/set-role            - Set role (siswa/guru)
GET  /{roomCode}/verify-access       - Halaman verifikasi kode akses
POST /{roomCode}/verify-access       - Verifikasi kode akses guru
GET  /{roomCode}/dashboard           - Dashboard berbasis role
GET  /{roomCode}/logout              - Logout dari session

// Feature Routes
GET  /{roomCode}/inventaris          - Daftar inventaris
GET  /{roomCode}/monitoring          - Monitoring sarpras (guru only)
GET  /{roomCode}/pelaporan           - Pelaporan kerusakan
GET  /{roomCode}/pantau-pelaporan    - Pantau status pelaporan
GET  /{roomCode}/jadwal              - Jadwal ruangan (siswa)
GET  /{roomCode}/booking             - Booking ruangan (guru)
```

## 📱 Responsive Design

Seluruh tampilan telah dioptimalkan untuk:
- 📱 Mobile (smartphones)
- 💻 Tablet
- 🖥️ Desktop

### Fitur Mobile-Friendly:
- Touch-friendly buttons
- Responsive grid layouts
- Mobile-optimized forms
- Sticky navigation
- Modal dialogs untuk mobile
- Card-based layouts untuk list items

## 🎨 UI/UX Features

### Halaman Role Selection:
- Card-based selection
- Clear visual indicators
- Hover effects
- Feature lists untuk setiap role

### Dashboard:
- Role-specific menu cards
- Color-coded by function
- Icon-based navigation
- Responsive grid layout

### Forms & Modals:
- Full-screen modals pada mobile
- Touch-friendly inputs
- Clear validation messages
- Date/time pickers yang mobile-friendly

## 🔐 Session Management

Sistem menggunakan session untuk menyimpan:
- `room_role` - Role user (siswa/guru)
- `room_code` - Kode ruangan yang diakses
- `guru_id` - ID guru (jika role = guru)
- `guru_name` - Nama guru (jika role = guru)

## 🧪 Testing

### Test Manual:

1. **Test Akses Siswa:**
   ```
   - Buka: http://localhost/{roomCode}/select-role
   - Pilih: Siswa
   - Verifikasi: Dapat akses 4 menu (Inventaris, Pelaporan, Pantau, Jadwal)
   ```

2. **Test Akses Guru:**
   ```
   - Buka: http://localhost/{roomCode}/select-role
   - Pilih: Guru
   - Input kode akses: GURU001
   - Verifikasi: Dapat akses 5 menu (termasuk Monitoring & Booking)
   ```

3. **Test Booking Ruangan:**
   ```
   - Login sebagai Guru
   - Buka menu Booking Ruangan
   - Klik "Tambah Booking"
   - Isi form dan submit
   - Verifikasi: Booking muncul di jadwal
   ```

## 🔧 Instalasi & Setup

1. **Jalankan Migrasi:**
   ```bash
   php artisan migrate
   ```

2. **Seed Data:**
   ```bash
   php artisan db:seed --class=RoomSeeder
   php artisan db:seed --class=TeacherSeeder
   ```

3. **Generate QR Code untuk Ruangan:**
   ```bash
   # Buat QR code untuk setiap ruangan
   # Akses: http://localhost/ruangan/{roomCode}/qr
   ```

## 📊 Fitur-Fitur Utama

### 1. Daftar Inventaris
- View semua inventaris ruangan
- Filter by kategori
- Search by nama/kode
- Responsive table/card layout

### 2. Monitoring Sarpras (Guru Only)
- View perangkat elektronik
- Monitor kondisi perangkat
- Search functionality

### 3. Pelaporan Kerusakan
- Form pelaporan kerusakan
- Select item dari inventaris
- Tingkat kerusakan (ringan/sedang/berat)
- Input nama & kontak pelapor

### 4. Pantau Pelaporan
- View semua laporan kerusakan
- Filter by status
- Timeline view
- Status badges

### 5. Jadwal Ruangan (Siswa)
- View jadwal pemakaian ruangan
- Mode tampilan: Hari/Minggu/Bulan
- Timeline view
- Info guru & mata pelajaran

### 6. Booking Ruangan (Guru)
- View jadwal yang ada
- Tambah booking baru
- Validasi conflict booking
- Cancel booking sendiri
- Form dengan date/time picker

## 🎯 Best Practices

1. **Session Management:**
   - Selalu cek session sebelum render
   - Redirect jika session invalid
   - Clear session saat logout

2. **Mobile Responsiveness:**
   - Gunakan Tailwind responsive classes
   - Test di berbagai ukuran layar
   - Gunakan touch-friendly button sizes

3. **User Experience:**
   - Clear error messages
   - Loading indicators
   - Success feedback
   - Intuitive navigation

## 📝 Notes

- Sistem ini menggunakan session-based authentication, berbeda dengan auth regular Laravel
- Tidak ada register user, semua akses melalui QR code ruangan
- Guru dapat memiliki multiple access codes jika diperlukan
- Booking ruangan otomatis approved (bisa diubah jika diperlukan workflow approval)

## 🐛 Troubleshooting

### Issue: Class RoomBooking conflict
**Solution:** Class sudah direname ke `RoomBookingPage` untuk menghindari konflik dengan Model

### Issue: Session tidak persist
**Solution:** Pastikan session driver di `.env` sudah dikonfigurasi dengan benar

### Issue: Views tidak ditemukan
**Solution:** Pastikan namespace views sudah sesuai dengan struktur folder

## 🔄 Update History

- **v1.0** - Initial implementation
  - Role selection system
  - Teacher verification
  - Role-based dashboard
  - 6 main features (Inventaris, Monitoring, Pelaporan, Pantau, Jadwal, Booking)
  - Full responsive design
  - Session management

## 👨‍💻 Developer Notes

Untuk menambah kode akses guru baru:
```php
User::create([
    'name' => 'Nama Guru',
    'email' => 'email@school.id',
    'password' => Hash::make('password'),
    'role' => 'guru',
    'access_code' => 'GURU005', // Unique code
    'is_active' => true,
]);
```

Untuk menambah ruangan baru:
```php
Room::create([
    'name' => 'Nama Ruangan',
    'code' => 'KODE-UNIK',
    'description' => 'Deskripsi',
    'floor' => '1',
    'building' => 'Gedung A',
    'is_active' => true,
]);
```




