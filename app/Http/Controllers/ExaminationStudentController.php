<?php

namespace App\Http\Controllers;

use App\Models\ExaminationStudent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExaminationStudentController extends Controller
{
    /**
     * Display a single ExaminationStudent record.
     */
    public function index(Request $request)
    {
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'examId' => 'required|exists:examinations,id',
            'studentId' => 'required|exists:student_admissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        // Fetch a specific ExaminationStudent record based on provided criteria
        $examinationStudent = ExaminationStudent::with('examination', 'student')->where('examination_id', $request->examId)
            ->where('student_id', $request->studentId)
            ->first();

        if ($examinationStudent) {
            return response()->json([
                'examDetails' => $examinationStudent,
            ]);
        }

        return response()->json([
            'message' => 'Examination student record not found.',
        ], 404);
    }

    public function all(Request $request)
    {
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'examId' => 'required|exists:examinations,id',
            'studentId' => 'required|exists:student_admissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        // Fetch a specific ExaminationStudent record based on provided criteria
        $examinationStudent = ExaminationStudent::with('examination.theoryQuestion.questionDetails', 'examination.practicalQuestion.questionDetails', 'examination.mcqQuestion.questionDetails.options')->where('examination_id', $request->examId)
            ->where('student_id', $request->studentId)
            ->first();

        if ($examinationStudent) {
            return response()->json([
                'examDetails' => $examinationStudent,
            ]);
        }

        return response()->json([
            'message' => 'Examination student record not found.',
        ], 404);
    }

    /**
     * Store the start time when a student starts an exam.
     */
    public function startExam(Request $request)
    {
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'examId' => 'required|exists:examinations,id',
            'studentId' => 'required|exists:student_admissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Find the examination student record
            $examinationStudent = ExaminationStudent::where('examination_id', $request->examId)
                ->where('student_id', $request->studentId)
                ->first();

            if ($examinationStudent) {
                // If record exists, check if started_at is already set
                if ($examinationStudent->started_at) {
                    return response()->json([
                        'message' => 'The exam has already started.',
                    ], 400);
                }

                // Set the started_at time
                $examinationStudent->started_at = Carbon::now('Asia/Kolkata');;
                $examinationStudent->save();

                return response()->json([
                    'message' => 'Exam started successfully.',
                    'started_at' => $examinationStudent->started_at,
                ]);
            } else {
                // If no examination student record found, return an error
                return response()->json([
                    'message' => 'Examination student record not found.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function uploadAnswerFile(Request $request, $id)
    {
        // Validate the request to ensure a PDF is uploaded
        $request->validate([
            'pdf' => 'required|mimes:pdf',
        ]);

        // Find the ExaminationStudent record
        $examinationStudent = ExaminationStudent::with('examination')->find($id);

        if (!$examinationStudent) {
            return response()->json([
                'message' => 'ExaminationStudent record not found.'
            ], 404);
        }

        // Get the exam's start time and duration
        $startTime = Carbon::parse($examinationStudent->started_at);
        $endTime = $startTime->addSeconds($examinationStudent->examination->time_in_seconds)->addMinutes(2);

        // Check if the current time (Asia/Mumbai) is before the allowed end time
        if (Carbon::now('Asia/Kolkata')->gt($endTime)) {
            return response()->json([
                'message' => 'The exam time has expired. You can no longer upload the answer file.'
            ], 400);
        }

        // Handle the file upload
        $pdfFile = $request->file('pdf');
        $path = $pdfFile->storeAs(
            'answer_files', // Directory name in storage/app/public
            $pdfFile->getClientOriginalName(),
            'public' // Use the 'public' disk
        );

        // Update the answer_file_url with the stored file path
        $examinationStudent->answer_file_url = Storage::url($path); // Store public URL
        $examinationStudent->save();

        // Return a success response
        return response()->json([
            'message' => 'Answer file uploaded successfully!',
            'file_url' => $examinationStudent->answer_file_url
        ], 200);
    }

    public function submitAnswer(Request $request, $id)
    {
        // Find the ExaminationStudent record
        $examinationStudent = ExaminationStudent::with('examination')->find($id);

        if (!$examinationStudent) {
            return response()->json([
                'message' => 'ExaminationStudent record not found.'
            ], 404);
        }

        // Get the exam's start time and duration
        $startTime = Carbon::parse($examinationStudent->started_at);
        $endTime = $startTime->addSeconds($examinationStudent->examination->time_in_seconds)->addMinutes(2);

        // Check if the current time (Asia/Mumbai) is before the allowed end time
        if (Carbon::now('Asia/Kolkata')->gt($endTime)) {
            return response()->json([
                'message' => 'The exam time has expired. You can no longer submit your answer.'
            ], 400);
        }

        // Mark the exam as submitted
        $examinationStudent->submitted = 1;
        $examinationStudent->save();

        return response()->json([
            'message' => 'Answer submitted successfully!'
        ], 200);
    }
}
