<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        // Allow Admin, Dean, HOD, CR, CC (coordinator, faculty-lecturer-coordinator)
        if (!in_array(session('user_role'), ['admin', 'dean', 'hod', 'cr', 'coordinator', 'faculty-lecturer-coordinator']) && session('staff_name') != 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized access to Communications Center.');
        }

        $section = $request->query('section', 'General');
        $validSections = ['General', 'Academics', 'Exams', 'Placements', 'Administration'];
        
        if (!in_array($section, $validSections)) {
            $section = 'General';
        }

        $messages = \App\Models\Message::where('section', $section)->orderBy('created_at', 'asc')->get();

        return view('admin.chat', compact('messages', 'section', 'validSections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'section' => 'required|string'
        ]);

        $senderName = session('staff_name') ?? 'Admin';
        $senderRole = session('user_role') ?? 'admin';

        \App\Models\Message::create([
            'sender_name' => $senderName,
            'sender_role' => $senderRole,
            'message' => $request->message,
            'section' => $request->section
        ]);

        return back()->with('success', 'Message sent successfully.');
    }
}
