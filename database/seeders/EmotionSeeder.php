<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Emotion;

class EmotionSeeder extends Seeder
{
    public function run(): void
    {
        $emotions = [
            ['name' => 'Happy', 'description' => 'Feeling of joy or pleasure'],
            ['name' => 'Sad', 'description' => 'Feeling of sorrow or unhappiness'],
            ['name' => 'Angry', 'description' => 'Feeling of strong displeasure or hostility'],
            ['name' => 'Excited', 'description' => 'Feeling of enthusiasm and eagerness'],
            ['name' => 'Anxious', 'description' => 'Feeling of worry, nervousness, or unease'],
        ];

        foreach ($emotions as $emotion) {
            Emotion::firstOrCreate(
                ['name' => $emotion['name']], // search by unique column
                [
                    'description' => $emotion['description'],
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]
            );
        }
    }
}