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
