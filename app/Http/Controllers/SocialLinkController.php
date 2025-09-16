<?php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class SocialLinkController extends Controller
{
    // READ: list ทั้งหมดของ user (ใหม่สุดก่อน)
    public function index()
    {
        $links = Auth::user()
            ->socialLinks()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('social_links.index', compact('links'));
    }

    // CREATE: ฟอร์มสร้าง
    public function create()
    {
        return view('social_links.create');
    }

    // STORE: บันทึก (platform เป็น text + unique ต่อ user)
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'platform' => [
                'required','string','max:50',
                'unique:social_links,platform,NULL,id,user_id,' . $userId,
            ],
            'url'    => ['required','url','max:255'],
        ], [
            'platform.unique' => 'You already added this platform.',
        ]);

        Auth::user()->socialLinks()->create($validated);

        return redirect()->route('social-links.index')
            ->with('status', 'Social link added successfully!');
    }

    // SHOW (option เผื่ออยากใช้)
    public function show(string $id)
    {
        $link = Auth::user()->socialLinks()->findOrFail($id);
        return view('social_links.show', compact('link'));
    }

    // EDIT
    public function edit(string $id)
    {
        $link = Auth::user()->socialLinks()->findOrFail($id);
        return view('social_links.edit', compact('link'));
    }

    // UPDATE: ยกเว้นตัวเองเวลา unique
    public function update(Request $request, string $id): RedirectResponse
    {
        $link = Auth::user()->socialLinks()->findOrFail($id);

        $validated = $request->validate([
            'platform' => [
                'required','string','max:50',
                'unique:social_links,platform,' . $link->id . ',id,user_id,' . Auth::id(),
            ],
            'url'    => ['required','url','max:255'],
        ]);

        $link->update($validated);

        return redirect()->route('social-links.index')
            ->with('status', 'Social link updated successfully!');
    }

    // DELETE
    public function destroy(string $id): RedirectResponse
    {
        $link = Auth::user()->socialLinks()->findOrFail($id);
        $link->delete();

        return redirect()->route('social-links.index')
            ->with('status', 'Social link deleted successfully!');
    }
}
