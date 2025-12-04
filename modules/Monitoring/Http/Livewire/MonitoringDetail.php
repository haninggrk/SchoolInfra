<?php

namespace Modules\Monitoring\Http\Livewire;

use Livewire\Component;
use Modules\Shared\Models\InventoryItem;

class MonitoringDetail extends Component
{
    public $itemId;
    public $item;
    public $purchase_date = '';
    public $last_maintenance_date = '';
    public $next_maintenance_date = '';
    public $isEditingDates = false;

    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->item = InventoryItem::with('room')->findOrFail($itemId);
        $this->loadDates();
    }

    public function loadDates()
    {
        $this->purchase_date = $this->item->purchase_date ? $this->item->purchase_date->format('Y-m-d') : '';
        $this->last_maintenance_date = $this->item->last_maintenance_date ? $this->item->last_maintenance_date->format('Y-m-d') : '';
        $this->next_maintenance_date = $this->item->next_maintenance_date ? $this->item->next_maintenance_date->format('Y-m-d') : '';
    }

    public function toggleEditDates()
    {
        $this->isEditingDates = !$this->isEditingDates;
        if (!$this->isEditingDates) {
            $this->loadDates(); // Reset jika cancel
        }
    }

    public function saveMaintenanceDates()
    {
        $this->item->update([
            'purchase_date' => $this->purchase_date ?: null,
            'last_maintenance_date' => $this->last_maintenance_date ?: null,
            'next_maintenance_date' => $this->next_maintenance_date ?: null,
        ]);
        
        $this->item = $this->item->fresh();
        $this->loadDates();
        $this->isEditingDates = false;
        
        session()->flash('message', 'Tanggal maintenance berhasil diperbarui.');
    }

    public function updateStatus($status)
    {
        $this->item->update(['status' => $status]);
        
        session()->flash('message', __('monitoring.item_updated_successfully'));
        $this->item = $this->item->fresh();
    }

    public function generateQrCode()
    {
        // Generate QR code logic here
        session()->flash('message', __('monitoring.qr_code_generated'));
    }

    public function render()
    {
        return view('monitoring::livewire.monitoring-detail')->layout('components.layouts.app');
    }
}
