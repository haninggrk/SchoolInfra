<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\InventoryItem;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create guru user
        User::create([
            'name' => 'Guru',
            'email' => 'guru@school.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // Run seeders
        $this->call([
            RoomSeeder::class,
            TeacherSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
