<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Hash;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\EmailNotification;



class ForgotPasswordController extends Controller
{
    // function to handle emailing of password reset link

    public function passwordReset(Request $request){
        // validate input email
        $request->validate([
            'email' => 'required|email|exists:users'
        ],
        [
            'email.required' => 'Email cannot be empty',
            'email.email' => 'Provie a valid email',
            'email.exists' => 'Invalid email'
        ]);

        // generate token
        $token = Str::random(64);

        $pass_reset = new PasswordResetToken();
        $pass_reset->email = $request->input('email');
        $pass_reset->token = $token;
        $pass_reset->created_at = now()->format('Y-m-d H:i:s');

        try{
            $pass_reset->save();

            // Combine token with 'resetpasswordlink'
            $resetLink = url('http://127.0.0.1:8000/resetpasswordlink/' . $token);

            // get user object with retreived email
            $user = User::where('email',$request->input('email'))->first();

            $user->notify(new EmailNotification($resetLink));

            return response()->json(['message' => 'Email sent', 'status'=>200],200);
        }catch(QueryException $ex){
            return response()->json(['message' => 'Failed', 'status'=>500],500);
        }
        
    }

    // this function handles new password update in user table

    public function changePassword(Request $request){
        // validate input parameters
        $request->validate([
            'password' => 'required|max:255',
            'confirmpassword' => 'required|max:255',
            'token' => 'required|exists:password_reset_tokens'
        ]);

        $newPassword = bcrypt($request->input('password'));

        try{

            // get email address related to input token
            $emailAddr = PasswordResetToken::where('token',$request->input('token'))->first();;

            if(!$emailAddr){
                // valid record with email not found for given token. return error response
                return response()->json(['message' => 'Valid record not found', 'status'=>204], 204);
            }            
            // get user object with retreived email
            $user = User::where('email',$emailAddr->email)->first();

            $user->password = $newPassword;
            $user->save();
            
            $this->destroyResetToken($emailAddr);
            return response()->json(['message' => 'Password changed successfully','status'=>200], 200);

        }catch(QueryException $ex){
            return response()->json(['message' => 'Error! DB error occured', 'status'=>500], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyResetToken(PasswordResetToken $token ){
        $token->delete();
        
        return response()->json(['message' => 'Record deleted successfully']);
    }
}
