<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // 👈 ต้องมี use นี้ด้วย
use App\Http\Controllers\UserController; // 👈 ต้องมี use นี้ด้วย
use App\Http\Controllers\DiaryEntryController;
use App\Http\Controllers\SocialLinkController;



Route::get('/', function () {
    return view('welcome');
});

// Dashboard (ของ Breeze)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// ✅ กลุ่มโปรไฟล์ (ตัวนี้ให้ชื่อ profile.edit)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
     Route::patch('/profile/photo/update', [UserController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::get('/profile/photo/{filename}', [UserController::class, 'showProfilePhoto'])->where('filename', '.*')->name('user.photo');
    Route::middleware('auth')->group(function () {
    // หน้าแสดง/แก้ไข bio
    Route::get('/profile/bio',   [UserController::class, 'showBio'])->name('profile.show-bio');
    // อัปเดต bio
    Route::patch('/profile/bio', [UserController::class, 'updateBio'])->name('profile.update-bio');
     Route::resource('diary', DiaryEntryController::class); //add this line
    // routes/web.php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::resource('social-links', SocialLinkController::class);
});


});
});

// ให้แน่ใจว่ามีบรรทัดนี้ (routes login/register)
require __DIR__.'/auth.php';
