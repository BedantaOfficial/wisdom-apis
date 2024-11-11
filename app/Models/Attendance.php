<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    protected $fillable = [
        'student_id',
        'date',
        'status',
    ];
    // Define the relationship with the Student model (since attendance is linked to a student via 'student_id')
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
        // 'student_id' is the foreign key in the attendance table, 'id' is the primary key in the student_admissions table
    }

    /**
     * Get the first and last attendance dates for a student.
     *
     * @param int $student_id
     * @return array
     */
    public static function getAttendanceDateRange($student_id)
    {
        // Get the first attendance date (earliest)
        $firstAttendance = self::where('student_id', $student_id)
            ->orderBy('date', 'asc')  // Order by date in ascending order
            ->first();

        // Get the last attendance date (latest)
        $lastAttendance = self::where('student_id', $student_id)
            ->orderBy('date', 'desc')  // Order by date in descending order
            ->first();

        // Check if attendance records exist for the student
        if ($firstAttendance && $lastAttendance) {
            return [
                'first_attendance_date' => Carbon::parse($firstAttendance->date)->toDateString(),
                'last_attendance_date' => Carbon::parse($lastAttendance->date)->toDateString(),
            ];
        }

        // Return an empty array if no attendance found for the student
        return [
            'first_attendance_date' => null,
            'last_attendance_date' => null,
        ];
    }
}
