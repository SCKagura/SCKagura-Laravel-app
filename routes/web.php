<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;     // ต้องมี use นี้
use App\Http\Controllers\UserController;        // ต้องมี use นี้
use App\Http\Controllers\DiaryEntryController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\ReminderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'));

// Dashboard (Breeze) — ต้องล็อกอินและยืนยันอีเมลแล้ว
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
});

// กลุ่มที่ต้องล็อกอิน
Route::middleware('auth')->group(function () {

    // Profile (Breeze)
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile Photo
    Route::patch('/profile/photo/update', [UserController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::get('/profile/photo/{filename}', [UserController::class, 'showProfilePhoto'])
        ->where('filename', '.*')
        ->name('user.photo');

    // Bio
    Route::get('/profile/bio',   [UserController::class, 'showBio'])->name('profile.show-bio');
    Route::patch('/profile/bio', [UserController::class, 'updateBio'])->name('profile.update-bio');

    // Resources
    Route::resource('diary',        DiaryEntryController::class);
    Route::resource('social-links', SocialLinkController::class);
    Route::resource('reminders',    ReminderController::class); // ถ้าไม่ใช้ show ให้ ->except(['show'])
});

// auth routes (login / register / password reset)
require __DIR__.'/auth.php';
