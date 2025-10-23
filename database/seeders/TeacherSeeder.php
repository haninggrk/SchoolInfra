<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'name' => 'Pak Budi Santoso',
                'email' => 'budi@smartschool.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'access_code' => 'GURU001',
                'phone' => '081234567890',
                'is_active' => true,
            ],
            [
                'name' => 'Bu Siti Rahayu',
                'email' => 'siti@smartschool.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'access_code' => 'GURU002',
                'phone' => '081234567891',
                'is_active' => true,
            ],
            [
                'name' => 'Pak Ahmad Hidayat',
                'email' => 'ahmad@smartschool.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'access_code' => 'GURU003',
                'phone' => '081234567892',
                'is_active' => true,
            ],
            [
                'name' => 'Bu Dewi Lestari',
                'email' => 'dewi@smartschool.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'access_code' => 'GURU004',
                'phone' => '081234567893',
                'is_active' => true,
            ],
        ];

        foreach ($teachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                $teacher
            );
        }

        $this->command->info('Teachers seeded with access codes!');
        $this->command->info('Access Codes:');
        foreach ($teachers as $teacher) {
            $this->command->info("- {$teacher['name']}: {$teacher['access_code']}");
        }
    }
}


