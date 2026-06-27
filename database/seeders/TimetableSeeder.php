<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timetable;
use App\Models\TimetableEntry;

class TimetableSeeder extends Seeder
{
    public function run()
    {
        $classes = ['Class 01', 'Class 02', 'Class 03', 'Class 04'];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        
        $mainSubjects = [
            'Data Structures and Algorithms',
            'Database Management Systems',
            'Digital Logic Design',
            'Discrete Mathematics',
            'Computer Organization and Architecture'
        ];
        
        $labsAndPbls = [
            'PBL: Web Development Capstone', 
            'PBL: AI & Machine Learning',
            'Data Structures Lab',
            'DBMS Lab'
        ];

        foreach ($classes as $classSection) {
            $timetable = Timetable::create([
                'title' => 'Sem 03 - ' . $classSection,
                'department_id' => 1,
                'semester' => '3',
                'file_path' => null,
                'uploaded_by' => \App\Models\User::first()->id ?? 1,
            ]);

            // 30 slots total (5 days * 6 slots)
            $pool = [];
            // 5 Main Subjects * 4 lectures = 20 slots
            foreach ($mainSubjects as $subj) {
                for($i=0; $i<4; $i++) {
                    $pool[] = ['subject' => $subj, 'duration' => 1];
                }
            }
            // 4 Labs/PBLs = 4 slots (duration 2)
            foreach ($labsAndPbls as $lab) {
                $pool[] = ['subject' => $lab, 'duration' => 2];
            }
            // 2 Library Sessions
            $pool[] = ['subject' => 'Library Session', 'duration' => 1];
            $pool[] = ['subject' => 'Library Session', 'duration' => 1];
            
            // Fill remaining 4 slots
            $pool[] = ['subject' => 'Mentoring / Soft Skills', 'duration' => 1];
            $pool[] = ['subject' => 'Placement Training', 'duration' => 1];
            $pool[] = ['subject' => 'Sports & Activity', 'duration' => 1];
            $pool[] = ['subject' => 'Technical Seminar', 'duration' => 1];

            // Shuffle the pool
            shuffle($pool);
            $poolIndex = 0;

            foreach ($days as $day) {
                for ($slot = 1; $slot <= 6; $slot++) {
                    $item = $pool[$poolIndex] ?? ['subject' => 'Free Period', 'duration' => 1];
                    $poolIndex++;

                    TimetableEntry::create([
                        'timetable_id' => $timetable->id,
                        'day_of_week' => $day,
                        'slot' => $slot,
                        'duration' => $item['duration'],
                        'subject' => $item['subject'],
                        'faculty' => 'TBD',
                        'room' => 'Room 30' . rand(1, 9),
                    ]);
                }
            }
        }
    }
}
