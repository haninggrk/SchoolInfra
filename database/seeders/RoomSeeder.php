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
        // Get random classroom images from Unsplash (1280x720 resolution)
        // Using Unsplash Source API with keyword "classroom"
        // All rooms will have at least 1-2 images
        $unsplashImages = [
            'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=1280&h=720&fit=crop&q=80', // Modern classroom
            'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1280&h=720&fit=crop&q=80', // Classroom with desks
            'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=1280&h=720&fit=crop&q=80', // Empty classroom
            'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1280&h=720&fit=crop&q=80', // Classroom board view
            'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=1280&h=720&fit=crop&q=80', // Lab/computer room
            'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1280&h=720&fit=crop&q=80', // Library/reading room
            'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=1280&h=720&fit=crop&q=80', // Modern school room
        ];

        $rooms = [
            [
                'name' => 'Lab Komputer 1',
                'code' => 'LAB-KOMP-1',
                'description' => 'Laboratorium komputer untuk pembelajaran TIK',
                'floor' => '2',
                'building' => 'Gedung A',
                'is_active' => true,
                'image_urls' => [
                    $unsplashImages[4], // Computer lab image
                    $unsplashImages[0], // Additional view
                ],
            ],
            [
                'name' => 'Lab IPA',
                'code' => 'LAB-IPA-1',
                'description' => 'Laboratorium untuk praktikum IPA',
                'floor' => '2',
                'building' => 'Gedung B',
                'is_active' => true,
                'image_urls' => [
                    $unsplashImages[4], // Lab image
                    $unsplashImages[2], // Empty classroom view
                ],
            ],
            [
                'name' => 'Ruang Multimedia',
                'code' => 'MULTIMEDIA-1',
                'description' => 'Ruang multimedia dan presentasi',
                'floor' => '3',
                'building' => 'Gedung A',
                'is_active' => true,
                'image_urls' => [
                    $unsplashImages[0], // Modern classroom
                    $unsplashImages[3], // Board view
                ],
            ],
            [
                'name' => 'Perpustakaan',
                'code' => 'PERPUS-1',
                'description' => 'Perpustakaan sekolah',
                'floor' => '1',
                'building' => 'Gedung C',
                'is_active' => true,
                'image_urls' => [
                    $unsplashImages[2], // Library/reading room
                    $unsplashImages[1], // Desk arrangement view
                ],
            ],
        ];

        foreach ($rooms as $room) {
            $roomModel = Room::updateOrCreate(
                ['code' => $room['code']],
                $room
            );
            
            // Ensure room has at least 1-2 images (in case it was created without images)
            if (empty($roomModel->image_urls)) {
                $roomModel->update([
                    'image_urls' => [
                        $unsplashImages[0],
                        $unsplashImages[rand(1, count($unsplashImages) - 1)],
                    ] // Always add 2 images
                ]);
            } elseif (count($roomModel->image_urls) === 1) {
                // If only 1 image, add a second one
                $currentImages = $roomModel->image_urls;
                $currentImages[] = $unsplashImages[rand(0, count($unsplashImages) - 1)];
                $roomModel->update(['image_urls' => array_unique($currentImages)]);
            }
        }
        
        // Also update any existing rooms that don't have images
        $allRooms = Room::all();
        
        foreach ($allRooms as $room) {
            $hasImages = !empty($room->image_urls) && is_array($room->image_urls) && count($room->image_urls) > 0;
            
            if (!$hasImages) {
                // Add 2 default images
                $room->update([
                    'image_urls' => [
                        $unsplashImages[0],
                        $unsplashImages[rand(1, count($unsplashImages) - 1)],
                    ]
                ]);
                $this->command->info("Added images to room: {$room->name} ({$room->code})");
            } elseif (count($room->image_urls) === 1) {
                // Add a second image if only 1 exists
                $currentImages = $room->image_urls;
                $currentImages[] = $unsplashImages[rand(0, count($unsplashImages) - 1)];
                $room->update(['image_urls' => array_unique($currentImages)]);
                $this->command->info("Added second image to room: {$room->name} ({$room->code})");
            }
        }

        $this->command->info('Rooms seeded successfully!');
        $this->command->info('Room Codes:');
        foreach ($rooms as $room) {
            $this->command->info("- {$room['name']}: {$room['code']}");
        }
    }
}




