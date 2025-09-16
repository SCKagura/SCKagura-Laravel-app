<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use Carbon\Carbon;
class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
 {
     $tags = [
         'work',
         'study',
         'gardening',
         'cooking',
         'take pill',
     ];

     foreach ($tags as $name) {
         Tag::firstOrCreate(['name' => $name],
         ['created_at' => Carbon::now(),
         'updated_at' => Carbon::now()]);
     }
 }
}
