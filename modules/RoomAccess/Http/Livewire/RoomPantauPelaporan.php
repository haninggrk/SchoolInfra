<?php

namespace Modules\RoomAccess\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\DamageReport;
use Illuminate\Support\Facades\Session;

class RoomPantauPelaporan extends Component
{
    use WithPagination;

    public $roomCode;
    public $statusFilter = '';

    public function mount($roomCode)
    {
        $this->roomCode = $roomCode;
        
        if (!Session::has('room_role')) {
            return redirect()->route('room.select-role', $roomCode);
        }
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $room = Room::where('code', $this->roomCode)->firstOrFail();
        
        $reports = DamageReport::where('room_id', $room->id)
            ->with('inventoryItem')
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.room-pantau-pelaporan', [
            'room' => $room,
            'reports' => $reports,
            'role' => Session::get('room_role'),
        ]);
    }
}

