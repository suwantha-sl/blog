<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // For handling HTTP requests
use Illuminate\Support\Facades\Auth; // For handling authentication
use App\Models\User; // User model

class AuthController extends Controller
{
    //

    public function login(Request $request){
        // validate incoming input data
        $request->validate([
            'email' => 'required|max:150|string|email',
            'password' => 'required|string|max:255'
        ]);

        // check if the provided credentials are valid
        if(!Auth::attempt($request->only('email','password'))){
            // invalid login. Return status code 401 for unauthorized
            return response()->json(['message'=>'Invalid username/password'],401);
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
