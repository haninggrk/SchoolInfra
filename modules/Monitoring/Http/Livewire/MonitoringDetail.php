<?php

namespace Modules\Monitoring\Http\Livewire;

use Livewire\Component;
use Modules\Shared\Models\InventoryItem;

class MonitoringDetail extends Component
{
    public $itemId;
    public $item;

    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->item = InventoryItem::with(['room', 'itemTypeRelation'])->findOrFail($itemId);
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
