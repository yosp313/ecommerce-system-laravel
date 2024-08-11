<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function login(Request $request) : JsonResponse{
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if(!Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password'], 'is_admin' => 1])){
            return response()->json([
                'message' => 'Invalid credentials or you are not an admin'
            ], 401);
        }

        return response()->json([
            'message' => 'User logged in',
        ], 200);
    }

    public function logout(Request $request) : JsonResponse{
        //logout the user
        Auth::logout();
        return response()->json([
            'message' => 'User logged out'
        ], 200);
    }

}

