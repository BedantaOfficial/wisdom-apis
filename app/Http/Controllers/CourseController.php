<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses with their papers.
     */
    public function index()
    {
        try {
            // Fetch all courses with their related papers
            $courses = Course::with('papers')->get();

            // Convert to array and modify the course data to send 'name' instead of 'course_name'
            $courses = $courses->map(function ($course) {
                $courseArray = $course->toArray(); // Convert model to array
                $courseArray['name'] = $courseArray['course_name']; // Rename field
                unset($courseArray['course_name']); // Optionally remove course_name field
                return $courseArray;
            });

            // Return success response with courses and their papers
            return response()->json([
                'success' => true,
                'courses' => $courses,
            ], 200);
        } catch (\Exception $e) {
            // Return error response in case of an exception
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courses. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
