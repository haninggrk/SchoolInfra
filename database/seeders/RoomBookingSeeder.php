<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\RoomBooking;
use App\Models\User;
use Carbon\Carbon;

class RoomBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all rooms
        $rooms = Room::where('is_active', true)->get();
        if ($rooms->isEmpty()) {
            $this->command->warn('No active rooms found. Please run RoomSeeder first.');
            return;
        }

        // Get all teachers
        $teachers = User::where('role', 'guru')->where('is_active', true)->get();
        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Please run TeacherSeeder first.');
            return;
        }

        // Define base date (31/10/2025 - today)
        $baseDate = Carbon::create(2025, 10, 31);
        
        // Subjects by teacher specialty
        $subjects = [
            'Matematika Kelas 10A',
            'Matematika Kelas 11B',
            'Fisika Kelas 10C',
            'Kimia Kelas 11A',
            'Biologi Kelas 10B',
            'Bahasa Indonesia Kelas 10A',
            'Bahasa Inggris Kelas 11A',
            'TIK Kelas 10B',
            'Praktek IPA Kelas 10C',
            'Presentasi Kelas 11B',
            'Diskusi Kelompok',
            'Remedial Matematika',
            'Ujian Praktikum IPA',
            'Workshop Multimedia',
        ];

        $descriptions = [
            'Pembelajaran reguler',
            'Praktek laboratorium',
            'Presentasi siswa',
            'Diskusi kelompok',
            'Ujian praktikum',
            'Remedial teaching',
            'Workshop',
            'Persiapan ujian',
        ];

        $bookings = [];
        
        // Generate bookings for 7 days (28/10 to 3/11)
        for ($dayOffset = -3; $dayOffset <= 3; $dayOffset++) {
            $date = $baseDate->copy()->addDays($dayOffset);
            $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 6 = Saturday
            
            // Skip weekends (optional - you can remove this if you want weekend bookings)
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                continue;
            }

            // Determine status based on date
            if ($dayOffset < 0) {
                // Past dates - mostly completed
                $statusOptions = ['completed', 'completed', 'completed', 'approved'];
            } elseif ($dayOffset == 0) {
                // Today - mix of approved, pending, and completed
                $statusOptions = ['approved', 'approved', 'approved', 'pending', 'completed'];
            } else {
                // Future dates - mostly approved, some pending
                $statusOptions = ['approved', 'approved', 'approved', 'approved', 'pending'];
            }

            // Generate 3-5 bookings per day
            $bookingsPerDay = rand(3, 5);
            $dailyBookings = []; // Track bookings for this day to avoid conflicts
            
            for ($i = 0; $i < $bookingsPerDay; $i++) {
                $room = $rooms->random();
                $teacher = $teachers->random();
                
                // Try to find a non-conflicting time slot (max 10 attempts)
                $attempts = 0;
                $conflict = true;
                
                while ($conflict && $attempts < 10) {
                    // Generate time slots (07:00 - 14:00)
                    $startHour = rand(7, 13);
                    $startMinute = [0, 15, 30, 45][rand(0, 3)];
                    $duration = [60, 90, 120][rand(0, 2)]; // 1 hour, 1.5 hours, or 2 hours
                    
                    $startTime = Carbon::createFromTime($startHour, $startMinute, 0);
                    $endTime = $startTime->copy()->addMinutes($duration);
                    
                    // Ensure end time doesn't exceed 15:00
                    if ($endTime->format('H:i') > '15:00') {
                        $endTime = Carbon::createFromTime(15, 0, 0);
                        $startTime = $endTime->copy()->subMinutes($duration);
                        if ($startTime->format('H:i') < '07:00') {
                            $startTime = Carbon::createFromTime(7, 0, 0);
                            $endTime = $startTime->copy()->addMinutes($duration);
                        }
                    }
                    
                    // Check for conflicts with existing bookings for this room on this day
                    $conflict = false;
                    foreach ($dailyBookings as $existingBooking) {
                        if ($existingBooking['room_id'] == $room->id) {
                            $existingStart = Carbon::parse($existingBooking['start_time']);
                            $existingEnd = Carbon::parse($existingBooking['end_time']);
                            
                            // Check if time slots overlap
                            if (($startTime->lt($existingEnd) && $endTime->gt($existingStart))) {
                                $conflict = true;
                                break;
                            }
                        }
                    }
                    
                    $attempts++;
                }
                
                // If still conflict after 10 attempts, skip this booking or use different room
                if ($conflict) {
                    continue;
                }

                $subject = $subjects[array_rand($subjects)];
                $description = $descriptions[array_rand($descriptions)];
                $status = $statusOptions[array_rand($statusOptions)];

                // Add some variation to descriptions
                if (str_contains($subject, 'IPA') || str_contains($subject, 'Praktek')) {
                    $description = 'Praktek laboratorium IPA';
                } elseif (str_contains($subject, 'TIK') || str_contains($subject, 'Multimedia')) {
                    $description = 'Praktek komputer dan multimedia';
                } elseif (str_contains($subject, 'Matematika')) {
                    $description = 'Pembelajaran matematika';
                }

                $bookingData = [
                    'room_id' => $room->id,
                    'user_id' => $teacher->id,
                    'subject' => $subject,
                    'description' => $description,
                    'booking_date' => $date->format('Y-m-d'),
                    'start_time' => $startTime->format('H:i:00'),
                    'end_time' => $endTime->format('H:i:00'),
                    'status' => $status,
                    'notes' => $dayOffset < 0 ? 'Booking selesai' : ($dayOffset == 0 ? 'Booking hari ini' : null),
                    'created_at' => now()->subDays(abs($dayOffset) + rand(1, 3)),
                    'updated_at' => now()->subDays(abs($dayOffset) + rand(1, 3)),
                ];
                
                $bookings[] = $bookingData;
                $dailyBookings[] = $bookingData; // Track for conflict checking
            }
        }

        // Insert bookings
        foreach ($bookings as $booking) {
            RoomBooking::create($booking);
        }

        $this->command->info('Room bookings seeded successfully!');
        $this->command->info('Total bookings created: ' . count($bookings));
        $this->command->info('Date range: 28/10/2025 - 3/11/2025');
    }
}
