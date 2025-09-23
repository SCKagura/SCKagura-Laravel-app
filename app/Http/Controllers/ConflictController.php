<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConflictController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // เคส insensitive ให้ชัวร์ทุกคอลเลคชัน (กัน collation แปลก ๆ)
        $conflicts = DB::table('diary_entries as de')
            ->join('diary_entry_emotions as dee', 'dee.diary_entry_id', '=', 'de.id')
            ->select('de.id','de.date','de.content','dee.intensity')
            ->where('de.user_id', $userId)
            ->where('dee.emotion_id', 2) // Sad
            ->whereRaw('LOWER(de.content) LIKE ?', ['%happy%'])
            ->orderBy('de.date','desc')
            ->paginate(10);

        $total = DB::table('diary_entries as de')
            ->join('diary_entry_emotions as dee', 'dee.diary_entry_id', '=', 'de.id')
            ->where('de.user_id', $userId)
            ->where('dee.emotion_id', 2)
            ->whereRaw('LOWER(de.content) LIKE ?', ['%happy%'])
            ->count();

        return view('conflicts.index', compact('conflicts', 'total'));
    }
}
