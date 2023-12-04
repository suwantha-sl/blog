<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    private $initialArticleStatus = "DRAFT"; // initial status of the article set to DRAFT when saving new blog post
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        //
        //return (BlogPost::all()); 
        return (DB::table('blog_posts')->get());
        /*if(Auth::check()){
            return (DB::table('blog_posts')->get());
        }else{
            return response()->json(['message'=>'unauthorized user', 'status'=> 401]);
        }    */   
        
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
    public function update(Request $request, BlogPost $blogPost)
    {        
        // validate user inputs
        $request->validate([
            'article_title' => 'required|string|max:225',
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
            $author = Auth::user()->id;            

            $blogPost->article_title = $request->article_title;
            $blogPost->article_category = $request->article_category;
            $blogPost->article_content = $request->article_content;            
            $blogPost->article_status = $this->initialArticleStatus;
            $blogPost->author = $author;

            // check image file exists
            if($request->hasFile('article_cover')){
                $coverImage = $request->file('article_cover');
                $coverImageName = time().$coverImage->getClientOriginalName();                
                $coverImagePath = $coverImage->storeAs('images', $coverImageName, 'public');
                $article->article_cover = 'images/' . $coverImageName;
            }else{
                // no need to update cover image
            }

            $blogPost->save();
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
    }
}
