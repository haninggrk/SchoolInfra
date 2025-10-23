<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register RoomAccess Livewire Components
        Livewire::component('room-inventaris', \Modules\RoomAccess\Http\Livewire\RoomInventaris::class);
        Livewire::component('room-monitoring', \Modules\RoomAccess\Http\Livewire\RoomMonitoring::class);
        Livewire::component('room-pelaporan', \Modules\RoomAccess\Http\Livewire\RoomPelaporan::class);
        Livewire::component('room-pantau-pelaporan', \Modules\RoomAccess\Http\Livewire\RoomPantauPelaporan::class);
        Livewire::component('room-jadwal', \Modules\RoomAccess\Http\Livewire\RoomJadwal::class);
        Livewire::component('room-booking-page', \Modules\RoomAccess\Http\Livewire\RoomBookingPage::class);
    }
}
