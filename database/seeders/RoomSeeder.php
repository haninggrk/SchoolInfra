<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Shared\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Lab Komputer 1',
                'code' => 'LAB-KOMP-1',
                'description' => 'Laboratorium komputer untuk pembelajaran TIK',
                'floor' => '2',
                'building' => 'Gedung A',
                'is_active' => true,
            ],
            [
                'name' => 'Lab IPA',
                'code' => 'LAB-IPA-1',
                'description' => 'Laboratorium untuk praktikum IPA',
                'floor' => '2',
                'building' => 'Gedung B',
                'is_active' => true,
            ],
            [
                'name' => 'Ruang Multimedia',
                'code' => 'MULTIMEDIA-1',
                'description' => 'Ruang multimedia dan presentasi',
                'floor' => '3',
                'building' => 'Gedung A',
                'is_active' => true,
            ],
            [
                'name' => 'Perpustakaan',
                'code' => 'PERPUS-1',
                'description' => 'Perpustakaan sekolah',
                'floor' => '1',
                'building' => 'Gedung C',
                'is_active' => true,
            ],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(
                ['code' => $room['code']],
                $room
            );
        }

        $this->command->info('Rooms seeded successfully!');
        $this->command->info('Room Codes:');
        foreach ($rooms as $room) {
            $this->command->info("- {$room['name']}: {$room['code']}");
        }
    }
}



