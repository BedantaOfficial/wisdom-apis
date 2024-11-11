<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getToken(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Get the username and password from the request
        $username = $request->get('username');
        $password = $request->get('password');

        // Generate token with valid credentials
        $token = Admin::generateToken([
            'username' => $username,
            'password' => $password
        ]);

        // Return the token in the response
        return response()->json(['token' => $token], 200);
    }
}
