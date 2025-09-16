<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\DiaryEntry;
use Illuminate\Http\Request;
use App\Models\Emotion;
use App\Models\Tag;
class DiaryEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $diaryEntries = Auth::user()->diaryEntries()
    ->with('emotions', 'tags') // Eager load emotions and tags
    ->orderBy('date', 'desc')
    ->get();
    return view('diary.index', compact('diaryEntries'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $emotions = Emotion::all(); // Fetch all emotions for selection
    $tags = Tag::all(); // Fetch all tags for selection
    return view('diary.create', compact('emotions', 'tags')); // Pass emotions and tags to the view
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
        'tags'      => ['nullable', 'array'],
        'tags.*'    => ['integer', 'exists:tags,id'],
    ]);

    // Create the diary entry
    // retrieves the currently authenticated user model
    $diaryEntry = Auth::user()->diaryEntries()->create([
        'date' => $validated['date'],
        'content' => $validated['content'],
    ]);
    $diaryEntry->tags()->sync($validated['tags'] ?? []); //Attaches only valid tags from the tags table. If no tags were passed → uses [] (detaches all).

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
    $diaryEntry = Auth::user()->diaryEntries()->with('emotions', 'tags')->findOrFail($id);
    $emotions = Emotion::all(); // Fetch all emotions for selection (you must have a model called 'Emotion' to fetch all emotions)
    $tags = Tag::all(); // Fetch all tags for selection
    return view('diary.edit', compact('diaryEntry', 'emotions', 'tags'));
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    // Validate the request
    $validated = $request->validate([
        'date'      => ['required', 'date'],
        'content'   => ['required', 'string'],
        'emotions'  => ['nullable', 'array'],
        'emotions.*'=> ['integer', 'exists:emotions,id'],
        'intensity' => ['nullable', 'array'],
        'tags'      => ['nullable', 'array'],
        'tags.*'    => ['integer', 'exists:tags,id'],
    ]);

    // Find and update the diary entry (only if it belongs to the logged-in user)
    $diaryEntry = Auth::user()->diaryEntries()->findOrFail($id);

    $diaryEntry->update([
        'date'    => $validated['date'],
        'content' => $validated['content'],
    ]);

    // ✅ Sync tags
    $diaryEntry->tags()->sync($validated['tags'] ?? []);

    // ✅ Sync emotions + intensities
    if (!empty($validated['emotions'])) {
        $emotions = [];
        foreach ($validated['emotions'] as $emotionId) {
            $intensity = $validated['intensity'][$emotionId] ?? null;
            $emotions[$emotionId] = ['intensity' => $intensity];
        }
        $diaryEntry->emotions()->sync($emotions);
    } else {
        $diaryEntry->emotions()->sync([]); // clear if none selected
    }

    return redirect()
        ->route('diary.index')
        ->with('status', 'Diary entry updated successfully!');
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
