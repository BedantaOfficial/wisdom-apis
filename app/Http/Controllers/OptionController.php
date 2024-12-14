<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    // Show all options
    public function index()
    {
        $options = Option::all();
        return response()->json($options);
    }

    // Show a single option
    public function show($id)
    {
        $option = Option::find($id);

        if (!$option) {
            return response()->json(['message' => 'Option not found'], 404);
        }

        return response()->json($option);
    }

    // Store a new option
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_details_id' => 'required|exists:question_details,id',
            'text' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        $option = Option::create($validated);
        return response()->json($option, 201); // 201 created response
    }

    // Update an existing option
    public function update(Request $request, $id)
    {
        $option = Option::find($id);

        if (!$option) {
            return response()->json(['message' => 'Option not found'], 404);
        }

        $validated = $request->validate([
            'question_details_id' => 'required|exists:question_details,id',
            'text' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        $option->update($validated);
        return response()->json($option);
    }

    // Delete an option
    public function destroy($id)
    {
        $option = Option::find($id);

        if (!$option) {
            return response()->json(['message' => 'Option not found'], 404);
        }

        $option->delete();
        return response()->json(['message' => 'Option deleted successfully']);
    }
}
