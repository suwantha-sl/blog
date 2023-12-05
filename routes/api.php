<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function (){
    Route::middleware('auth:sanctum')->get('/blogs/{blog}', 'BlogPostController@show');
    Route::resource('blogs',BlogPostController::class); 
    Route::resource('comments',CommentController::class);    
    Route::middleware('auth:sanctum')->get('/logout',[AuthController::class,'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware('auth:sanctum')->get('/logout',[AuthController::class,'logout']);

Route::post('/userlogin', [AuthController::class, 'login']);

Route::resource('users',UserController::class);

//Route::resource('blogs',BlogPostController::class);