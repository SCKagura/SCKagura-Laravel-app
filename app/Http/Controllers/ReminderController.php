<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
   

    /** List */
    public function index()
    {
        $reminders = Auth::user()
            ->reminders()
            ->with('tags')
            ->orderBy('remind_at', 'asc')
            ->get();

        return view('reminders.index', compact('reminders'));
    }

    /** Create form */
    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        return view('reminders.create', compact('tags'));
    }

    /** Store */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => ['required','string','max:255'],
            'note'      => ['nullable','string'],
            'remind_at' => ['required','date'],
            'status'    => ['required','in:new,done,snoozed'],
            'tags'      => ['nullable','array'],
            'tags.*'    => ['integer','exists:tags,id'],
        ]);

        $reminder = Auth::user()->reminders()->create($validated);

        // attach tags
        $reminder->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('reminders.index')->with('status', 'Reminder created!');
    }

    /** Edit form */
    public function edit(Reminder $reminder)
    {
        abort_unless($reminder->user_id === Auth::id(), 403);

        $tags = Tag::orderBy('name')->get();
        $reminder->load('tags');

        return view('reminders.edit', compact('reminder','tags'));
    }

    /** Update */
    public function update(Request $request, Reminder $reminder)
    {
        abort_unless($reminder->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'title'     => ['required','string','max:255'],
            'note'      => ['nullable','string'],
            'remind_at' => ['required','date'],
            'status'    => ['required','in:new,done,snoozed'],
            'tags'      => ['nullable','array'],
            'tags.*'    => ['integer','exists:tags,id'],
        ]);

        $reminder->update($validated);
        $reminder->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('reminders.index')->with('status', 'Reminder updated!');
    }

    /** Destroy */
    public function destroy(Reminder $reminder)
    {
        abort_unless($reminder->user_id === Auth::id(), 403);

        // เคลียร์ pivot เพราะ morphs ไม่มี FK cascade
        $reminder->tags()->detach();
        $reminder->delete();

        return redirect()->route('reminders.index')->with('status', 'Reminder deleted!');
    }
}
