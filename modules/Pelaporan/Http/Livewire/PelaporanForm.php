<?php

namespace Modules\Pelaporan\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Shared\Models\DamageReport;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\InventoryItem;

class PelaporanForm extends Component
{
    use WithFileUploads;

    public $roomId;
    public $room;
    public $reporterName = '';
    public $reporterClass = '';
    public $inventoryItemId = '';
    public $description = '';
    public $urgencyLevel = 'sedang';
    public $photos = [];
    public $adminNotes = '';

    protected $rules = [
        'reporterName' => 'required|string|max:255',
        'reporterClass' => 'nullable|string|max:100',
        'inventoryItemId' => 'nullable|exists:inventory_items,id',
        'description' => 'required|string|min:10',
        'urgencyLevel' => 'required|in:rendah,sedang,tinggi',
        'photos.*' => 'nullable|image|max:5120', // 5MB max
    ];

    protected $messages = [
        'reporterName.required' => 'Nama pelapor wajib diisi',
        'description.required' => 'Deskripsi kerusakan wajib diisi',
        'description.min' => 'Deskripsi minimal 10 karakter',
        'urgencyLevel.required' => 'Tingkat urgensi wajib dipilih',
        'photos.*.image' => 'File harus berupa gambar',
        'photos.*.max' => 'Ukuran file maksimal 5MB',
    ];

    public function mount($roomId = null)
    {
        if ($roomId) {
            $this->roomId = $roomId;
            $this->room = Room::findOrFail($roomId);
        }
    }

    public function updatedPhotos()
    {
        $this->validate([
            'photos.*' => 'image|max:5120',
        ]);
    }

    public function submitReport()
    {
        $this->validate();

        $photoPaths = [];
        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $photoPaths[] = $photo->store('damage-reports', 'public');
            }
        }

        DamageReport::create([
            'room_id' => $this->roomId,
            'inventory_item_id' => $this->inventoryItemId ?: null,
            'reporter_name' => $this->reporterName,
            'reporter_class' => $this->reporterClass,
            'description' => $this->description,
            'photos' => $photoPaths,
            'urgency_level' => $this->urgencyLevel,
            'status' => 'baru',
            'reported_at' => now(),
        ]);

        session()->flash('message', __('pelaporan.report_submitted_successfully'));
        
        // Reset form
        $this->reset(['reporterName', 'reporterClass', 'inventoryItemId', 'description', 'urgencyLevel', 'photos']);
    }

    public function render()
    {
        $inventoryItems = collect();
        if ($this->roomId) {
            $inventoryItems = InventoryItem::where('room_id', $this->roomId)
                ->where('status', '!=', 'baik')
                ->orderBy('item_name')
                ->get();
        }

        return view('pelaporan::livewire.pelaporan-form', [
            'inventoryItems' => $inventoryItems,
        ])->layout('components.layouts.app');
    }
}
