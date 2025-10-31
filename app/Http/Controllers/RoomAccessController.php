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
}


