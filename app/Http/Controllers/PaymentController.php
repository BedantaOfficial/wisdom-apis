<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class PaymentController extends Controller
{
    /**
     * Fetch the last paid records based on the date range.
     */
    public function index(Request $request)
    {
        // Get start and end date from request
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date) {
            $start_date = now()->toDateString();
        }
        if (!$end_date) {
            $end_date = now()->toDateString();
        }

        // Write the raw SQL query
        $query = DB::select("
            SELECT *
            FROM user_records AS ur join student_admissions stu
            ON ur.user_id = stu.id
            WHERE (ur.record_id, ur.user_id) IN (
                SELECT MAX(record_id) AS record_id, user_id
                FROM user_records
                WHERE status = 'paid'
                AND updated_at BETWEEN ? AND ?
                GROUP BY user_id
            )
        ", [$start_date, $end_date]);

        // Return the results
        return response()->json($query);
    }


    public function getAllPaymentsOfStudent(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:student_admissions,id',
            ]);

            $user_id = $request->input('user_id');

            $payments = Payment::where('user_id', $user_id)
                ->get();

            return response()->json([
                'payments' => $payments
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while fetching payments',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
