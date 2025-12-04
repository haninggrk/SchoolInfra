<?php

namespace Modules\Monitoring\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Shared\Models\InventoryItem;
use Modules\Shared\Models\Room;

class MonitoringIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $roomCodeFilter = '';
    public $itemTypeFilter = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $showModal = false;
    public $selectedItem = null;
    public $showQrModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'roomCodeFilter' => ['except' => ''],
        'itemTypeFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount()
    {
        // Clear any existing QR code data from previous sessions
        session()->forget(['qr_code_data', 'qr_code_filename']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoomCodeFilter()
    {
        $this->resetPage();
    }
    
    public function updatingItemTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->roomCodeFilter = '';
        $this->itemTypeFilter = '';
        $this->categoryFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }
    
    public function showDetail($itemId)
    {
        $this->selectedItem = InventoryItem::with(['room', 'itemTypeRelation'])->findOrFail($itemId);
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedItem = null;
    }
    
    public function closeQrModal()
    {
        $this->showQrModal = false;
        // Clear QR data from session when modal is closed
        session()->forget(['qr_code_data', 'qr_code_filename']);
    }
    
    public function generateQrCode($itemId)
    {
        try {
            $item = InventoryItem::findOrFail($itemId);
            
            // Generate QR code URL
            $qrUrl = route('monitoring.detail', $item->id);
            
            // Generate QR code as SVG (doesn't require ImageMagick)
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(200)
                ->generate($qrUrl);
            
            // Store QR code in session for download
            session(['qr_code_data' => $qrCode]);
            session(['qr_code_filename' => 'qr_' . $item->item_name . '_' . time() . '.svg']);
            
            // Show QR modal
            $this->showQrModal = true;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membuat QR Code: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = InventoryItem::with(['room', 'itemTypeRelation'])
            ->digital()
            ->activeBarcode();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('item_type', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }

        // Apply room code filter
        if ($this->roomCodeFilter) {
            $query->whereHas('room', function($q) {
                $q->where('code', $this->roomCodeFilter);
            });
        }
        
        // Apply item type filter
        if ($this->itemTypeFilter) {
            $query->where('item_type', $this->itemTypeFilter);
        }

        // Apply category filter
        if ($this->categoryFilter) {
            $query->where('item_category', $this->categoryFilter);
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        $roomCodes = Room::where('is_active', true)->distinct()->orderBy('code')->pluck('code');
        $itemTypes = InventoryItem::digital()->distinct()->orderBy('item_type')->pluck('item_type')->filter();
        $categories = InventoryItem::digital()->distinct()->orderBy('item_category')->pluck('item_category')->filter();
        $statuses = ['baik', 'rusak', 'dalam_perbaikan'];

        return view('monitoring::livewire.monitoring-index', [
            'items' => $items,
            'roomCodes' => $roomCodes,
            'itemTypes' => $itemTypes,
            'categories' => $categories,
            'statuses' => $statuses,
        ])->layout('components.layouts.app');
    }
}
