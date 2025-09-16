<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo) {
            File::delete(storage_path('app/private/profile_photos/' . $user->profile_photo));
        }

        $fileName = time() . '_' . $user->id . '_' . $request->file('profile_photo')->getClientOriginalName();
        $filePath = $request->file('profile_photo')->storeAs('profile_photos', $fileName, 'local');

        $user->profile_photo = $fileName; //Update the user record
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-photo-updated');
    }

    public function showProfilePhoto($filename)
    {
        // Step 1: Get the currently authenticated user
        $user = auth()->user();

        // Step 2: Check if the user is trying to access their own photo
        if ($user->profile_photo !== $filename) {
            abort(403); // Prevent access to others' photos
        }

        // Step 3: Construct the file path
        $path = storage_path('app/private/profile_photos/' . $filename);

        // Step 4: Check if the file exists at the specified path
        if (!File::exists($path)) {
            abort(404);
        }

        // Step 5: Return the file response to the browser
        return response()->file($path);
    }
    // แสดงหน้าแก้ไข Bio
    public function showBio()
    {
        $user = Auth::user();
        $bio  = $user->bio; // hasOne
        return view('profile.show-bio', compact('user', 'bio'));
    }

    // อัปเดต/สร้าง Bio (ถ้าไม่เคยมี)
    public function updateBio(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'bio' => ['required','string','max:2000'],
        ]);

        // ถ้ามีอยู่แล้ว → update, ถ้าไม่ → create
        $user->bio()
            ->updateOrCreate(
                ['user_id' => $user->id],
                ['bio' => $validated['bio']]
            );

        // กลับไปหน้า bio (หรือจะกลับ profile.edit ก็ได้)
        return redirect()
            ->route('profile.show-bio')
            ->with('status', 'Bio updated successfully!');
    }
}