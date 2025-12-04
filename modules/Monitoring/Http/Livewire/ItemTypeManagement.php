<?php

namespace Modules\Monitoring\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Modules\Shared\Models\ItemType;
use Modules\Shared\Models\InventoryItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ItemTypeManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $icon = null;
    public $is_active = true;
    public $existingIconPath = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'icon' => 'nullable|image|max:2048',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($name = null)
    {
        if ($name) {
            // Find existing ItemType or create new one from name
            $itemType = ItemType::firstOrNew(['name' => $name]);
            $this->editingId = $itemType->id;
            $this->name = $itemType->name ?? $name;
            $this->description = $itemType->description ?? '';
            $this->is_active = $itemType->is_active ?? true;
            $this->existingIconPath = $itemType->icon_path ?? null;
        } else {
            $this->resetForm();
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->icon = null;
        $this->is_active = true;
        $this->existingIconPath = null;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        // Handle icon upload
        if ($this->icon) {
            // Delete old icon if exists
            if ($this->existingIconPath && Storage::disk('public')->exists($this->existingIconPath)) {
                Storage::disk('public')->delete($this->existingIconPath);
            }

            // Store new icon
            $iconPath = $this->icon->store('item-type-icons', 'public');
            $data['icon_path'] = $iconPath;
        } elseif (!$this->editingId) {
            // If creating new and no icon provided, set to null
            $data['icon_path'] = null;
        }

        // Use updateOrCreate to handle both new and existing item types
        $itemType = ItemType::updateOrCreate(
            ['name' => $this->name],
            $data
        );
        
        if ($this->editingId && $itemType->wasRecentlyCreated === false) {
            session()->flash('message', 'Tipe barang berhasil diperbarui.');
        } else {
            session()->flash('message', 'Tipe barang berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function delete($name)
    {
        $itemType = ItemType::where('name', $name)->first();
        
        if (!$itemType) {
            session()->flash('error', 'Tipe barang tidak ditemukan.');
            return;
        }
        
        // Check if item type is being used
        if ($itemType->inventoryItems()->count() > 0) {
            session()->flash('error', 'Tipe barang tidak dapat dihapus karena masih digunakan oleh beberapa barang.');
            return;
        }

        // Delete icon file if exists
        if ($itemType->icon_path && Storage::disk('public')->exists($itemType->icon_path)) {
            Storage::disk('public')->delete($itemType->icon_path);
        }

        $itemType->delete();
        session()->flash('message', 'Tipe barang berhasil dihapus.');
    }

    public function toggleActive($name)
    {
        $itemType = ItemType::firstOrCreate(['name' => $name], ['is_active' => true]);
        $itemType->update(['is_active' => !$itemType->is_active]);
        session()->flash('message', 'Status tipe barang berhasil diperbarui.');
    }

    public function render()
    {
        // Get all unique item types from inventory_items
        $existingItemTypes = InventoryItem::whereNotNull('item_type')
            ->where('item_type', '!=', '')
            ->distinct()
            ->pluck('item_type')
            ->toArray();

        // Get all item types from item_types table
        $itemTypesWithData = ItemType::all()->keyBy('name');

        // Combine: get all unique names and merge with item_types data
        $allItemTypeNames = array_unique($existingItemTypes);
        
        // Create collection with all item types
        $itemTypesCollection = collect($allItemTypeNames)->map(function ($name) use ($itemTypesWithData) {
            $itemType = $itemTypesWithData->get($name);
            
            return (object) [
                'name' => $name,
                'icon_path' => $itemType ? $itemType->icon_path : null,
                'description' => $itemType ? $itemType->description : null,
                'is_active' => $itemType ? $itemType->is_active : true,
                'id' => $itemType ? $itemType->id : null,
                'item_count' => InventoryItem::where('item_type', $name)->count(),
            ];
        });

        // Apply search filter
        if ($this->search) {
            $itemTypesCollection = $itemTypesCollection->filter(function ($itemType) {
                return stripos($itemType->name, $this->search) !== false;
            });
        }

        // Sort and paginate manually
        $sorted = $itemTypesCollection->sortBy('name')->values();
        $currentPage = request()->get('page', 1);
        $perPage = 15;
        $offset = ($currentPage - 1) * $perPage;
        $currentItems = $sorted->slice($offset, $perPage)->values();
        
        $itemTypes = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $sorted->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('monitoring::livewire.item-type-management', [
            'itemTypes' => $itemTypes,
        ])->layout('components.layouts.app');
    }
}
