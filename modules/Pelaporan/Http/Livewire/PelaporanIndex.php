<?php

namespace Modules\Pelaporan\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Shared\Models\DamageReport;
use Modules\Shared\Models\Room;

class PelaporanIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $roomFilter = '';
    public $statusFilter = '';
    public $urgencyFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'roomFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'urgencyFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoomFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingUrgencyFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->roomFilter = '';
        $this->statusFilter = '';
        $this->urgencyFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function updateStatus($reportId, $status)
    {
        $report = DamageReport::findOrFail($reportId);
        $report->update(['status' => $status]);
        
        if ($status === 'selesai') {
            $report->markAsResolved();
        }
        
        session()->flash('message', __('pelaporan.status_updated_successfully'));
    }

    public function render()
    {
        $query = DamageReport::with(['room', 'inventoryItem']);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('reporter_name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('reporter_class', 'like', '%' . $this->search . '%');
            });
        }

        // Apply room filter
        if ($this->roomFilter) {
            $query->where('room_id', $this->roomFilter);
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply urgency filter
        if ($this->urgencyFilter) {
            $query->where('urgency_level', $this->urgencyFilter);
        }

        // Apply date filters
        if ($this->dateFrom) {
            $query->whereDate('reported_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('reported_at', '<=', $this->dateTo);
        }

        $reports = $query->orderBy('reported_at', 'desc')->paginate($this->perPage);

        $rooms = Room::where('is_active', true)->orderBy('name')->get();
        $statuses = ['baru', 'sedang_diproses', 'selesai'];
        $urgencyLevels = ['rendah', 'sedang', 'tinggi'];

        return view('pelaporan::livewire.pelaporan-index', [
            'reports' => $reports,
            'rooms' => $rooms,
            'statuses' => $statuses,
            'urgencyLevels' => $urgencyLevels,
        ])->layout('components.layouts.app');
    }
}
