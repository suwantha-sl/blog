<?php
// This BlogPostController handles functions (new blog creation, edit, delete and retrieve blog details) related to blogs.
// Developed by Ishan
// Developed date: 2023-Nov-26
// Last Updated: 2023-Dec-04

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogPostController extends Controller
{
    private $initialArticleStatus = "PUBLISHED"; // initial status of the article set to PUBLISHED when saving new blog post
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        //
        //return (BlogPost::all()); 
        //return (DB::table('blog_posts')->get());
        // check authorized user access
        if(Auth::check()){
            $blogs = BlogPost::orderBy('created_at', 'desc')->get()->where('article_status','<>','DELETED'); // fetch data in decending order based on created date
            return response()->json(['blogs' => $blogs, 'status'=>200]);
        }else{
            return response()->json(['message'=>'unauthorized user', 'status'=> 401],401); // unauthorized access
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
     * Store a newly blog post to database.
     */
    public function store(Request $request)
    {
        // validate user inputs
        $request->validate([
            'article_title' => 'required|max:225',
            'article_category' => 'required|string|max:225',            
        ],
        [
            'article_title.required' => 'Blog Title cannot be empty',
            'article_title.max' => 'Blog Title cannot exceed 225 characters',
            'article_category.required' => 'Blog Category cannot be empty',
            'article_category.max' => 'Blog Category cannot exceed 225 characters',
            'article_category.string' => 'Blog Category should be a string value'
        ]
        );

        //if(Auth::check()){
            $author = Auth::user()->id;//auth('sanctum')->user()->id;

            $article = new BlogPost();

            $article->article_title = $request->article_title;
            $article->article_category = $request->article_category;
            $article->article_content = $request->article_content;            
            $article->article_status = $this->initialArticleStatus;
            $article->author = $author;

            // check image file exists
            if($request->hasFile('article_cover')){
                $coverImage = $request->file('article_cover');
                $coverImageName = time().$coverImage->getClientOriginalName();                
                $coverImagePath = $coverImage->storeAs('images', $coverImageName, 'public');
                $article->article_cover = 'images/' . $coverImageName;
            }else{
                $article->article_cover = "-";
            }

            $article->save();
            return response()->json(['message'=>'saved successfully', 'status'=> 200]);
        /*}else{
            return response()->json(['message'=>'unauthorized user', 'status'=> 401]);
        }*/
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blog)
    {
        //
        //var_dump($blogPost->article_title);
        //\Log::info('Show method called with request: ' . json_encode(request()->all()));
    //\Log::info('Blog post ID: ' . $blog->id);
        return $blog;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blogPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blog)
    {        
        \Log::info('Received data:', $request->json()->all());
        // Retrieve the JSON payload
        $requestData = $request->json()->all();

        // Validate the incoming data
        $validator = Validator::make($requestData, [
            'article_title' => 'required|string|max:225',
            'article_category' => 'required|string|max:225',
            'article_content' => 'required|string',
        ], [
            'article_title.required' => 'Article Title cannot be empty',
            'article_title.string' => 'Article Title should be a string',
            'article_title.max' => 'Article Title cannot exceed 225 characters',
            'article_category.required' => 'Article Category cannot be empty',
            'article_category.string' => 'Article Category should be a string',
            'article_category.max' => 'Article Category cannot exceed 225 characters',
            'article_content.required' => 'Article Content cannot be empty',
            'article_content.string' => 'Article Content should be a string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

		if (!$blog->exists) {
        return response()->json(['error' => 'Blog post not found'], 404);
    }
        // validate user inputs
        /*$request->validate([
            'article_title' => 'required|string|max:225',
            'article_category' => 'required|string|max:225',
            'article_cover' => 'nullable|file|max:2048'            
        ],
        [
            'article_title.required' => 'Blog Title cannot be empty',
            'article_title.max' => 'Blog Title cannot exceed 225 characters',
            'article_category.required' => 'Blog Category cannot be empty',
            'article_category.max' => 'Blog Category cannot exceed 225 characters',
            'article_category.string' => 'Blog Category should be a string value',
            'article_cover.max' => 'File size cannot exceed 2MB'
        ]
        );*/

        //if(Auth::check()){
            $author = Auth::user()->id;            

            $blog->article_title = $requestData['article_title'];
            $blog->article_category = $requestData['article_category'];
            $blog->article_content = $requestData['article_content'];            
            $blog->article_status = $this->initialArticleStatus;
            $blog->author = $author;

            // check image file exists
            /*if($request->hasFile('article_cover')){
                $coverImage = $request->file('article_cover');
                $coverImageName = time().$coverImage->getClientOriginalName();                
                $coverImagePath = $coverImage->storeAs('images', $coverImageName, 'public');
                $blogPost->article_cover = 'images/' . $coverImageName;
            }else{
                // no need to update cover image
            }*/
            // Check if the 'article_cover' key exists in the request data
            if (array_key_exists('article_cover', $requestData)) {
                $coverImage = $requestData['article_cover'];

                // Your existing code for handling the file
                $coverImageName = time() . $coverImage->getClientOriginalName();                
                $coverImagePath = $coverImage->storeAs('images', $coverImageName, 'public');
                $blog->article_cover = 'images/' . $coverImageName;
            }

            $blog->save();
            return response()->json(['message'=>'updated successfully', 'status'=> 200]);
        /*}else{
            return response()->json(['message'=>'unauthorized user', 'status'=> 401]);
        }*/
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost)
    {
        //
        try{
            $blogPost->article_status = 'DELETED';
            $blogPost->save();
            return response()->json(['message' => 'Product deleted successfully']);
        }catch(QueryException $ex){
            \Log::error('Error deleting blog post: ' . $ex->getMessage());
        }
        

        
    }
}
