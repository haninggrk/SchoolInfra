<?php

namespace Modules\RoomAccess\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\InventoryItem;
use Illuminate\Support\Facades\Session;

class RoomMonitoring extends Component
{
    use WithPagination;

    public $roomCode;
    public $search = '';

    public function mount($roomCode)
    {
        $this->roomCode = $roomCode;
        
        // Only guru can access monitoring
        if (Session::get('room_role') !== 'guru') {
            return redirect()->route('room.dashboard', $roomCode);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $room = Room::where('code', $this->roomCode)->firstOrFail();
        
        $items = InventoryItem::where('room_id', $room->id)
            ->where('is_monitored', true)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('item_name', 'like', '%' . $this->search . '%')
                      ->orWhere('item_code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('item_name')
            ->paginate(10);

        return view('livewire.room-monitoring', [
            'room' => $room,
            'items' => $items,
            'role' => Session::get('room_role'),
        ]);
    }
}

