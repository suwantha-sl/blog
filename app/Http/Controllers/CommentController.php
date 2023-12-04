<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    private $commentIniStatus = "DRAFT";
    private $commentPubStatus = "PUBLISHED";
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return (DB::table('comments')->get());
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
        // validate user inputs
        $request->validate([
            'ori_comment' => 'required',  
            'blog_id' => 'required|integer',                     
        ]);

        $commentor = Auth::user()->id; // Get the logedin user id;

        $userComment = new Comment();

        $userComment->ori_comment = $request->ori_comment;
        $userComment->blog_id = $request->blog_id;
        $userComment->mod_comment = $request->ori_comment;
        $userComment->comment_status = $this->commentIniStatus;
        $userComment->comment_by = $commentor;

        $userComment->save();
        return response()->json(['message'=>'saved successfully', 'status'=> 200]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
       // \Log::info('Show method called with request: ' . json_encode(request()->all()));
    //\Log::info('Blog post ID: ' . $comment->id);
        return $comment;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
        $request->validate([
            'mod_comment' => 'required',  
                                
        ]);       
        
        $comment->mod_comment = $request->ori_comment;
        $comment->comment_status = $this->commentPubStatus;        

        $comment->save();
        return response()->json(['message'=>'saved successfully', 'status'=> 200]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
