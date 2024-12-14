<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paper;
use App\Models\Course;

class PaperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch course IDs dynamically
        $courses = Course::pluck('id', 'name'); // ['Course Name' => id]

        Paper::insert([
            // DCA Semester I
            ['paper_code' => '1001', 'semester' => 1, 'name' => 'Fundamental of Computer & IT', 'course_id' => $courses['DCA']],
            ['paper_code' => '1010', 'semester' => 1, 'name' => 'MS - Office (Word, Excel, PowerPoint)', 'course_id' => $courses['DCA']],
            ['paper_code' => '1214', 'semester' => 1, 'name' => 'Database using MS-Access', 'course_id' => $courses['DCA']],
            ['paper_code' => '1150', 'semester' => 1, 'name' => 'Tally GST Erp 9.0', 'course_id' => $courses['DCA']],

            // DCA Semester II
            ['paper_code' => '1015', 'semester' => 2, 'name' => 'DTP (PageMaker, CorelDraw, Photoshop)', 'course_id' => $courses['DCA']],
            ['paper_code' => '1104', 'semester' => 2, 'name' => 'Macromedia Fireworks', 'course_id' => $courses['DCA']],
            ['paper_code' => '1272', 'semester' => 2, 'name' => 'Programming in C++', 'course_id' => $courses['DCA']],
            ['paper_code' => '1005', 'semester' => 2, 'name' => 'OS (MS-Dos, MS-Windows)', 'course_id' => $courses['DCA']],

            // ADCA Semester I
            ['paper_code' => '1010', 'semester' => 1, 'name' => 'MS-Office (Word, Excel, PowerPoint)', 'course_id' => $courses['ADCA']],
            ['paper_code' => '1214', 'semester' => 1, 'name' => 'Database using MS-Access', 'course_id' => $courses['ADCA']],
            ['paper_code' => '1105', 'semester' => 1, 'name' => 'HTML/DHTML', 'course_id' => $courses['ADCA']],
            ['paper_code' => '1150', 'semester' => 1, 'name' => 'Tally GST Erp 9.0', 'course_id' => $courses['ADCA']],

            // ADCA Semester II
            ['paper_code' => '1015', 'semester' => 2, 'name' => 'DTP (PageMaker, CorelDraw, Photoshop)', 'course_id' => $courses['ADCA']],
            ['paper_code' => '1104', 'semester' => 2, 'name' => 'Macromedia Fireworks', 'course_id' => $courses['ADCA']],
            ['paper_code' => '1038', 'semester' => 2, 'name' => 'C & C++', 'course_id' => $courses['ADCA']],
            ['paper_code' => '1005', 'semester' => 2, 'name' => 'OS (MS-Dos, MS-Windows)', 'course_id' => $courses['ADCA']],

            // DOMP Semester I
            ['paper_code' => '1001', 'semester' => 1, 'name' => 'Fundamental of Computer & IT', 'course_id' => $courses['DOMP']],
            ['paper_code' => '1010', 'semester' => 1, 'name' => 'MS-Office (Word, Excel, PowerPoint)', 'course_id' => $courses['DOMP']],
            ['paper_code' => '1015', 'semester' => 1, 'name' => 'DTP (PageMaker, CorelDraw, Photoshop)', 'course_id' => $courses['DOMP']],
            ['paper_code' => '1043', 'semester' => 1, 'name' => 'Internet & HTML', 'course_id' => $courses['DOMP']],
            ['paper_code' => '1214', 'semester' => 1, 'name' => 'Database using MS-Access', 'course_id' => $courses['DOMP']],

            // COMP
            ['paper_code' => '1010', 'semester' => 1, 'name' => 'MS-Office (Word, Excel, PowerPoint)', 'course_id' => $courses['COMP']],
            ['paper_code' => '1214', 'semester' => 1, 'name' => 'Database using MS-Access', 'course_id' => $courses['COMP']],
            ['paper_code' => '1105', 'semester' => 1, 'name' => 'HTML/DHTML', 'course_id' => $courses['COMP']],
            ['paper_code' => '1150', 'semester' => 1, 'name' => 'Tally GST Erp 9.0', 'course_id' => $courses['COMP']],

            // DFA Semester I
            ['paper_code' => '1023', 'semester' => 1, 'name' => 'Concepts of Accounting', 'course_id' => $courses['DFA']],
            ['paper_code' => '1024', 'semester' => 1, 'name' => 'Computerized Accounting with Tally', 'course_id' => $courses['DFA']],
            ['paper_code' => '1200', 'semester' => 1, 'name' => 'Basic Advanced Accounting', 'course_id' => $courses['DFA']],
            ['paper_code' => '1201', 'semester' => 1, 'name' => 'Statutory Accounting', 'course_id' => $courses['DFA']],
        ]);
    }
}
