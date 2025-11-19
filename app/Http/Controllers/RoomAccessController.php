<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class RoomAccessController extends Controller
{
    /**
     * Show role selection page for room access.
     */
    public function selectRole($roomCode)
    {
        $room = \Modules\Shared\Models\Room::where('code', $roomCode)
            ->where('is_active', true)
            ->firstOrFail();
        
        return view('room-access.select-role', compact('roomCode', 'room'));
    }

    /**
     * Set role in session.
     */
    public function setRole(Request $request, $roomCode)
    {
        $request->validate([
            'role' => 'required|in:siswa,guru',
        ]);

        Session::put('room_role', $request->role);
        Session::put('room_code', $roomCode);

        if ($request->role === 'guru') {
            return redirect()->route('room.verify-access', $roomCode);
        }

        return redirect()->route('room.dashboard', $roomCode);
    }

    /**
     * Show access code verification page for teachers.
     */
    public function verifyAccess($roomCode)
    {
        if (Session::get('room_role') !== 'guru') {
            return redirect()->route('room.select-role', $roomCode);
        }

        return view('room-access.verify-access', compact('roomCode'));
    }

    /**
     * Verify teacher access code.
     */
    public function verifyAccessCode(Request $request, $roomCode)
    {
        $request->validate([
            'access_code' => 'required|string',
        ]);

        $user = User::where('access_code', $request->access_code)
            ->where('role', 'guru')
            ->where('is_active', true)
            ->first();

        if (!$user) {
            return back()->withErrors(['access_code' => 'Kode akses tidak valid atau tidak aktif.']);
        }

        Session::put('guru_id', $user->id);
        Session::put('guru_name', $user->name);

        return redirect()->route('room.dashboard', $roomCode);
    }

    /**
     * Show room dashboard based on role.
     */
    public function dashboard($roomCode)
    {
        $role = Session::get('room_role');
        
        if (!$role) {
            return redirect()->route('room.select-role', $roomCode);
        }

        if ($role === 'guru' && !Session::has('guru_id')) {
            return redirect()->route('room.verify-access', $roomCode);
        }

        return view('room-access.dashboard', compact('roomCode', 'role'));
    }

    /**
     * Clear session and logout from room.
     */
        public function logout($roomCode)
    {
        Session::forget(['room_role', 'room_code', 'guru_id', 'guru_name']);
        return redirect()->route('room.select-role', $roomCode);
    }

    /**
     * Show pelaporan (damage report) page.
     */
    public function pelaporan($roomCode)
    {
        if (!Session::has('room_role')) {
            return redirect()->route('room.select-role', $roomCode);
        }

        $room = \Modules\Shared\Models\Room::where('code', $roomCode)->firstOrFail();
        $inventoryItems = \Modules\Shared\Models\InventoryItem::where('room_id', $room->id)
            ->orderBy('item_name')
            ->get();
        $students = \App\Models\Student::where('is_active', true)
            ->orderBy('name')
            ->get();
        $role = Session::get('room_role');

        return view('room-access.pelaporan', compact('roomCode', 'room', 'inventoryItems', 'students', 'role'));
    }

    /**
     * Store damage report.
     */
    public function storePelaporan(Request $request, $roomCode)
    {
        if (!Session::has('room_role')) {
            return redirect()->route('room.select-role', $roomCode);
        }

        $validated = $request->validate([
            'inventory_item_id' => 'nullable|exists:inventory_items,id',
            'description' => 'required|string',
            'urgency_level' => 'required|in:rendah,sedang,tinggi',
            'student_id' => 'required|exists:students,id',
            'reporter_contact' => 'nullable|string|max:255',
        ]);

        $room = \Modules\Shared\Models\Room::where('code', $roomCode)->firstOrFail();
        $student = \App\Models\Student::findOrFail($validated['student_id']);
        
        \Modules\Shared\Models\DamageReport::create([
            'room_id' => $room->id,
            'inventory_item_id' => $validated['inventory_item_id'] ?: null,
            'student_id' => $validated['student_id'],
            'description' => $validated['description'],
            'urgency_level' => $validated['urgency_level'],
            'reporter_name' => $student->name,
            'reporter_class' => $student->class,
            'reporter_contact' => $validated['reporter_contact'] ?? null,
            'status' => 'baru',
            'reported_at' => now(),
        ]);

        return redirect()->route('room.pantau-pelaporan', $roomCode)
            ->with('message', 'Laporan kerusakan berhasil dibuat!');
    }

    /**
     * Search students for autocomplete.
     */
    public function searchStudents(Request $request)
    {
        $query = $request->get('q', '');
        
        $students = \App\Models\Student::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('nis', 'like', '%' . $query . '%')
                  ->orWhere('class', 'like', '%' . $query . '%');
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'nis', 'class']);

        return response()->json($students);
    }
}


