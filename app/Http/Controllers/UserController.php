<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $loginAttempt = 0;
    private $initialUserStatus = 'Y';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //User::all()
        return (DB::table('users')->get()->where('user_status','Y'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'f_name' => 'required|max:225',
            'l_name' => 'required|max:225',
            'email' => 'required|unique:users|max:150',
            'password' => 'required|max:255',
            'user_type' => 'required',
        ]);

        $user = new User();

        $user->f_name = $request->input('f_name');
        $user->l_name = $request->input('l_name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->user_type = $request->input('user_type');
        $user->login_attempt = $this->loginAttempt;
        $user->user_status = $this->initialUserStatus;

        $user->save();

        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        $request->validate([
            'f_name' => 'required|max:225',
            'l_name' => 'required|max:225',                       
            'user_type' => 'required',
        ]);

       
        $user->f_name = $request->input('f_name');
        $user->l_name = $request->input('l_name');        
        $user->user_type = $request->input('user_type');
        
        $user->save();

        return $user;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $this->initialUserStatus = 'N';
        $user->user_status = $this->initialUserStatus;

        $user->save();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
