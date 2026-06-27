<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\TimetableEntry;
use App\Models\Department;
use App\Models\Staff;
use App\Models\Course;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::latest()->get();
        $departments = Department::all();
        return view('admin.timetables', compact('timetables', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'department_id' => 'nullable|exists:departments,id',
            'semester' => 'nullable|string',
        ]);

        $filePath = $request->file('file')->store('timetables', 'public');

        $uploadedBy = session('user_id') ?? 1;
        if (auth()->check()) {
            $uploadedBy = auth()->id();
        }

        Timetable::create([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'semester' => $request->semester,
            'file_path' => $filePath,
            'uploaded_by' => $uploadedBy
        ]);

        return back()->with('success', 'Timetable uploaded successfully.');
    }

    public function buildManual()
    {
        $departments = Department::all();
        $faculties = Staff::orderBy('name', 'asc')->get();
        $courses = Course::orderBy('title', 'asc')->get();
        return view('admin.timetables_builder', compact('departments', 'faculties', 'courses'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'semester' => 'nullable|string',
            'slots' => 'required|array',
        ]);

        $uploadedBy = session('user_id') ?? 1;
        if (auth()->check()) {
            $uploadedBy = auth()->id();
        }

        $timetable = Timetable::create([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'semester' => $request->semester,
            'file_path' => null, // No file
            'uploaded_by' => $uploadedBy
        ]);

        foreach ($request->slots as $day => $daySlots) {
            foreach ($daySlots as $slotNumber => $data) {
                if (!empty($data['subject'])) {
                    TimetableEntry::create([
                        'timetable_id' => $timetable->id,
                        'day_of_week' => $day,
                        'slot' => $slotNumber,
                        'duration' => $data['duration'] ?? 1,
                        'subject' => $data['subject'],
                        'faculty' => $data['faculty'] ?? null,
                        'room' => $data['room'] ?? null,
                        'is_cancelled' => !empty($data['is_cancelled']),
                        'cancel_reason' => $data['cancel_reason'] ?? null,
                        'faculty_cancel_reason' => $data['faculty_cancel_reason'] ?? $data['cancel_reason'] ?? null,
                        'student_cancel_reason' => $data['student_cancel_reason'] ?? $data['cancel_reason'] ?? null,
                        'is_extra' => !empty($data['is_extra']),
                        'extra_reason' => $data['extra_reason'] ?? null,
                    ]);
                }
            }
        }

        return redirect('/admin/timetables')->with('success', 'Interactive timetable created successfully.');
    }

    public function generateAi(Request $request)
    {
        $departmentId = $request->input('department_id');
        $semester = $request->input('semester');

        // Fetch all staff members
        $allStaff = Staff::all();

        // Group faculties by expertise
        $mathFaculties = [];
        $physicsFaculties = [];
        $adminFaculties = [];
        $deanHODFaculties = [];
        $generalFaculties = [];

        foreach ($allStaff as $staff) {
            $nameLower = strtolower($staff->name);
            $roleLower = strtolower($staff->role);
            $positionsLower = array_map('strtolower', $staff->positions ?? []);

            $isMath = strpos($nameLower, 'math') !== false || in_array('mathematics faculty', $positionsLower) || in_array('math faculty', $positionsLower);
            $isPhysics = strpos($nameLower, 'physics') !== false || in_array('physics faculty', $positionsLower);

            if ($isMath) {
                $mathFaculties[] = $staff->name;
            } elseif ($isPhysics) {
                $physicsFaculties[] = $staff->name;
            } elseif ($roleLower === 'admin') {
                $adminFaculties[] = $staff->name;
            } elseif (in_array($roleLower, ['dean', 'hod'])) {
                $deanHODFaculties[] = $staff->name;
            } else {
                $generalFaculties[] = $staff->name;
            }
        }

        // Fallbacks if empty
        if (empty($mathFaculties)) $mathFaculties = ['Dr. Amit Sharma(Mathematics Faculty)', 'Dr. Neha Gupta(Mathematics Faculty)'];
        if (empty($physicsFaculties)) $physicsFaculties = ['Dr. Suresh Trivedi(Physics Faculty)', 'Prof. Rakesh Dave(Physics Faculty)'];
        if (empty($adminFaculties)) $adminFaculties = ['Prof. Mohan Vyas(Head of Exam Controller)', 'BHAVIKKUMAR PATEL'];
        if (empty($deanHODFaculties)) $deanHODFaculties = ['Dr. Kalpana Matre(Sinior Faculty)(Co-Dean)(Quad PHD)', 'Dr. Asutosh Abhangi(Sinoir Faculty)(Co-Dean)(Triple PHD)', 'Prof. Shivangi Meteda(HOD Of All AI CS CSN IT)'];
        if (empty($generalFaculties)) $generalFaculties = ['Alka Ravat', 'Anupam Mund', 'Ankita Kumari', 'Meet Patel'];

        $theoryRooms = ['Room 301', 'Room 302', 'Room 303', 'Room 304', 'Room 305', 'Room 306', 'Room 307', 'Room 308', 'Room 309'];
        $labRooms = ['Room 303', 'Room 307', 'Room 309', 'Room 301'];

        // Let's get database occupied faculties and rooms at each slot to avoid cross-timetable clash
        $occupiedFaculties = [];
        $occupiedRooms = [];
        $existingEntries = TimetableEntry::all();
        foreach ($existingEntries as $entry) {
            $occupiedFaculties[$entry->day_of_week][$entry->slot][] = $entry->faculty;
            $occupiedRooms[$entry->day_of_week][$entry->slot][] = $entry->room;
        }

        $getFacultyForSubject = function($subjectType, $subjectName, $day, $slot) use (
            $mathFaculties, $physicsFaculties, $adminFaculties, $deanHODFaculties, $generalFaculties, &$occupiedFaculties
        ) {
            if ($subjectType === 'math') {
                $pool = $mathFaculties;
            } elseif ($subjectType === 'physics') {
                $pool = $physicsFaculties;
            } elseif ($subjectName === 'Placement Training' || $subjectName === 'Mentoring Soft Skills') {
                $pool = ['Prof. Maddona Lamin(Placememet and Devlopment and Tranner Head+Dean)'];
            } elseif ($subjectType === 'other') {
                $pool = array_merge($adminFaculties, $generalFaculties);
            } else {
                $pool = array_merge($generalFaculties, $deanHODFaculties, $adminFaculties);
            }

            $dayOccupied = $occupiedFaculties[$day][$slot] ?? [];
            $available = array_values(array_filter($pool, function($fac) use ($dayOccupied) {
                return !in_array($fac, $dayOccupied);
            }));

            if (!empty($available)) {
                $selected = $available[array_rand($available)];
                $occupiedFaculties[$day][$slot][] = $selected;
                return $selected;
            }

            $selected = $pool[array_rand($pool)];
            $occupiedFaculties[$day][$slot][] = $selected;
            return $selected;
        };

        $getRoomForSubject = function($subjectType, $day, $slot) use ($theoryRooms, $labRooms, &$occupiedRooms) {
            $pool = ($subjectType === 'lab') ? $labRooms : $theoryRooms;
            $dayOccupied = $occupiedRooms[$day][$slot] ?? [];
            $available = array_values(array_filter($pool, function($room) use ($dayOccupied) {
                return !in_array($room, $dayOccupied);
            }));

            if (!empty($available)) {
                $selected = $available[array_rand($available)];
                $occupiedRooms[$day][$slot][] = $selected;
                return $selected;
            }

            $selected = $pool[array_rand($pool)];
            $occupiedRooms[$day][$slot][] = $selected;
            return $selected;
        };

        $template = [
            'Monday' => [
                1 => ['subject' => 'Discrete Mathematics', 'type' => 'math', 'duration' => 1],
                2 => ['subject' => 'Computer Organization and Architecture', 'type' => 'theory', 'duration' => 1],
                3 => ['subject' => 'Digital Logic Design', 'type' => 'theory', 'duration' => 1],
                4 => ['subject' => 'Digital Logic Design', 'type' => 'theory', 'duration' => 1],
                5 => ['subject' => 'Digital Logic Design', 'type' => 'theory', 'duration' => 1],
                6 => ['subject' => 'Discrete Mathematics', 'type' => 'math', 'duration' => 1],
            ],
            'Tuesday' => [
                1 => ['subject' => 'Computer Organization and Architecture', 'type' => 'theory', 'duration' => 1],
                2 => ['subject' => 'Data Structures Lab', 'type' => 'lab', 'duration' => 2],
                3 => null,
                4 => ['subject' => 'Mentoring Soft Skills', 'type' => 'other', 'duration' => 1],
                5 => ['subject' => 'Database Management Systems', 'type' => 'theory', 'duration' => 1],
                6 => ['subject' => 'Technical Seminar', 'type' => 'other', 'duration' => 1],
            ],
            'Wednesday' => [
                1 => ['subject' => 'Database Management Systems', 'type' => 'theory', 'duration' => 1],
                2 => ['subject' => 'Placement Training', 'type' => 'other', 'duration' => 1],
                3 => ['subject' => 'Data Structures and Algorithms', 'type' => 'theory', 'duration' => 1],
                4 => ['subject' => 'Sports Activity', 'type' => 'other', 'duration' => 1],
                5 => ['subject' => 'PBL: AI & Machine Learning', 'type' => 'lab', 'duration' => 2],
                6 => null,
            ],
            'Thursday' => [
                1 => ['subject' => 'Database Management Systems', 'type' => 'theory', 'duration' => 1],
                2 => ['subject' => 'DBMS Lab', 'type' => 'lab', 'duration' => 2],
                3 => null,
                4 => ['subject' => 'Library Session', 'type' => 'other', 'duration' => 1],
                5 => ['subject' => 'Data Structures and Algorithms', 'type' => 'theory', 'duration' => 1],
                6 => ['subject' => 'Digital Logic Design', 'type' => 'theory', 'duration' => 1],
            ],
            'Friday' => [
                1 => ['subject' => 'PBL: Web Development Capstone', 'type' => 'lab', 'duration' => 2],
                2 => null,
                3 => ['subject' => 'Data Structures and Algorithms', 'type' => 'theory', 'duration' => 1],
                4 => ['subject' => 'Computer Organization and Architecture', 'type' => 'theory', 'duration' => 1],
                5 => ['subject' => 'Library Session', 'type' => 'other', 'duration' => 1],
                6 => ['subject' => 'Discrete Mathematics', 'type' => 'math', 'duration' => 1],
            ],
            'Saturday' => [
                1 => ['subject' => 'Physics 2 (Hard core Physics, Digital Electronics)', 'type' => 'physics', 'duration' => 1],
                2 => ['subject' => 'Calculus for Computer Science', 'type' => 'math', 'duration' => 1],
                3 => ['subject' => 'Linear Algebra & Matrices', 'type' => 'math', 'duration' => 1],
                4 => ['subject' => 'Probability & Statistics for CSE', 'type' => 'math', 'duration' => 1],
                5 => null,
                6 => null,
            ]
        ];

        $slots = [];
        foreach ($template as $day => $daySlots) {
            $slots[$day] = [];
            foreach ($daySlots as $slot => $info) {
                if ($info === null) {
                    $slots[$day][$slot] = ['subject' => '', 'faculty' => '', 'room' => '', 'duration' => 1];
                    continue;
                }

                $fac = $getFacultyForSubject($info['type'], $info['subject'], $day, $slot);
                $room = $getRoomForSubject($info['type'], $day, $slot);

                if ($info['duration'] === 2) {
                    $occupiedFaculties[$day][$slot + 1][] = $fac;
                    $occupiedRooms[$day][$slot + 1][] = $room;
                }

                $slots[$day][$slot] = [
                    'subject' => $info['subject'],
                    'faculty' => $fac,
                    'room' => $room,
                    'duration' => $info['duration']
                ];
            }
        }

        return response()->json([
            'success' => true,
            'slots' => $slots
        ]);
    }

    public function editManual($id)
    {
        $timetable = Timetable::with('entries')->findOrFail($id);
        if (!in_array(session('user_role'), ['cr', 'hod', 'dean', 'admin'])) {
            return redirect('/admin/timetables')->with('error', 'Unauthorized.');
        }

        $departments = Department::all();
        $faculties = Staff::orderBy('name', 'asc')->get();
        $courses = Course::orderBy('title', 'asc')->get();
        $grid = [];
        foreach($timetable->entries as $entry) {
            $grid[$entry->day_of_week][$entry->slot] = $entry;
        }

        return view('admin.timetables_builder', compact('departments', 'timetable', 'grid', 'faculties', 'courses'));
    }

    public function updateManual(Request $request, $id)
    {
        if (!in_array(session('user_role'), ['cr', 'hod', 'dean', 'admin'])) {
            return redirect('/admin/timetables')->with('error', 'Unauthorized.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'semester' => 'nullable|string',
            'slots' => 'required|array',
        ]);

        $timetable = Timetable::findOrFail($id);
        $timetable->update([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'semester' => $request->semester,
        ]);

        // Delete old entries and insert new ones
        TimetableEntry::where('timetable_id', $id)->delete();

        foreach ($request->slots as $day => $daySlots) {
            foreach ($daySlots as $slotNumber => $data) {
                if (!empty($data['subject']) || !empty($data['faculty'])) {
                    TimetableEntry::create([
                        'timetable_id' => $timetable->id,
                        'day_of_week' => $day,
                        'slot' => $slotNumber,
                        'duration' => $data['duration'] ?? 1,
                        'subject' => $data['subject'] ?? 'Subject',
                        'faculty' => $data['faculty'] ?? null,
                        'room' => $data['room'] ?? null,
                        'is_cancelled' => !empty($data['is_cancelled']),
                        'cancel_reason' => $data['cancel_reason'] ?? null,
                        'faculty_cancel_reason' => $data['faculty_cancel_reason'] ?? $data['cancel_reason'] ?? null,
                        'student_cancel_reason' => $data['student_cancel_reason'] ?? $data['cancel_reason'] ?? null,
                        'is_extra' => !empty($data['is_extra']),
                        'extra_reason' => $data['extra_reason'] ?? null,
                    ]);
                }
            }
        }

        return redirect('/admin/timetables')->with('success', 'Timetable updated successfully.');
    }

    public function studentIndex()
    {
        $timetables = Timetable::latest()->get();
        return view('student.timetables', compact('timetables'));
    }

    public function show($id)
    {
        $timetable = Timetable::with('entries')->findOrFail($id);
        
        if ($timetable->file_path) {
            return redirect(asset('storage/' . $timetable->file_path));
        }

        $grid = [];
        foreach($timetable->entries as $entry) {
            $grid[$entry->day_of_week][$entry->slot] = $entry;
        }

        return view('student.timetable_show', compact('timetable', 'grid'));
    }

    public function facultyShow($id)
    {
        $timetable = Timetable::with('entries')->findOrFail($id);
        
        if ($timetable->file_path) {
            return redirect(asset('storage/' . $timetable->file_path));
        }

        $grid = [];
        foreach($timetable->entries as $entry) {
            $grid[$entry->day_of_week][$entry->slot] = $entry;
        }

        return view('admin.timetable_faculty_view', compact('timetable', 'grid'));
    }
}
