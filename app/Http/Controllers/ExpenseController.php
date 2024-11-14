<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource (GET /api/expenses).
     */
    public function index()
    {
        // Fetch all expenses 
        $expenses = Expense::all();


        return response()->json([
            'expenses' => $expenses,
        ]);
    }

    /**
     * Store a newly created resource in storage (POST /api/expenses).
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'amount' => 'required|numeric',
            ]);

            // Create a new expense record
            Expense::create([
                'name' => $request->name,
                'type' => $request->type,
                'amount' => $request->amount,
                'date' => now(), // Automatically use the current date
            ]);

            return response()->json(['message' => 'Expense added successfully'], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
    public function delete(Request $request)
    {
        try {
            $expense = Expense::find($request->id);
            if ($expense) {
                $expense->delete();
                return response()->json(['message' => 'Expense deleted successfully']);
            }
            return response()->json(['message' => 'Expense not found']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
}
