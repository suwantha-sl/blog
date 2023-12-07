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
        // Executes sp_GetIndividualComments stored procedure and retrieve comments
		// Parameters for the stored procedure are user id, user type
		// If logged in user is Super Admin the procedure will return all comments.
		// If logged in user is Blog Admin the procedure will return comments only for his blogs
        
		// check for authorized access

		if(Auth::check()){
		  try{
			  $receivedComments = DB::select('CALL sp_GetIndividualComments('.Auth::user()->id.',\''.Auth::user()->user_type.'\')');
			  return response()->json(['reccomments'=> $receivedComments, 'status'=> 200],200);	// ok
		  }catch(QueryException $ex){
			  return response()->json(['message'=>'Error! database error', 'status'=> 500],500); // sql excemption error
		  }		
		}else{
		  return response()->json(['message'=>'unauthorised user', 'status'=> 401],401); // unauthorised access	
		}
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
        ],
        [
            'ori_comment.required' => 'Comment cannot be empty.'
        ]);

        $commentor = Auth::user()->id; // Get the logedin user id;

        $userComment = new Comment();

        $userComment->ori_comment = $request->ori_comment;
        $userComment->blog_id = $request->blog_id;
        $userComment->mod_comment = $request->ori_comment;
        $userComment->comment_status = $this->commentIniStatus;
        $userComment->comment_by = $commentor;

        try{
            $userComment->save();
            return response()->json(['message'=>'Comment saved successfully', 'status'=> 201]);
        }catch(QueryException $ex){
            return response()->json(['message' => 'Failed to save comment', 'status' => 500]);
        }        
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
        
        $comment->mod_comment = $request->mod_comment;
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
        $comment->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Call sp_GetBlogComments stored procedure to retrieve comments for specific blog post.
     */
    public function getBlogComments($blogId){
        // check for authorized action
        if(Auth::check()){
            try{
                $commentList = DB::select('CALL sp_GetBlogComments('.$blogId.')');
                return response()->json(['comments'=>$commentList, 'status'=>200],200);
            }catch(QueryException $ex){
                return response()->json(['message'=>'Error!', 'status'=> 500],500);
            }
            
        }else{
            return response()->json(['message'=>'unauthorized user', 'status'=> 401],401); // unauthorized access
        }
        
    }
}
