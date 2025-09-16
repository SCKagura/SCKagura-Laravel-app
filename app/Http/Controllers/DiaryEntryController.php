<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\DiaryEntry;
use Illuminate\Http\Request;
use App\Models\Emotion;
class DiaryEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
  {
      $diaryEntries = Auth::user()->diaryEntries()
      ->with('emotions')
      ->get();
      return view('diary.index', compact('diaryEntries'));
  }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $emotions = Emotion::all(); // Fetch all emotions for selection
    return view('diary.create', compact('emotions')); // Pass emotions to the view
}

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'date' => 'required|date',
        'content' => 'required|string',
        'emotions' => 'array', // Validate emotions as an array
        'intensity' => 'array', // Validate intensity as an array
    ]);

    // Create the diary entry
    // retrieves the currently authenticated user model
    $diaryEntry = Auth::user()->diaryEntries()->create([
        'date' => $validated['date'],
        'content' => $validated['content'],
    ]);

    // Handle emotions and intensities
    if (!empty($validated['emotions']) && !empty($validated['intensity'])) {
        foreach ($validated['emotions'] as $emotionId) {
            $intensity = $validated['intensity'][$emotionId] ?? null;

            // Attach emotions and intensities to the diary entry
            $diaryEntry->emotions()->attach($emotionId, ['intensity' => $intensity]);
        }
    }

    return redirect()->route('diary.index')->with('status', 'Diary entry added successfully!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $diaryEntry = Auth::user()
        ->diaryEntries()
        ->with('emotions') // eager load related emotions
        ->findOrFail($id);

    return view('diary.show', compact('diaryEntry'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    $diaryEntry = Auth::user()->diaryEntries()->with('emotions')->findOrFail($id);
    $emotions =Emotion::orderBy('name')->get();  // 👈 ต้องมี
    return view('diary.edit', compact('diaryEntry', 'emotions'));
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    // Validate the request
    $validated = $request->validate([
        'date' => 'required|date',
        'content' => 'required|string',
        'emotions' => 'array', // Validate emotions as an array
        'intensity' => 'array', // Validate intensity as an array
    ]);

    // Find and update the diary entry
    $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);
    $diaryEntry->update([
        'date' => $validated['date'],
        'content' => $validated['content'],
    ]);

    // Sync emotions and intensities
    if (!empty($validated['emotions'])) {
        $emotions = [];
        foreach ($validated['emotions'] as $emotionId) {
            $intensity = $validated['intensity'][$emotionId] ?? null;
            $emotions[$emotionId] = ['intensity' => $intensity];
        }
        $diaryEntry->emotions()->sync($emotions);
    } else {
        // If no emotions are selected, clear all associated emotions
        $diaryEntry->emotions()->sync([]);
    }

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
