<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'student_id' => 'required|exists:student_admissions,id', // Ensure student_id exists in the students table
                'year' => 'required|digits:4|integer', // Ensure year is a 4-digit integer
                'month' => 'required|integer|between:1,12', // Ensure month is an integer between 1 and 12
            ]);

            // Extract the student_id, year, and month from the request
            $student_id = $request->student_id;
            $year = $request->year;
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT); // Ensure month is two digits (e.g., 01, 02, ..., 12)

            // Create a date range for the given month and year
            $startDate = "$year-$month-01"; // First day of the month
            $endDate = "$year-$month-" . date('t', strtotime($startDate)); // Last day of the month (dynamically calculated)

            // Get the attendance records for the given student, year, and month
            $attendanceRecords = Attendance::where('student_id', $student_id) // Corrected the query syntax
                ->whereBetween('date', [$startDate, $endDate])
                ->get();
        } catch (\Exception $e) {
            // Catch any errors and return a response
            return response()->json([
                'error' => 'Failed to fetch attendance records',
                'message' => $e->getMessage(), // Include the error message for debugging
            ], 500);
        }

        // Return the attendance records
        return response()->json([
            'attendance' => $attendanceRecords
        ], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validated = $request->validate([
                'student_id' => 'required|exists:student_admissions,id', // Ensure the student exists in the 'student_admissions' table
                'date' => 'required|date|unique:attendances,student_id,NULL,id,date,' . $request->date, // Ensure the date is unique for the student
                'status' => 'required|in:present,absent', // Ensure the status is either 'present' or 'absent'
            ]);

            // Check if attendance for the same student and date already exists
            $attendance = Attendance::firstOrCreate(
                [
                    'student_id' => $request->student_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $request->status, // Store the status if the attendance is created
                ]
            );

            // If attendance was not created, it means it already exists
            if ($attendance->wasRecentlyCreated) {
                return response()->json([
                    'message' => 'Attendance taken successfully',
                    'attendance' => $attendance
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Attendance for this student on this day has already been taken'
                ], 400);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            return response()->json([
                'error' => 'Database error occurred while saving attendance.',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the attendance date range (first and last attendance dates) for a student.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendanceDateRange(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'student_id' => 'required|exists:student_admissions,id', // Ensure student exists in student_admissions table
            ]);

            // Get the student ID from the request
            $student_id = $request->student_id;

            // Get the attendance date range for the student
            $attendanceDateRange = Attendance::getAttendanceDateRange($student_id);

            // Return the response with the attendance date range
            return response()->json([
                'message' => 'Attendance date range fetched successfully',
                'attendance_date_range' => $attendanceDateRange
            ]);
        } catch (\Exception $e) {
            // If any exception occurs, return an error response
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500); // HTTP status code 500 for internal server error
        }
    }




    public function getAllAttendance(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'student_id' => 'required|exists:student_admissions,id', // Ensure student exists in student_admissions table
            ]);

            // Get the student ID from the request
            $student_id = $request->student_id;

            // Get the attendance date range for the student
            $attendances = Attendance::where('student_id', $student_id)->get("date");

            return response()->json([
                'message' => 'Attendances fetched successfully',
                'attendances' => $attendances
            ]);
        } catch (\Exception $e) {
            // If any exception occurs, return an error response
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500); // HTTP status code 500 for internal server error
        }
    }
}
