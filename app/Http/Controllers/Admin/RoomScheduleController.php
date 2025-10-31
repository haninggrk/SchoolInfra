<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\RoomBooking;
use Carbon\Carbon;

class RoomScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Only admin can access
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Get selected date or default to today
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        $date = Carbon::parse($selectedDate);
        
        // Get all rooms
        $rooms = Room::where('is_active', true)
            ->orderBy('building')
            ->orderBy('floor')
            ->orderBy('name')
            ->get();

        // Get bookings for selected date with room and user info
        $bookings = RoomBooking::with(['room', 'user'])
            ->whereDate('booking_date', $selectedDate)
            ->orderBy('start_time')
            ->get()
            ->groupBy('room_id');

        // Prepare data for view
        $roomSchedules = [];
        foreach ($rooms as $room) {
            $roomSchedules[$room->id] = [
                'room' => $room,
                'bookings' => $bookings->get($room->id, collect())
            ];
        }

        // Previous and next dates
        $previousDate = $date->copy()->subDay()->format('Y-m-d');
        $nextDate = $date->copy()->addDay()->format('Y-m-d');
        $todayDate = now()->format('Y-m-d');

        return view('admin.room-schedule', compact(
            'roomSchedules',
            'selectedDate',
            'date',
            'previousDate',
            'nextDate',
            'todayDate'
        ));
    }

    public function show(Request $request, $roomId)
    {
        // Only admin can access
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $room = Room::findOrFail($roomId);

        // Get selected date or default to today
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        $date = Carbon::parse($selectedDate);

        // Get bookings for this room on selected date
        $bookings = RoomBooking::with(['room', 'user'])
            ->where('room_id', $roomId)
            ->whereDate('booking_date', $selectedDate)
            ->orderBy('start_time')
            ->get();

        // Previous and next dates
        $previousDate = $date->copy()->subDay()->format('Y-m-d');
        $nextDate = $date->copy()->addDay()->format('Y-m-d');
        $todayDate = now()->format('Y-m-d');

        return view('admin.room-schedule-detail', compact(
            'room',
            'bookings',
            'selectedDate',
            'date',
            'previousDate',
            'nextDate',
            'todayDate'
        ));
    }
}
