<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return response()->json([
            'message' => 'List of students',
            'students' => $students
        ]);
    }


    /**
     * Return the date range of a student
     * start: course start date
     * end: course end date
     */

    public function getCourseRange(Request $request)
    {

        $request->validate([
            'student_id' => 'required|exists:student_admissions,id',
        ]);


        $student_id = $request->student_id;
        $student = Student::find($student_id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }


        $start_date = $student->date_of_admission;
        $duration = preg_replace('/[^0-9]/', '', $student->duration);
        $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $duration . ' months'));

        return response()->json([
            'message' => 'Course range for the student',
            'student' => $student,
            'start' => $start_date,
            'end' => $end_date
        ]);
    }
}
