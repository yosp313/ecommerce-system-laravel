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

        $user = User::where('email', $validatedData['email'])->first();

        if(!$user->is_admin){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        if(!$user || !Hash::check($validatedData['password'], $user->password)){
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        request()->session()->regenerate();

        return response()->json([
            'message' => 'Login successful'
        ]);
    }

}

