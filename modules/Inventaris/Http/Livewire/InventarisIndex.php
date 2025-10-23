<?php

namespace Modules\Inventaris\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Shared\Models\InventoryItem;
use Modules\Shared\Models\Room;

class InventarisIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $roomCodeFilter = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $barcodeStatusFilter = '';
    public $perPage = 10;
    public $selectedItems = [];
    public $showModal = false;
    public $selectedItem = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'roomCodeFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'barcodeStatusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoomCodeFilter()
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

    public function updatingBarcodeStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->roomCodeFilter = '';
        $this->categoryFilter = '';
        $this->statusFilter = '';
        $this->barcodeStatusFilter = '';
        $this->selectedItems = [];
        $this->resetPage();
    }

    public function selectAll()
    {
        $this->selectedItems = $this->getItems()->pluck('id')->toArray();
    }

    public function deselectAll()
    {
        $this->selectedItems = [];
    }

    public function toggleItem($itemId)
    {
        if (in_array($itemId, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$itemId]);
        } else {
            $this->selectedItems[] = $itemId;
        }
    }

    public function showDetail($itemId)
    {
        $this->selectedItem = InventoryItem::with('room')->findOrFail($itemId);
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedItem = null;
    }
    
    public function deleteItem($itemId)
    {
        InventoryItem::findOrFail($itemId)->delete();
        $this->closeModal();
        session()->flash('message', __('inventaris.item_deleted_successfully'));
    }
    
    public function deleteSelected()
    {
        if (count($this->selectedItems) > 0) {
            InventoryItem::whereIn('id', $this->selectedItems)->delete();
            $this->selectedItems = [];
            session()->flash('message', __('inventaris.items_deleted_successfully'));
        }
    }

    public function exportCsv()
    {
        $items = $this->getItems();
        // Export logic here
        session()->flash('message', __('inventaris.items_exported_successfully'));
    }

    public function exportExcel()
    {
        $items = $this->getItems();
        // Export logic here
        session()->flash('message', __('inventaris.items_exported_successfully'));
    }

    private function getItems()
    {
        $query = InventoryItem::with('room');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('item_type', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%')
                  ->orWhere('prefix', 'like', '%' . $this->search . '%');
            });
        }

        // Apply room code filter
        if ($this->roomCodeFilter) {
            $query->whereHas('room', function($q) {
                $q->where('code', $this->roomCodeFilter);
            });
        }

        // Apply category filter
        if ($this->categoryFilter) {
            $query->where('item_category', $this->categoryFilter);
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply barcode status filter
        if ($this->barcodeStatusFilter) {
            $query->where('barcode_status', $this->barcodeStatusFilter);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        $query = InventoryItem::with('room');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('item_type', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%')
                  ->orWhere('prefix', 'like', '%' . $this->search . '%');
            });
        }

        // Apply room code filter
        if ($this->roomCodeFilter) {
            $query->whereHas('room', function($q) {
                $q->where('code', $this->roomCodeFilter);
            });
        }

        // Apply category filter
        if ($this->categoryFilter) {
            $query->where('item_category', $this->categoryFilter);
        }

        // Apply status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply barcode status filter
        if ($this->barcodeStatusFilter) {
            $query->where('barcode_status', $this->barcodeStatusFilter);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        $roomCodes = Room::where('is_active', true)->distinct()->orderBy('code')->pluck('code');
        $categories = InventoryItem::distinct()->orderBy('item_category')->pluck('item_category')->filter();
        $statuses = ['baik', 'rusak', 'dalam_perbaikan'];
        $barcodeStatuses = ['active', 'inactive'];

        return view('inventaris::livewire.inventaris-index', [
            'items' => $items,
            'roomCodes' => $roomCodes,
            'categories' => $categories,
            'statuses' => $statuses,
            'barcodeStatuses' => $barcodeStatuses,
        ])->layout('components.layouts.app');
    }
}
