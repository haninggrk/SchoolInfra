<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register module views
        View::addNamespace('monitoring', base_path('modules/Monitoring/resources/views'));
        View::addNamespace('pelaporan', base_path('modules/Pelaporan/resources/views'));
        View::addNamespace('inventaris', base_path('modules/Inventaris/resources/views'));
        View::addNamespace('room', base_path('modules/Shared/resources/views'));
        
        // Register Livewire components
        $this->registerLivewireComponents();
        
        // Translations: use Laravel's default loader from `lang/`.
        // No explicit registration needed.
    }
    
    /**
     * Register all Livewire components from modules
     */
    private function registerLivewireComponents(): void
    {
        // Monitoring Module
        Livewire::component('monitoring-index', \Modules\Monitoring\Http\Livewire\MonitoringIndex::class);
        Livewire::component('monitoring-detail', \Modules\Monitoring\Http\Livewire\MonitoringDetail::class);
        
        // Pelaporan Module
        Livewire::component('pelaporan-index', \Modules\Pelaporan\Http\Livewire\PelaporanIndex::class);
        Livewire::component('pelaporan-form', \Modules\Pelaporan\Http\Livewire\PelaporanForm::class);
        Livewire::component('pelaporan-detail', \Modules\Pelaporan\Http\Livewire\PelaporanDetail::class);
        
        // Inventaris Module
        Livewire::component('inventaris-index', \Modules\Inventaris\Http\Livewire\InventarisIndex::class);
        Livewire::component('inventaris-form', \Modules\Inventaris\Http\Livewire\InventarisForm::class);
    }
}
