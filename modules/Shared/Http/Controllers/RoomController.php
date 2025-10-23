<?php

namespace Modules\Shared\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\InventoryItem;
use Modules\Shared\Models\DamageReport;

class RoomController extends Controller
{
    public function show($roomCode)
    {
        $room = Room::where('code', $roomCode)
            ->where('is_active', true)
            ->firstOrFail();

        // Get statistics
        $totalItems = $room->inventoryItems()->count();
        $digitalItems = $room->digitalInventoryItems()->count();
        $damageReports = $room->damageReports()->count();
        $activeReports = $room->damageReports()->where('status', '!=', 'selesai')->count();

        // Get recent activities
        $recentReports = $room->damageReports()
            ->with('inventoryItem')
            ->orderBy('reported_at', 'desc')
            ->limit(5)
            ->get();

        $recentItems = $room->inventoryItems()
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('room::room-page', compact(
            'room',
            'totalItems',
            'digitalItems',
            'damageReports',
            'activeReports',
            'recentReports',
            'recentItems'
        ));
    }

    public function generateQrCode($roomCode)
    {
        $room = Room::where('code', $roomCode)->firstOrFail();
        
        // Generate QR code logic here
        // For now, return the room URL
        $qrCodeUrl = route('room.show', $room->code);
        
        return response()->json([
            'qr_code_url' => $qrCodeUrl,
            'room_name' => $room->name,
            'room_code' => $room->code,
        ]);
    }
}
