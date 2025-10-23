<?php

use Illuminate\Support\Facades\Route;
use Modules\Shared\Http\Controllers\RoomController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    $rooms = \Modules\Shared\Models\Room::where('is_active', true)
        ->orderBy('building')
        ->orderBy('floor')
        ->orderBy('name')
        ->get();
    return view('welcome', compact('rooms'));
})->name('home');

// Room QR Code Route (public access)
Route::get('/ruangan/{roomCode}', [RoomController::class, 'show'])->name('room.show');

// Generate QR Code
Route::get('/ruangan/{roomCode}/qr', [RoomController::class, 'generateQrCode'])->name('room.qr');

// Room Access Routes (using session-based authentication)
Route::prefix('{roomCode}')->name('room.')->group(function () {
    Route::get('/select-role', [App\Http\Controllers\RoomAccessController::class, 'selectRole'])->name('select-role');
    Route::post('/set-role', [App\Http\Controllers\RoomAccessController::class, 'setRole'])->name('set-role');
    Route::get('/verify-access', [App\Http\Controllers\RoomAccessController::class, 'verifyAccess'])->name('verify-access');
    Route::post('/verify-access', [App\Http\Controllers\RoomAccessController::class, 'verifyAccessCode'])->name('verify-access-code');
    Route::get('/dashboard', [App\Http\Controllers\RoomAccessController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [App\Http\Controllers\RoomAccessController::class, 'logout'])->name('logout');
    
    // Module routes
    Route::get('/inventaris', \Modules\RoomAccess\Http\Livewire\RoomInventaris::class)->name('inventaris');
    Route::get('/monitoring', \Modules\RoomAccess\Http\Livewire\RoomMonitoring::class)->name('monitoring');
    Route::get('/pelaporan', \Modules\RoomAccess\Http\Livewire\RoomPelaporan::class)->name('pelaporan');
    Route::get('/pantau-pelaporan', \Modules\RoomAccess\Http\Livewire\RoomPantauPelaporan::class)->name('pantau-pelaporan');
    Route::get('/jadwal', \Modules\RoomAccess\Http\Livewire\RoomJadwal::class)->name('jadwal');
    Route::get('/booking', \Modules\RoomAccess\Http\Livewire\RoomBookingPage::class)->name('booking');
});

// Authentication routes (no register)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Monitoring routes
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/', \Modules\Monitoring\Http\Livewire\MonitoringIndex::class)->name('index');
        Route::get('/{itemId}', \Modules\Monitoring\Http\Livewire\MonitoringDetail::class)->name('detail');
    });
    
    // Pelaporan routes
    Route::prefix('pelaporan')->name('pelaporan.')->group(function () {
        Route::get('/', \Modules\Pelaporan\Http\Livewire\PelaporanIndex::class)->name('index');
        Route::get('/create', \Modules\Pelaporan\Http\Livewire\PelaporanForm::class)->name('create');
        Route::get('/{reportId}', \Modules\Pelaporan\Http\Livewire\PelaporanDetail::class)->name('detail');
    });
    
    // Inventaris routes (mount Livewire components directly)
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('/', \Modules\Inventaris\Http\Livewire\InventarisIndex::class)->name('index');
        Route::get('/create', \Modules\Inventaris\Http\Livewire\InventarisForm::class)->name('create');
        Route::get('/{itemId}/edit', \Modules\Inventaris\Http\Livewire\InventarisForm::class)->name('edit');
    });
});