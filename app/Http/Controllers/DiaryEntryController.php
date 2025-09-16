<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\DiaryEntry;
use Illuminate\Http\Request;

class DiaryEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $diaryEntries = Auth::user()->diaryEntries()->get();
    return view('diary.index', compact('diaryEntries'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('diary.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'content' => 'required|string',
    ]);

    Auth::user()->diaryEntries()->create($validated);

    return redirect()->route('diary.index')->with('status', 'Diary entry added successfully!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
    return view('diary.show', compact('diaryEntry'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
    return view('diary.edit', compact('diaryEntry'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Retrieve the diary entry by its ID
    $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
    $validated = $request->validate([
        'date' => 'required|date',
        'content' => 'required|string',
    ]);

    $diaryEntry->update($validated);

    return redirect()->route('diary.index')->with('status', 'Diary entry updated successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(string $id): RedirectResponse
 {
     $diaryEntry = DiaryEntry::where('id', $id)
         ->where('user_id', Auth::id())
         ->firstOrFail(); // Will throw a ModelNotFoundException if the entry doesn't exist or doesn't belong to the user

     $diaryEntry->delete();

     return redirect()->route('diary.index')->with('status', 'Diary entry deleted successfully!');
 }
}
