<?php

namespace Modules\RoomAccess\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\InventoryItem;
use Modules\Shared\Models\DamageReport;
use Illuminate\Support\Facades\Session;

class RoomPelaporan extends Component
{
    use WithFileUploads;

    public $roomCode;
    public $showReportForm = false;
    
    // Form fields
    public $inventory_item_id = '';
    public $description = '';
    public $urgency_level = 'sedang';
    public $reporter_name = '';
    public $reporter_contact = '';
    public $photo;

    protected $rules = [
        'inventory_item_id' => 'nullable|exists:inventory_items,id',
        'description' => 'required|string',
        'urgency_level' => 'required|in:rendah,sedang,tinggi',
        'reporter_name' => 'required|string|max:255',
        'reporter_contact' => 'nullable|string|max:255',
    ];

    public function mount($roomCode)
    {
        $this->roomCode = $roomCode;
        
        if (!Session::has('room_role')) {
            return redirect()->route('room.select-role', $roomCode);
        }
    }

    public function openReportForm()
    {
        $this->showReportForm = true;
        $this->resetForm();
    }

    public function closeReportForm()
    {
        $this->showReportForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->inventory_item_id = '';
        $this->description = '';
        $this->urgency_level = 'sedang';
        $this->reporter_name = Session::get('room_role') === 'guru' ? Session::get('guru_name') : '';
        $this->reporter_contact = '';
        $this->photo = null;
    }

    public function createReport()
    {
        $this->validate();

        $room = Room::where('code', $this->roomCode)->firstOrFail();
        
        $report = DamageReport::create([
            'room_id' => $room->id,
            'inventory_item_id' => $this->inventory_item_id ?: null,
            'description' => $this->description,
            'urgency_level' => $this->urgency_level,
            'reporter_name' => $this->reporter_name,
            'reporter_contact' => $this->reporter_contact,
            'status' => 'baru',
            'reported_at' => now(),
        ]);

        session()->flash('message', 'Laporan kerusakan berhasil dibuat!');
        return redirect()->route('room.pantau-pelaporan', $this->roomCode);
    }

    public function render()
    {
        $room = Room::where('code', $this->roomCode)->firstOrFail();
        
        $inventoryItems = InventoryItem::where('room_id', $room->id)
            ->orderBy('item_name')
            ->get();

        return view('livewire.room-pelaporan', [
            'room' => $room,
            'inventoryItems' => $inventoryItems,
            'role' => Session::get('room_role'),
        ]);
    }
}

