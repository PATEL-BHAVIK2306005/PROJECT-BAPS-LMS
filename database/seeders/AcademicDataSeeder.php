<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AcademicDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing data to prevent duplicate/stale records
        Schema::disableForeignKeyConstraints();
        Course::query()->delete();
        Subject::query()->delete();
        Schema::enableForeignKeyConstraints();

        // 2. Ensure Department exists
        $dept = Department::updateOrCreate(
            ['name' => 'School Of Computer Science Engineering and Technology'],
            ['code' => 'SCSET']
        );

        // 3. Define courses for Diploma, Bachelors, and Masters
        $coursesData = [
            // --- DIPLOMA COURSES (IT / CE Branches) ---
            [
                'title' => 'Diploma IT - Basic Programming in C',
                'description' => 'Fundamental programming concepts using C language for Diploma IT students.',
                'program' => 'Diploma',
                'year' => 1,
                'semester' => 1,
                'class_section' => 'IT'
            ],
            [
                'title' => 'Diploma CE - Introduction to Digital Logic',
                'description' => 'Basic principles of digital logic design for Diploma CE students.',
                'program' => 'Diploma',
                'year' => 1,
                'semester' => 1,
                'class_section' => 'CE'
            ],
            [
                'title' => 'Diploma IT - Web Designing Basics',
                'description' => 'Introduction to HTML, CSS, and basic scripting for Diploma IT students.',
                'program' => 'Diploma',
                'year' => 1,
                'semester' => 2,
                'class_section' => 'IT'
            ],

            // --- BACHELORS COURSES (Class 01 / Class 02 Sections) ---
            [
                'title' => 'Bachelors - Data Structures and Algorithms',
                'description' => 'Advanced study of arrays, linked lists, trees, and algorithmic design.',
                'program' => 'Bachelors',
                'year' => 2,
                'semester' => 3,
                'class_section' => 'Class 01'
            ],
            [
                'title' => 'Bachelors - Database Management Systems',
                'description' => 'Relational database model, SQL queries, normalization and indexing.',
                'program' => 'Bachelors',
                'year' => 2,
                'semester' => 3,
                'class_section' => 'Class 02'
            ],
            [
                'title' => 'Bachelors - Operating Systems Concepts',
                'description' => 'Processes, CPU scheduling, memory management, and file systems.',
                'program' => 'Bachelors',
                'year' => 2,
                'semester' => 4,
                'class_section' => 'Class 01'
            ],

            // --- MASTERS COURSES (Class 01 Section) ---
            [
                'title' => 'Masters - Advanced Machine Learning',
                'description' => 'Deep neural networks, reinforcement learning, and advanced model tuning.',
                'program' => 'Masters',
                'year' => 1,
                'semester' => 1,
                'class_section' => 'Class 01'
            ],
            [
                'title' => 'Masters - Distributed Systems Architecture',
                'description' => 'Designing large-scale scalable systems, consensus algorithms, and RPC.',
                'program' => 'Masters',
                'year' => 1,
                'semester' => 1,
                'class_section' => 'Class 02'
            ]
        ];

        foreach ($coursesData as $cData) {
            $course = Course::create(array_merge($cData, [
                'department_id' => $dept->id,
                'duration' => '4 Months',
                'level' => 'Undergraduate',
                'credits' => 4,
                'approval_status' => 'approved' // Set to approved so they are immediately accessible
            ]));

            // Seed subjects/modules for each course
            for ($j = 1; $j <= 4; $j++) {
                Subject::create([
                    'course_id' => $course->id,
                    'name' => "{$course->title} - Module $j",
                    'code' => "SUB-" . strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $course->title), 0, 3)) . "-$j",
                    'type' => 'theory'
                ]);
            }
        }

        // 4. Add 30 Students with specific academic profiles
        $studentNames = [
            // Diploma IT Students (10)
            'Akshar Patel', 'Shanti Patel', 'Vinu Patel', 'Yogi Patel', 'Dungar Patel',
            'Ghansyam panday', 'Aarav Shah', 'Vivaan Mehta', 'Ishaan Joshi', 'Kabir Trivedi',
            // Bachelors Class 01 Students (10)
            'Dhruv Dave', 'Reyansh Sharma', 'Ayaan Vyas', 'Advait Bhatt', 'Shaurya Desai',
            'Arnav Kothari', 'Vihaan Gandhi', 'Atharv Parikh', 'Krishna Amin', 'Vedant Shah',
            // Masters Class 01 Students (10)
            'Aarav Patel', 'Vihaan Shah', 'Aarush Mehta', 'Jignesh Mevani', 'Kabir Patel',
            'Vivaan Sharma', 'Dev Shah', 'Reyansh Trivedi', 'Aarav Dave', 'Hardik Patel'
        ];

        foreach ($studentNames as $index => $name) {
            $emailName = strtolower(str_replace(' ', '.', $name));
            $email = "{$emailName}@itmbu.ac.in";

            // Determine academic profile mapping
            if ($index < 10) {
                // Diploma IT Sem 1
                $program = 'Diploma';
                $year = 1;
                $semester = 1;
                $class_section = 'IT';
            } elseif ($index < 20) {
                // Bachelors Class 01 Sem 3
                $program = 'Bachelors';
                $year = 2;
                $semester = 3;
                $class_section = 'Class 01';
            } else {
                // Masters Class 01 Sem 1
                $program = 'Masters';
                $year = 1;
                $semester = 1;
                $class_section = 'Class 01';
            }
            
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'enrollment_no' => "2026" . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'department_id' => $dept->id,
                    'status' => 'approved',
                    'level' => 1,
                    'xp' => 0,
                    'program' => $program,
                    'year' => $year,
                    'semester' => $semester,
                    'class_section' => $class_section
                ]
            );
        }
    }
}
