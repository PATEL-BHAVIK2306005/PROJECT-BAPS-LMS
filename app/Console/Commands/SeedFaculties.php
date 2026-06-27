<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Staff;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class SeedFaculties extends Command
{
    protected $signature = 'app:seed-faculties';
    protected $description = 'Seed the custom faculty list under School of Computer Science Engineering and Technology';

    public function handle()
    {
        // 1. Robustly resolve or create the consolidated department
        $mainDept = Department::where('name', 'School Of Computer Science Enginnering and Technology')->first();
        
        if (!$mainDept) {
            $mainDept = Department::where('code', 'SCSET')->first();
            if ($mainDept) {
                $mainDept->name = 'School Of Computer Science Enginnering and Technology';
                $mainDept->save();
            } else {
                // If the old CSE department exists, let's rename it to SCSET to maintain integrity
                $cseDept = Department::where('code', 'CSE')
                    ->orWhere('name', 'Computer Science & Engineering')
                    ->first();
                if ($cseDept) {
                    $cseDept->name = 'School Of Computer Science Enginnering and Technology';
                    $cseDept->code = 'SCSET';
                    $cseDept->save();
                    $mainDept = $cseDept;
                } else {
                    $mainDept = Department::create([
                        'code' => 'SCSET',
                        'name' => 'School Of Computer Science Enginnering and Technology'
                    ]);
                }
            }
        }

        // Delete temporary/old codes if they exist
        Department::whereIn('code', ['DIP', 'BCA', 'BTECH_MTECH'])->delete();

        // 2. Define faculty members list
        $faculties = [
            [
                'name' => 'Prof. Riya Modi',
                'email' => 'riya.modi.faculty@itmbu.ac.in',
                'password' => 'RIYA',
                'role' => 'faculty',
                'positions' => ['faculty', 'lecturer'],
                'unique_code' => 'RIYAM2026'
            ],
            [
                'name' => 'Prof. Prachi Rajput',
                'email' => 'prachi.rajput.hod@itmbu.ac.in',
                'password' => 'PRACHI',
                'role' => 'hod',
                'positions' => ['hod', 'faculty', 'lecturer'],
                'unique_code' => 'PRACHIR2026'
            ],
            [
                'name' => 'Prof. Pooja Bhaliya',
                'email' => 'pooja.bhaliya.faculty@itmbu.ac.in',
                'password' => 'POOJA',
                'role' => 'faculty',
                'positions' => ['faculty', 'lecturer'],
                'unique_code' => 'POOJAB2026'
            ],
            [
                'name' => 'Prof. Moin Khokhar',
                'email' => 'moin.khokhar.faculty@itmbu.ac.in',
                'password' => 'MOIN',
                'role' => 'faculty',
                'positions' => ['faculty', 'lecturer'],
                'unique_code' => 'MOINK2026'
            ],
            [
                'name' => 'Prof. Abhishek Dave',
                'email' => 'abhishek.dave.faculty@itmbu.ac.in',
                'password' => 'ABHISHEK',
                'role' => 'faculty',
                'positions' => ['faculty', 'lecturer'],
                'unique_code' => 'ABHISHEKD2026'
            ],
            [
                'name' => 'Prof. Neel Patel',
                'email' => 'neel.patel.faculty@itmbu.ac.in',
                'password' => 'NEEL',
                'role' => 'faculty',
                'positions' => ['faculty', 'lecturer'],
                'unique_code' => 'NEELP2026'
            ],
            [
                'name' => 'Prof. Ashutosh Abhangi',
                'email' => 'ashutosh.abhangi.faculty@itmbu.ac.in',
                'password' => 'ASHUTOSH',
                'role' => 'faculty',
                'positions' => ['faculty'],
                'unique_code' => 'ASHUTOSHA2026'
            ],
            [
                'name' => 'Prof. Kalpna Matre',
                'email' => 'kalpna.matre.faculty@itmbu.ac.in',
                'password' => 'KALPNA',
                'role' => 'faculty',
                'positions' => ['faculty'],
                'unique_code' => 'KALPNAM2026'
            ],
            [
                'name' => 'Prof. Gaurav Kulkarni',
                'email' => 'gaurav.kulkarni.hod@itmbu.ac.in',
                'password' => 'GAURAV',
                'role' => 'hod',
                'positions' => ['hod', 'faculty'],
                'unique_code' => 'GAURAVK2026'
            ],
            [
                'name' => 'Prof. Raju Nakum',
                'email' => 'raju.nakum.coordinator@itmbu.ac.in',
                'password' => 'RAJU',
                'role' => 'faculty',
                'positions' => ['faculty', 'coordinator'],
                'unique_code' => 'RAJUN2026'
            ],
            [
                'name' => 'Prof. Sunil Panchal',
                'email' => 'sunil.panchal.hod@itmbu.ac.in',
                'password' => 'SUNIL',
                'role' => 'hod',
                'positions' => ['hod', 'faculty'],
                'unique_code' => 'SUNILP2026'
            ],
            [
                'name' => 'Prof. Anupam Mund',
                'email' => 'anupam.mund.faculty@itmbu.ac.in',
                'password' => 'ANUPAM',
                'role' => 'faculty',
                'positions' => ['faculty'],
                'unique_code' => 'ANUPAMM2026'
            ],
            [
                'name' => 'Prof. Ankita Kumari',
                'email' => 'ankita.kumari.faculty@itmbu.ac.in',
                'password' => 'ANKITA',
                'role' => 'faculty',
                'positions' => ['faculty'],
                'unique_code' => 'ANKITAK2026'
            ],
            [
                'name' => 'Prof. Gautam Prasad',
                'email' => 'gautam.prasad.faculty@itmbu.ac.in',
                'password' => 'GAUTAM',
                'role' => 'faculty',
                'positions' => ['faculty'],
                'unique_code' => 'GAUTAMP2026'
            ],
            [
                'name' => 'Prof. Akshar Mulle',
                'email' => 'akshar.mulle.faculty@itmbu.ac.in',
                'password' => 'AKSHAR',
                'role' => 'faculty',
                'positions' => ['faculty'],
                'unique_code' => 'AKSHARM2026'
            ],
            [
                'name' => 'Prof. Amit Sir',
                'email' => 'amit.sir.faculty@itmbu.ac.in',
                'password' => 'AMIT',
                'role' => 'faculty',
                'positions' => ['faculty'],
                'unique_code' => 'AMITS2026'
            ],
            [
                'name' => 'Prof. Alka Ravat',
                'email' => 'alka.ravat.faculty@itmbu.ac.in',
                'password' => 'ALKA',
                'role' => 'faculty',
                'positions' => ['faculty'],
                'unique_code' => 'ALKAR2026'
            ],
            [
                'name' => 'Prof. Madonna Lamin',
                'email' => 'madonna.lamin.dean@itmbu.ac.in',
                'password' => 'MADONNA',
                'role' => 'dean',
                'positions' => ['dean', 'faculty'],
                'unique_code' => 'MADONNAL2026'
            ]
        ];

        foreach ($faculties as $f) {
            $accessLevel = 100;
            if (in_array(strtolower($f['role']), ['dean', 'admin'])) {
                $accessLevel = 200;
            } elseif (strtolower($f['role']) === 'hod') {
                $accessLevel = 175;
            }

            Staff::updateOrCreate(
                ['email' => $f['email']],
                [
                    'name' => $f['name'],
                    'role' => $f['role'],
                    'positions' => $f['positions'],
                    'department_id' => $mainDept->id,
                    'unique_code' => $f['unique_code'],
                    'password' => Hash::make($f['password']),
                    'access_level' => $accessLevel
                ]
            );

            $this->info("Enrolled under SCSET: " . $f['name']);
        }

        $this->info("All faculties consolidated successfully!");
    }
}
