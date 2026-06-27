<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\Department;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure SCSET exists as the primary department
        $scset = Department::updateOrCreate(
            ['code' => 'SCSET'],
            [
                'name' => 'School Of Computer Science Engineering and Technology'
            ]
        );

        // Keep other departments if they are referenced elsewhere, but SCSET is our primary focus
        $cse = Department::updateOrCreate(['code' => 'CSE'], ['name' => 'Computer Science & Engineering']);
        $it = Department::updateOrCreate(['code' => 'IT'], ['name' => 'Information Technology']);
        $me = Department::updateOrCreate(['code' => 'ME'], ['name' => 'Mechanical Engineering']);
        $ce = Department::updateOrCreate(['code' => 'CE'], ['name' => 'Civil Engineering']);
        $ee = Department::updateOrCreate(['code' => 'EE'], ['name' => 'Electrical Engineering']);
        $mba = Department::updateOrCreate(['code' => 'MBA'], ['name' => 'MBA']);

        // Disable FK checks and delete staff
        Schema::disableForeignKeyConstraints();
        Staff::query()->delete();
        Schema::enableForeignKeyConstraints();

        // 2. Define the 26 Faculty Members
        $staffData = [
            // --- Diploma Faculty ---
            ['name' => 'Riya Modi', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Diploma Faculty']],
            ['name' => 'Prachi Rajput(HOD Diploma)', 'role' => 'hod', 'email_role' => 'hod', 'positions' => ['HOD Diploma']],
            ['name' => 'Abhishek Sir', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Diploma Faculty']],
            ['name' => 'Neel Patel', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Diploma Faculty']],
            ['name' => 'Yogita Parmar', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Diploma Faculty']],
            ['name' => 'Moin Khokhar', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Diploma Faculty']],
            ['name' => 'Pooja Bhaliya', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Diploma Faculty']],
            ['name' => 'Prashant Sir(BCA and Diploma Hybrid)', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['BCA and Diploma Hybrid']],
            ['name' => 'Ishan Mistry', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Diploma Faculty']],
            ['name' => 'Smriti Mund', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Diploma Faculty']],

            // --- B.Tech And M.Tech Faculty ---
            ['name' => 'Dr. Kalpana Matre(Sinior Faculty)(Co-Dean)(Quad PHD)', 'role' => 'dean', 'email_role' => 'dean', 'positions' => ['Senior Faculty', 'Co-Dean', 'Quad PHD']],
            ['name' => 'Dr. Asutosh Abhangi(Sinoir Faculty)(Co-Dean)(Triple PHD)', 'role' => 'dean', 'email_role' => 'dean', 'positions' => ['Senior Faculty', 'Co-Dean', 'Triple PHD']],
            ['name' => 'Anupam Mund', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['B.Tech & M.Tech Faculty']],
            ['name' => 'Ankita Kumari', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['B.Tech & M.Tech Faculty']],
            ['name' => 'Meet Patel', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['B.Tech & M.Tech Faculty']],
            ['name' => 'Alka Ravat', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['B.Tech & M.Tech Faculty']],

            // --- Higher Position Faculty ---
            ['name' => 'Prof. Sunil Panchal(HOD BCA)', 'role' => 'hod', 'email_role' => 'hod', 'positions' => ['HOD BCA']],
            ['name' => 'Prof. (Dill). DR. Raju Nakum(SSIP+ UGC HOD+ IPDC HEAD+RESEARCHER/PHD Student HOD)', 'role' => 'hod', 'email_role' => 'hod', 'positions' => ['SSIP Head', 'UGC HOD', 'IPDC Head', 'PHD Student HOD', 'Researcher']],
            ['name' => 'Prof. Shivangi Meteda(HOD Of All AI CS CSN IT)', 'role' => 'hod', 'email_role' => 'hod', 'positions' => ['HOD of All AI CS CSN IT']],
            ['name' => 'Prof. Gaurav Kulkarni(Associate Dean)', 'role' => 'dean', 'email_role' => 'dean', 'positions' => ['Associate Dean']],
            ['name' => 'Dr.Prof. Dr. Pradeep Laxkar(Main Dean(Already Exist)) (Dual PHD)', 'role' => 'dean', 'email_role' => 'dean', 'positions' => ['Main Dean', 'Dual PHD']],
            ['name' => 'Prof. Dr. Vedvas Dwivedi (Provost) (Quad PHD)', 'role' => 'dean', 'email_role' => 'provost', 'positions' => ['Provost', 'Quad PHD']],
            ['name' => 'Prof. Dr. Amit Kumar Sen (President)', 'role' => 'dean', 'email_role' => 'dean', 'positions' => ['President'], 'custom_email' => 'president@itmbu.ac.in'],
            ['name' => 'Prof. Dr. Rajesh Kumar (Vice President)', 'role' => 'dean', 'email_role' => 'dean', 'positions' => ['Vice President'], 'custom_email' => 'vp@itmbu.ac.in'],
            ['name' => 'Dr. Ramesh Mehta (Registrar)', 'role' => 'dean', 'email_role' => 'dean', 'positions' => ['Registrar'], 'custom_email' => 'registrar@itmbu.ac.in'],
            ['name' => 'Prof. Drashti Patel(Office assistant)', 'role' => 'office-assistant', 'email_role' => 'office-assistant', 'positions' => ['Office Assistant']],
            ['name' => 'Prof. Maddona Lamin(Placememet and Devlopment and Tranner Head+Dean)', 'role' => 'dean', 'email_role' => 'dean', 'positions' => ['Placement & Development Head', 'Trainer Head', 'Dean']],
            ['name' => 'Prof. Dixa Durgapal(Exam Coordinaor(Deaprtment+Faculty))', 'role' => 'coordinator', 'email_role' => 'coordinator', 'positions' => ['Exam Coordinator (Department+Faculty)']],
            ['name' => 'Prof. Mohan Vyas(Head of Exam Controller)', 'role' => 'admin', 'email_role' => 'admin', 'positions' => ['Head of Exam Controller']],
            ['name' => 'BHAVIKKUMAR PATEL', 'role' => 'admin', 'email_role' => 'admin', 'positions' => ['Administrator'], 'custom_email' => 'admin.bhavik@baps.ac.in', 'phone' => '9316945893'],
            
            // --- Mathematics Faculty (4 Injected) ---
            ['name' => 'Dr. Amit Sharma(Mathematics Faculty)', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Mathematics Faculty', 'Senior Lecturer']],
            ['name' => 'Dr. Neha Gupta(Mathematics Faculty)', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Mathematics Faculty', 'Assistant Professor']],
            ['name' => 'Prof. Rahul Joshi(Mathematics Faculty)', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Mathematics Faculty', 'Lecturer']],
            ['name' => 'Prof. Sneha Vyas(Mathematics Faculty)', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Mathematics Faculty', 'Lecturer']],
            
            // --- Physics Faculty (2 Injected) ---
            ['name' => 'Dr. Suresh Trivedi(Physics Faculty)', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Physics Faculty', 'Senior Professor']],
            ['name' => 'Prof. Rakesh Dave(Physics Faculty)', 'role' => 'faculty', 'email_role' => 'faculty', 'positions' => ['Physics Faculty', 'Assistant Professor']]
        ];

        // 3. Populate database
        foreach ($staffData as $index => $data) {
            $fullName = $data['name'];
            
            // Reusable parsing logic to extract first and last name
            $cleanName = preg_replace('/\s*\([^)]*\)/', '', $fullName);
            $cleanName = str_replace(['.', ','], ' ', $cleanName);
            $cleanName = preg_replace('/\s+/', ' ', trim($cleanName));
            $words = explode(' ', $cleanName);
            
            $titles = ['dr', 'prof', 'hod', 'dean', 'provost', 'associate', 'co-dean', 'senior', 'assistant', 'dill'];
            
            $filteredWords = [];
            foreach ($words as $word) {
                $cleanWord = strtolower(trim($word));
                if (in_array($cleanWord, $titles) || empty($cleanWord)) {
                    continue;
                }
                $filteredWords[] = $word;
            }
            
            $first = $filteredWords[0] ?? 'Faculty';
            $last = $filteredWords[1] ?? 'ITM';
            
            $firstClean = preg_replace('/[^a-zA-Z]/', '', $first);
            $lastClean = preg_replace('/[^a-zA-Z]/', '', $last);
            
            // Password: FirstName(All Capital)@123
            $plainPassword = strtoupper($firstClean) . '@123';
            $hashedPassword = Hash::make($plainPassword);
            
            // Email: Firstname.Lastname.Role@itmbu.ac.in (or custom email)
            $email = isset($data['custom_email']) ? $data['custom_email'] : (strtolower($firstClean) . '.' . strtolower($lastClean) . '.' . strtolower($data['email_role']) . '@itmbu.ac.in');
            
            // Unique code: ITM001 to ITM026
            $uniqueCode = 'ITM' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            
            // Access levels
            $accessLevel = 100;
            if (in_array(strtolower($data['role']), ['dean', 'admin'])) {
                $accessLevel = 200;
            } elseif (strtolower($data['role']) === 'hod') {
                $accessLevel = 175;
            } elseif (in_array(strtolower($data['role']), ['cr', 'coordinator'])) {
                $accessLevel = 125;
            }

            Staff::create([
                'name' => $fullName,
                'role' => $data['role'],
                'department_id' => $scset->id, // All belong to SCSET (School of Computer Science Engineering and Technology)
                'unique_code' => $uniqueCode,
                'email' => $email,
                'password' => $hashedPassword,
                'positions' => $data['positions'],
                'access_level' => $accessLevel,
                'phone' => $data['phone'] ?? null
            ]);
        }

        // 4. Set the 5-level programs/branches for SCSET with mapped HOD IDs
        $prachiId = Staff::where('name', 'like', '%Prachi%')->first()?->id;
        $shivangiId = Staff::where('name', 'like', '%Shivangi%')->first()?->id;
        $sunilId = Staff::where('name', 'like', '%Sunil%')->first()?->id;
        $rajuId = Staff::where('name', 'like', '%Raju%')->first()?->id;

        $scset->branches = [
            'diploma' => [
                [
                    'program' => 'Diploma',
                    'heads' => $prachiId ? [['staff_id' => $prachiId, 'type' => 'perm']] : [],
                    'branches' => ['Computer Engineering (CSE)']
                ]
            ],
            'bachelors' => [
                [
                    'program' => 'B.Tech',
                    'heads' => array_filter([
                        $shivangiId ? ['staff_id' => $shivangiId, 'type' => 'perm'] : null,
                        $rajuId ? ['staff_id' => $rajuId, 'type' => 'perm'] : null,
                        $sunilId ? ['staff_id' => $sunilId, 'type' => 'temp'] : null
                    ]),
                    'branches' => ['Computer Science & Engineering (CSE)', 'Computer Systems & Networking (CSN)', 'Information Technology (IT)']
                ],
                [
                    'program' => 'BCA',
                    'heads' => $sunilId ? [['staff_id' => $sunilId, 'type' => 'perm']] : [],
                    'branches' => ['General']
                ],
                [
                    'program' => 'B.Sc IT',
                    'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                    'branches' => ['General']
                ]
            ],
            'hons_bachelors' => [
                [
                    'program' => 'B.Tech (Hons)',
                    'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                    'branches' => ['Artificial Intelligence & Machine Learning (AI&ML)', 'Cyber Security (CS)']
                ],
                [
                    'program' => 'BCA (Hons)',
                    'heads' => $sunilId ? [['staff_id' => $sunilId, 'type' => 'perm']] : [],
                    'branches' => ['Cloud Computing & DevOps', 'Data Science']
                ]
            ],
            'masters' => [
                [
                    'program' => 'M.Tech',
                    'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                    'branches' => ['Computer Science & Engineering']
                ],
                [
                    'program' => 'MCA',
                    'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                    'branches' => ['General', 'Data Analytics']
                ],
                [
                    'program' => 'M.Sc CS',
                    'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                    'branches' => ['General']
                ]
            ],
            'phd' => [
                [
                    'program' => 'PhD',
                    'heads' => $rajuId ? [['staff_id' => $rajuId, 'type' => 'perm']] : [],
                    'branches' => ['Computer Science', 'Artificial Intelligence & Machine Learning']
                ]
            ]
        ];
        $scset->save();
    }
}
