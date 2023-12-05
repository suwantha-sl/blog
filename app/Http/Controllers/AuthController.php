<?php
// This AuthController handles user login and logout functions. User model is used.
// Developed by Ishan
// Developed date: 2023-Nov-26
// Last Updated: 2023-Dec-04

namespace App\Http\Controllers;

use Illuminate\Http\Request; // For handling HTTP requests
use Illuminate\Support\Facades\Auth; // For handling authentication
use App\Models\User; // User model

class AuthController extends Controller
{
    // function to handle user login
    public function login(Request $request){
        // validate incoming input data
        $request->validate([
            'email' => 'required|max:150|email',
            'password' => 'required|max:255'
        ],
        [
            'email.required' => 'Email cannot be empty', // custom error message
            'email.email' => 'Enter a valid email address',
            'email.max' => 'Email cannot exceed 150 characters',
            'password.required' => 'Password cannot be empty',
            'password.max' => 'Password cannot exceed 255 characters'
        ]
        );

        // check if the provided credentials are valid
        if(!Auth::attempt($request->only('email','password'))){
            // invalid login. Return status code 401 for unauthorized
            return response()->json(['message'=>'Invalid username/password', 'status'=>401],401);
        }

        // if credentials are valid get the authenticated user
        $user = $request->user();

        // create a token for user
        $token = $user->createToken('authToken')->plainTextToken;

        // Return user data and token as JSON
        return response()->json(['user' => $user, 'token' => $token]);
    }

    // function to handle logout
    public function logout(Request $request){

        // delete all tokens to the specific user
        $request->user()->tokens()->delete();

        // return json message
        return response()->json(['message'=>'You have been loged out.','status'=>200]);
    }
}
