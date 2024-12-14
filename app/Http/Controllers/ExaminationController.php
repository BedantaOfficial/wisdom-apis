<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\ExaminationStudent;
use App\Models\Option;
use App\Models\Question;
use App\Models\QuestionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExaminationController extends Controller
{
    public function index()
    {
        $examinations = Examination::all()->sortByDesc("exam_date");
        return response()->json([
            "examinations" => $examinations
        ]);
    }
    public function getExamination($date)
    {
        // Validate and parse the provided date
        try {
            // Convert the date string to a Carbon instance
            $examDate = Carbon::createFromFormat('Y-m-d', $date);
            $examination = Examination::whereDate('exam_date', $examDate)->first();

            if ($examination) {
                // Get the examination along with papers and related data using the semester from $examination
                $examination = Examination::with([
                    'theoryQuestion.questionDetails',
                    'practicalQuestion.questionDetails',
                    'mcqQuestion.questionDetails.options',
                    'students:id,name,filename,enrollment_no',
                    'course.papers' => function ($query) use ($examination) {
                        $query->where('semester', $examination->semester); // Use the semester from $examination
                    }
                ])
                    ->whereDate('exam_date', $examDate)
                    ->first();

                return response()->json([
                    'examination' => $examination
                ]);
            } else {
                return response()->json([
                    'message' => 'Examination not found'
                ], 404);
            }
        } catch (\Exception $e) {
            // If there is an error in parsing the date or any other issue
            return response()->json(['message' => 'Invalid date format. Please use YYYY-MM-DD format.', "err" => $e], 400);
        }
    }
    public function create(Request $request)
    {
        DB::beginTransaction();  // Start transaction

        try {
            // Validate the basic fields
            $request->validate([
                'students' => 'required|array|min:1',
                'students.*' => 'integer|exists:student_admissions,id',
                'examDate' => 'required|date_format:Y-m-d|unique:examinations,exam_date',
                'course' => 'required|exists:courses,id',
                'semester' => 'required|exists:papers,semester',
                'examTime' => 'required|integer|min:1',
            ]);

            // Validate paperTypes manually
            $paperTypes = $request->input('paperTypes');
            if (!is_array($paperTypes) || !isset($paperTypes['theory'], $paperTypes['practical'], $paperTypes['mcq'])) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => [
                        'paperTypes' => 'The paperTypes field must be an object containing the keys: theory, practical, mcq.',
                    ],
                ], 422);
            }

            // Initialize variables to store question_ids for each question type
            $theory_question_id = null;
            $practical_question_id = null;
            $mcq_question_id = null;

            // Validate and insert theory questions if required
            if ($paperTypes['theory'] === true) {
                $request->validate([
                    'theoryQuestions' => 'required|array|min:1',
                    'theoryQuestions.*.id' => 'required|integer',
                    'theoryQuestions.*.text' => 'required|string',
                ]);

                $theory_questions = $request->get('theoryQuestions');
                // Create a question of type 'theory' and store its ID
                $question = Question::create(['type' => 'theory']);
                $theory_question_id = $question->id;

                // Prepare theory questions for bulk insert
                $question_details = [];
                foreach ($theory_questions as $theory_question) {
                    $question_details[] = [
                        'question_id' => $theory_question_id,
                        'question' => $theory_question['text'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                // Bulk insert theory questions
                QuestionDetail::insert($question_details);
            }

            // Validate and insert practical questions if required
            if ($paperTypes['practical'] === true) {
                $request->validate([
                    'practicalQuestions' => 'required|array|min:1',
                    'practicalQuestions.*.id' => 'required|integer',
                    'practicalQuestions.*.text' => 'required|string',
                ]);

                $practical_questions = $request->get('practicalQuestions');
                // Create a question of type 'practical' and store its ID
                $question = Question::create(['type' => 'practical']);
                $practical_question_id = $question->id;

                // Prepare practical questions for bulk insert
                $question_details = [];
                foreach ($practical_questions as $practical_question) {
                    $question_details[] = [
                        'question_id' => $practical_question_id,
                        'question' => $practical_question['text'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                // Bulk insert practical questions
                QuestionDetail::insert($question_details);
            }

            // Validate and insert MCQ questions if required
            if ($paperTypes['mcq'] === true) {
                $request->validate([
                    'mcqQuestions' => 'required|array|min:1',
                    'mcqQuestions.*.id' => 'required|integer',
                    'mcqQuestions.*.text' => 'required|string',
                    'mcqQuestions.*.options' => 'required|array|min:2',
                    'mcqQuestions.*.options.*' => 'required|string',
                ]);

                $mcq_questions = $request->get('mcqQuestions');
                // Create a question of type 'mcq' and store its ID
                $question = Question::create(['type' => 'mcq']);
                $mcq_question_id = $question->id;

                // Prepare MCQ question details for bulk insert
                $question_details = [];
                foreach ($mcq_questions as $mcq_question) {
                    $question_details[] = [
                        'question_id' => $mcq_question_id,
                        'question' => $mcq_question['text'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                // Bulk insert MCQ questions
                QuestionDetail::insert($question_details);

                // Get all the inserted question_detail IDs (assuming they are auto-incremented)
                $inserted_question_details = QuestionDetail::where('question_id', $mcq_question_id)->get();

                // Prepare question options for each MCQ question
                $question_options = [];
                foreach ($inserted_question_details as $index => $question_detail) {
                    foreach ($mcq_questions[$index]['options'] as $option) {
                        $question_options[] = [
                            'question_details_id' => $question_detail->id,
                            'option' => $option,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Bulk insert options for the MCQ questions
                Option::insert($question_options);
            }

            // Create examination
            $exam = Examination::create([
                'exam_date' => $request->examDate,
                'course_id' => $request->course,
                'semester' => $request->semester,
                'time_in_seconds' => $request->examTime,
                'theory_question_id' => $theory_question_id,
                'practical_question_id' => $practical_question_id,
                'mcq_question_id' => $mcq_question_id,
                'theory' => $paperTypes['theory'],
                'practical' => $paperTypes['practical'],
                'mcq' => $paperTypes['mcq'],
            ]);

            // Create a relationship between the examination and the students using ExamStudent
            $exam_id = $exam->id; // Get the created exam ID
            $student_ids = $request->students; // Get the student IDs from the request

            // Insert records into the ExamStudent pivot table
            $exam_students = [];
            foreach ($student_ids as $student_id) {
                $exam_students[] = [
                    'examination_id' => $exam_id,
                    'student_id' => $student_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Bulk insert into the ExamStudent pivot table
            ExaminationStudent::insert($exam_students);

            DB::commit();  // Commit transaction

            return response()->json(['message' => 'Examination created successfully.'], 201);
        } catch (ValidationException $e) {
            DB::rollBack();  // Rollback transaction in case of failure
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback transaction for any other errors
            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
