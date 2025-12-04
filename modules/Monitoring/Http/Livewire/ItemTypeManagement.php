<?php

namespace Modules\Monitoring\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Modules\Shared\Models\ItemType;
use Illuminate\Support\Facades\Storage;

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

    public function openModal($id = null)
    {
        if ($id) {
            $itemType = ItemType::findOrFail($id);
            $this->editingId = $id;
            $this->name = $itemType->name;
            $this->description = $itemType->description;
            $this->is_active = $itemType->is_active;
            $this->existingIconPath = $itemType->icon_path;
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

        if ($this->editingId) {
            $itemType = ItemType::findOrFail($this->editingId);
            $itemType->update($data);
            session()->flash('message', 'Tipe barang berhasil diperbarui.');
        } else {
            ItemType::create($data);
            session()->flash('message', 'Tipe barang berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $itemType = ItemType::findOrFail($id);
        
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

    public function toggleActive($id)
    {
        $itemType = ItemType::findOrFail($id);
        $itemType->update(['is_active' => !$itemType->is_active]);
        session()->flash('message', 'Status tipe barang berhasil diperbarui.');
    }

    public function render()
    {
        $query = ItemType::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $itemTypes = $query->orderBy('name')->paginate(15);

        return view('monitoring::livewire.item-type-management', [
            'itemTypes' => $itemTypes,
        ])->layout('components.layouts.app');
    }
}
