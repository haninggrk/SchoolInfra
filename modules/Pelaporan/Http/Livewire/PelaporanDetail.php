<?php

namespace Modules\Pelaporan\Http\Livewire;

use Livewire\Component;
use Modules\Shared\Models\DamageReport;

class PelaporanDetail extends Component
{
    public $reportId;
    public $report;
    public $adminNotes = '';

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->report = DamageReport::with(['room', 'inventoryItem'])->findOrFail($reportId);
        $this->adminNotes = $this->report->admin_notes ?? '';
    }

    public function updateStatus($status)
    {
        $this->report->update(['status' => $status]);
        
        if ($status === 'selesai') {
            $this->report->markAsResolved();
        }
        
        session()->flash('message', __('pelaporan.status_updated_successfully'));
        $this->report = $this->report->fresh();
    }

    public function updateAdminNotes()
    {
        $this->report->update(['admin_notes' => $this->adminNotes]);
        session()->flash('message', __('pelaporan.report_updated_successfully'));
    }

    public function render()
    {
        return view('pelaporan::livewire.pelaporan-detail')->layout('components.layouts.app');
    }
}
