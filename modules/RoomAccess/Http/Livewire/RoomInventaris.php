<?php

namespace Modules\RoomAccess\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\InventoryItem;
use Illuminate\Support\Facades\Session;

class RoomInventaris extends Component
{
    use WithPagination;

    public $roomCode;
    public $search = '';
    public $categoryFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    public function mount($roomCode)
    {
        $this->roomCode = $roomCode;
        
        // Check if user has access
        if (!Session::has('room_role')) {
            return redirect()->route('room.select-role', $roomCode);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $room = Room::where('code', $this->roomCode)->firstOrFail();
        
        $items = InventoryItem::where('room_id', $room->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('item_name', 'like', '%' . $this->search . '%')
                      ->orWhere('item_code', 'like', '%' . $this->search . '%')
                      ->orWhere('brand', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('item_category', $this->categoryFilter);
            })
            ->orderBy('item_name')
            ->paginate(5);

        $categories = InventoryItem::where('room_id', $room->id)
            ->distinct()
            ->pluck('item_category');

        return view('livewire.room-inventaris', [
            'room' => $room,
            'items' => $items,
            'categories' => $categories,
            'role' => Session::get('room_role'),
        ]);
    }
}

