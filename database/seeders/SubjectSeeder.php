<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            // 10 PBL Subjects
            ['title' => 'PBL: AI & Machine Learning', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: Web Development Capstone', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: IoT Smart Home', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: Cloud Infrastructure Deployment', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: Cybersecurity Simulation', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: Data Science Analytics', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: Blockchain dApp', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: Autonomous Robotics', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: Game Engine Architecture', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'PBL: Augmented Reality Systems', 'type' => 'pbl', 'credits' => 4, 'class_mode' => 'offline'],

            // Physics 2
            ['title' => 'Physics 2 (Hard core Physics, Digital Electronics)', 'type' => 'theory', 'credits' => 4, 'class_mode' => 'offline'],

            // 4 CSE Math Subjects
            ['title' => 'Discrete Mathematics', 'type' => 'theory', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'Linear Algebra & Matrices', 'type' => 'theory', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'Probability & Statistics for CSE', 'type' => 'theory', 'credits' => 4, 'class_mode' => 'offline'],
            ['title' => 'Calculus for Computer Science', 'type' => 'theory', 'credits' => 4, 'class_mode' => 'offline'],
        ];

        foreach ($subjects as $s) {
            Course::create(array_merge($s, [
                'department_id' => 1,
                'program' => 'Bachelors',
                'year' => 2,
                'semester' => 3,
                'class_section' => 'Class 01',
                'description' => 'Comprehensive study and practical application of ' . $s['title'],
                'approval_status' => 'approved' // Automatically approved for study
            ]));
        }
    }
}
