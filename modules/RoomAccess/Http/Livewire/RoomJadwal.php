<?php

namespace Modules\RoomAccess\Http\Livewire;

use Livewire\Component;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\RoomBooking;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class RoomJadwal extends Component
{
    public $roomCode;
    public $selectedDate;
    public $viewMode = 'week'; // 'day', 'week', 'month'

    public function mount($roomCode)
    {
        $this->roomCode = $roomCode;
        $this->selectedDate = now()->toDateString();
        
        // Check if user has access
        if (!Session::has('room_role')) {
            return redirect()->route('room.select-role', $roomCode);
        }
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function previousPeriod()
    {
        $date = Carbon::parse($this->selectedDate);
        
        switch ($this->viewMode) {
            case 'day':
                $this->selectedDate = $date->subDay()->toDateString();
                break;
            case 'week':
                $this->selectedDate = $date->subWeek()->toDateString();
                break;
            case 'month':
                $this->selectedDate = $date->subMonth()->toDateString();
                break;
        }
    }

    public function nextPeriod()
    {
        $date = Carbon::parse($this->selectedDate);
        
        switch ($this->viewMode) {
            case 'day':
                $this->selectedDate = $date->addDay()->toDateString();
                break;
            case 'week':
                $this->selectedDate = $date->addWeek()->toDateString();
                break;
            case 'month':
                $this->selectedDate = $date->addMonth()->toDateString();
                break;
        }
    }

    public function today()
    {
        $this->selectedDate = now()->toDateString();
    }

    public function render()
    {
        $room = Room::where('code', $this->roomCode)->firstOrFail();
        $date = Carbon::parse($this->selectedDate);
        
        // Get bookings based on view mode
        $bookings = collect();
        
        if ($this->viewMode === 'day') {
            $bookings = RoomBooking::where('room_id', $room->id)
                ->where('booking_date', $date->toDateString())
                ->whereIn('status', ['approved', 'completed'])
                ->orderBy('start_time')
                ->get();
        } elseif ($this->viewMode === 'week') {
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek();
            
            $bookings = RoomBooking::where('room_id', $room->id)
                ->whereBetween('booking_date', [$startOfWeek, $endOfWeek])
                ->whereIn('status', ['approved', 'completed'])
                ->orderBy('booking_date')
                ->orderBy('start_time')
                ->get();
        } else { // month
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $bookings = RoomBooking::where('room_id', $room->id)
                ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
                ->whereIn('status', ['approved', 'completed'])
                ->orderBy('booking_date')
                ->orderBy('start_time')
                ->get();
        }

        return view('livewire.room-jadwal', [
            'room' => $room,
            'bookings' => $bookings,
            'selectedDate' => $date,
            'role' => Session::get('room_role'),
        ]);
    }
}

