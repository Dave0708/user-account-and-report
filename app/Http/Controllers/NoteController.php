<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note = Note::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'] ?? 'Untitled note',
            'content' => $validated['content'] ?? '',
        ]);

        return response()->json($note);
    }

    public function index()
    {
        $notes = Note::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notes);
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $note->delete();
        return response()->json(['success' => true]);
    }
}
