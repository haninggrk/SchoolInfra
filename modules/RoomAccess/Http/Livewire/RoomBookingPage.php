<?php

namespace Modules\RoomAccess\Http\Livewire;

use Livewire\Component;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\RoomBooking;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class RoomBookingPage extends Component
{
    public $roomCode;
    public $selectedDate;
    public $viewMode = 'week';
    
    // Form fields
    public $showBookingForm = false;
    public $booking_date;
    public $subject = '';
    public $description = '';
    public $start_time = '';
    public $end_time = '';
    public $notes = '';

    protected $rules = [
        'booking_date' => 'required|date|after_or_equal:today',
        'subject' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'notes' => 'nullable|string',
    ];

    public function mount($roomCode)
    {
        $this->roomCode = $roomCode;
        $this->selectedDate = now()->toDateString();
        $this->booking_date = now()->toDateString();
        
        // Check if user has access (guru or siswa)
        $role = Session::get('room_role');
        if (!$role || ($role === 'guru' && !Session::has('guru_id'))) {
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
                $this->selectedDate = $date->copy()->subDay()->toDateString();
                break;
            case 'week':
                $this->selectedDate = $date->copy()->subWeek()->toDateString();
                break;
            case 'month':
                $this->selectedDate = $date->copy()->subMonth()->toDateString();
                break;
        }
    }

    public function nextPeriod()
    {
        $date = Carbon::parse($this->selectedDate);
        
        switch ($this->viewMode) {
            case 'day':
                $this->selectedDate = $date->copy()->addDay()->toDateString();
                break;
            case 'week':
                $this->selectedDate = $date->copy()->addWeek()->toDateString();
                break;
            case 'month':
                $this->selectedDate = $date->copy()->addMonth()->toDateString();
                break;
        }
    }

    public function today()
    {
        $this->selectedDate = now()->toDateString();
    }

    public function openBookingForm()
    {
        $this->showBookingForm = true;
        $this->resetForm();
    }

    public function closeBookingForm()
    {
        $this->showBookingForm = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->booking_date = now()->toDateString();
        $this->subject = '';
        $this->description = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->notes = '';
    }

    public function createBooking()
    {
        // Only guru can create bookings
        if (Session::get('room_role') !== 'guru' || !Session::has('guru_id')) {
            session()->flash('error', 'Hanya guru yang dapat membuat booking.');
            return;
        }

        $this->validate();

        $room = Room::where('code', $this->roomCode)->firstOrFail();
        
        // Create booking instance to check for conflicts
        $booking = new RoomBooking([
            'room_id' => $room->id,
            'user_id' => Session::get('guru_id'),
            'booking_date' => $this->booking_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'subject' => $this->subject,
            'description' => $this->description,
            'notes' => $this->notes,
            'status' => 'approved',
        ]);

        // Check for conflicts
        if ($booking->hasConflict()) {
            $this->addError('start_time', 'Waktu yang dipilih bentrok dengan booking lain.');
            return;
        }

        // Save booking
        $booking->save();

        session()->flash('message', 'Booking berhasil dibuat!');
        $this->closeBookingForm();
        $this->selectedDate = $this->booking_date;
    }

    public function cancelBooking($bookingId)
    {
        // Only guru can cancel bookings
        if (Session::get('room_role') !== 'guru' || !Session::has('guru_id')) {
            session()->flash('error', 'Hanya guru yang dapat membatalkan booking.');
            return;
        }

        $booking = RoomBooking::findOrFail($bookingId);
        
        // Only allow cancellation by the owner
        if ($booking->user_id !== Session::get('guru_id')) {
            session()->flash('error', 'Anda tidak memiliki akses untuk membatalkan booking ini.');
            return;
        }

        $booking->update(['status' => 'cancelled']);
        session()->flash('message', 'Booking berhasil dibatalkan.');
    }

    public function render()
    {
        $room = Room::where('code', $this->roomCode)->firstOrFail();
        $date = Carbon::parse($this->selectedDate);
        
        // Get all bookings (not just approved)
        $bookings = collect();
        
        if ($this->viewMode === 'day') {
            $bookings = RoomBooking::where('room_id', $room->id)
                ->where('booking_date', $date->toDateString())
                ->whereIn('status', ['approved', 'completed', 'pending'])
                ->orderBy('start_time')
                ->get();
        } elseif ($this->viewMode === 'week') {
            $startOfWeek = $date->copy()->startOfWeek()->toDateString();
            $endOfWeek = $date->copy()->endOfWeek()->toDateString();
            
            $bookings = RoomBooking::where('room_id', $room->id)
                ->whereBetween('booking_date', [$startOfWeek, $endOfWeek])
                ->whereIn('status', ['approved', 'completed', 'pending'])
                ->orderBy('booking_date')
                ->orderBy('start_time')
                ->get();
        } else { // month
            $startOfMonth = $date->copy()->startOfMonth()->toDateString();
            $endOfMonth = $date->copy()->endOfMonth()->toDateString();
            
            $bookings = RoomBooking::where('room_id', $room->id)
                ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
                ->whereIn('status', ['approved', 'completed', 'pending'])
                ->orderBy('booking_date')
                ->orderBy('start_time')
                ->get();
        }

        return view('livewire.room-booking', [
            'room' => $room,
            'bookings' => $bookings,
            'selectedDate' => $date,
            'role' => Session::get('room_role'),
            'guruId' => Session::get('guru_id'),
        ]);
    }
}

