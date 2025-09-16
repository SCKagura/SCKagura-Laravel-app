<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $user = Auth::user();
        $photoUrl = $user->profile_photo
            ? route('user.photo', ['filename' => $user->profile_photo])
            : asset('images/default-photo.png');
        $birthdateText = $user->birthdate
            ? \Carbon\Carbon::parse($user->birthdate)->format('F j, Y')
            : __('Not set');
    @endphp

    <div class="py-8 sm:py-12 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- wrapper จัดกึ่งกลางทั้งแนวนอนและตั้ง -->
            <div class="min-h-[50vh] flex items-center justify-center">
                <!-- card -->
                <div class="w-full max-w-xl">
                    <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white/90 dark:bg-gray-800/90 backdrop-blur">
                        <!-- แถบบนแบบ gradient -->
                        <div class="h-24 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500"></div>

                        <!-- เนื้อหาหลัก -->
                        <div class="-mt-16 p-6 sm:p-8 text-center">
                            <!-- avatar -->
                            <div class="mx-auto w-28 h-28 sm:w-32 sm:h-32 rounded-full ring-4 ring-white dark:ring-gray-800 overflow-hidden shadow">
                                <img
                                    src="{{ $photoUrl }}"
                                    alt="Profile Photo"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                />
                            </div>

                            <!-- welcome -->
                            <h1 class="mt-4 text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                                🎉 Welcome back, <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-fuchsia-600">{{ $user->name }}</span>!
                            </h1>

                            <!-- birthdate -->
                            <p class="mt-3 text-sm sm:text-base text-gray-600 dark:text-gray-300">
                                <span class="inline-flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M6 2a1 1 0 0 1 1 1v1h10V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v3H3V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1Zm15 9H3v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8ZM7 15a1 1 0 1 1 0-2h2a1 1 0 1 1 0 2H7Zm5 0a1 1 0 1 1 0-2h2a1 1 0 1 1 0 2h-2Z"/>
                                    </svg>
                                    <span>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ __('Birthday') }}:</span>
                                        <strong class="ml-1">{{ $birthdateText }}</strong>
                                    </span>
                                </span>
                            </p>

                            <!-- ข้อความกำลังใจ -->
                            <p class="mt-6 text-base sm:text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ __('Have a great day ahead!') }} 🌟
                            </p>

                            <!-- ปุ่มลิงก์ไปหน้าโปรไฟล์ (ถ้ามี route profile.edit) -->
                            @if (Route::has('profile.edit'))
                                <div class="mt-6">
                                    <a href="{{ route('profile.edit') }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-indigo-200 dark:border-indigo-700
                                              hover:-translate-y-0.5 transition transform
                                              bg-indigo-50 text-indigo-700 hover:bg-indigo-100
                                              dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20.71 7.04a1 1 0 0 1 0 1.41l-9.9 9.9a1 1 0 0 1-.45.26l-4 1a1 1 0 0 1-1.23-1.23l1-4a1 1 0 0 1 .26-.45l9.9-9.9a1 1 0 0 1 1.41 0l2.01 2.01ZM14.04 6.99l-8.3 8.3-.52 2.08 2.08-.52 8.3-8.3-1.56-1.56Z"/>
                                        </svg>
                                        {{ __('Update Profile') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- note เล็ก ๆ -->
                    <p class="mt-4 text-center text-xs text-gray-500 dark:text-gray-400">
                        Tip: Upload a profile photo in your profile page to personalize your dashboard.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
