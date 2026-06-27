<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalNote;

class PersonalNoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'color' => 'nullable|string'
        ]);

        $userId = session('demo_user_id') ?? auth()->id() ?? 1;

        PersonalNote::create([
            'user_id' => $userId,
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color ?? 'primary',
        ]);

        return back()->with('success', 'Personal note saved successfully!');
    }

    public function destroy($id)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $note = PersonalNote::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $note->delete();

        return back()->with('success', 'Personal note deleted successfully!');
    }
}
