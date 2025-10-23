<?php

namespace Modules\Inventaris\Http\Livewire;

use Livewire\Component;
use Modules\Shared\Models\InventoryItem;
use Modules\Shared\Models\Room;

class InventarisForm extends Component
{
    public $itemId;
    public $item;
    public $roomId = '';
    public $itemCategory = '';
    public $itemType = '';
    public $itemName = '';
    public $barcode = '';
    public $prefix = '';
    public $qty = 1;
    public $unit = 'pcs';
    public $dateAdded = '';
    public $barcodeStatus = 'active';
    public $status = 'baik';
    public $notes = '';

    protected $rules = [
        'roomId' => 'required|exists:rooms,id',
        'itemCategory' => 'required|string|max:255',
        'itemType' => 'required|string|max:255',
        'itemName' => 'required|string|max:255',
        'barcode' => 'required|string|max:255|unique:inventory_items,barcode',
        'prefix' => 'required|string|max:50',
        'qty' => 'required|integer|min:1',
        'unit' => 'required|string|max:20',
        'dateAdded' => 'required|date',
        'barcodeStatus' => 'required|in:active,inactive',
        'status' => 'required|in:baik,rusak,dalam_perbaikan',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'roomId.required' => 'Ruangan wajib dipilih',
        'itemCategory.required' => 'Kategori item wajib diisi',
        'itemType.required' => 'Jenis item wajib diisi',
        'itemName.required' => 'Nama item wajib diisi',
        'barcode.required' => 'Barcode wajib diisi',
        'barcode.unique' => 'Barcode sudah digunakan',
        'prefix.required' => 'Prefix wajib diisi',
        'qty.required' => 'Jumlah wajib diisi',
        'qty.min' => 'Jumlah minimal 1',
        'dateAdded.required' => 'Tanggal ditambahkan wajib diisi',
    ];

    public function mount($itemId = null)
    {
        if ($itemId) {
            $this->itemId = $itemId;
            $this->item = InventoryItem::findOrFail($itemId);
            $this->roomId = $this->item->room_id;
            $this->itemCategory = $this->item->item_category;
            $this->itemType = $this->item->item_type;
            $this->itemName = $this->item->item_name;
            $this->barcode = $this->item->barcode;
            $this->prefix = $this->item->prefix;
            $this->qty = $this->item->qty;
            $this->unit = $this->item->unit;
            $this->dateAdded = $this->item->date_added->format('Y-m-d');
            $this->barcodeStatus = $this->item->barcode_status;
            $this->status = $this->item->status;
            $this->notes = $this->item->notes ?? '';
        } else {
            $this->dateAdded = now()->format('Y-m-d');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'room_id' => $this->roomId,
            'item_category' => $this->itemCategory,
            'item_type' => $this->itemType,
            'item_name' => $this->itemName,
            'barcode' => $this->barcode,
            'prefix' => $this->prefix,
            'qty' => $this->qty,
            'unit' => $this->unit,
            'date_added' => $this->dateAdded,
            'barcode_status' => $this->barcodeStatus,
            'status' => $this->status,
            'notes' => $this->notes,
        ];

        if ($this->itemId) {
            $this->item->update($data);
            session()->flash('message', __('inventaris.item_updated_successfully'));
        } else {
            InventoryItem::create($data);
            session()->flash('message', __('inventaris.item_added_successfully'));
        }

        return redirect()->route('inventaris.index');
    }

    public function render()
    {
        $rooms = Room::where('is_active', true)
            ->distinct()
            ->orderBy('code')
            ->get();
        $categories = [
            'Elektronik',
            'Furnitur',
            'Alat Tulis',
            'Alat Kebersihan',
            'Alat Olahraga',
            'Alat Laboratorium',
            'Alat Perpustakaan',
            'Lainnya',
        ];
        $units = ['pcs', 'unit', 'set', 'buah'];
        $statuses = ['baik', 'rusak', 'dalam_perbaikan'];
        $barcodeStatuses = ['active', 'inactive'];

        return view('inventaris::livewire.inventaris-form', [
            'rooms' => $rooms,
            'categories' => $categories,
            'units' => $units,
            'statuses' => $statuses,
            'barcodeStatuses' => $barcodeStatuses,
        ])->layout('components.layouts.app');
    }
}
