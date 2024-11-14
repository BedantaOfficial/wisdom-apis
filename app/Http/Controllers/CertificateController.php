<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'student_id' => 'required|exists:student_admissions,id',
            ]);

            $certificates = Certificate::where('student_id', $request->student_id)->first();
            if (!$certificates) {
                return response()->json([
                    'error' => 'No certificates found for the given student.'
                ], 404);
            }
            return response()->json([
                'marksheet' => asset('storage/' . $certificates->marksheet_filename),
                'certificates' => asset('storage/' . $certificates->certificate_filename)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'An error occurred while saving certificate and marksheet.',
                'message' => $th->getMessage()
            ]);
        }
    }
    public function store(Request $request)
    {
        try {
            // Validate that student_id and both files (certificate, marksheet) are provided
            $request->validate([
                'student_id' => 'required|exists:student_admissions,id',
                'certificate' => 'required|file|mimes:jpg,png,pdf', // adjust mime types as needed
                'marksheet' => 'required|file|mimes:jpg,png,pdf',
            ]);

            // Upload files and store paths
            $uploadedFiles = [];
            if ($request->hasFile('certificate') && $request->hasFile('marksheet')) {
                $uploadedFiles['certificate'] = $request->file('certificate')->store('uploads/certificate', 'public');
                $uploadedFiles['marksheet'] = $request->file('marksheet')->store('uploads/marksheet', 'public');
            } else {
                return response()->json(['error' => 'Both certificate and marksheet files are required.'], 400);
            }

            // Find existing Certificate record for the student_id
            $certificate = Certificate::where('student_id', $request->student_id)->first();

            if ($certificate) {
                // Update existing record with new file paths if files are provided
                $certificate->certificate_filename = $uploadedFiles['certificate'];
                $certificate->marksheet_filename = $uploadedFiles['marksheet'];
                $certificate->save();
            } else {
                // Create new record if no existing record is found
                $certificate = new Certificate();
                $certificate->student_id = $request->student_id;
                $certificate->certificate_filename = $uploadedFiles['certificate'];
                $certificate->marksheet_filename = $uploadedFiles['marksheet'];
                $certificate->save();
            }

            return response()->json([
                'message' => 'Certificate and Marksheet saved successfully.',
                'data' => $certificate
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'An error occurred while saving certificate and marksheet.',
                'message' => $th->getMessage()
            ]);
        }
    }
}
