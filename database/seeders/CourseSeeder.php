<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::insert([
            [
                'name' => 'DCA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ADCA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DOMP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'COMP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DFA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
