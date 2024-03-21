<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/update-user/{id}',[AuthController::class,'updateUser']);
Route::post('/logout',[AuthController::class,'logout']);

// Route::get('/posts',[PostController::class,'index'])->middleware('auth:api');
// Route::post('/post  ',[PostController::class,'store'])->middleware('auth:api');

// we can group the route that have similar funtionality
Route::group([
    'middleware'=>'auth:api'
],function($router)
    {
        Route::get('/posts',[PostController::class,'index']);
        Route::post('/post  ',[PostController::class,'store']);
        Route::post('/postupdate/{id}',[PostController::class,'update']);
        Route::delete('/postdelete/{id}',[PostController::class,'destroy']);

        // like and dislike
        Route::post('/toggle-like/{postId}',[LikeController::class,'toggleLike']);
        Route::get('/get-Likes/{id}',[LikeController::class,'getLikes']);

        // comment
        Route::get('/comments/{postId}',[CommentController::class,'showPostDetail']);
        Route::post('/comment/{id}',[CommentController::class,'store']);
        Route::post('/commentupdate/{id}',[CommentController::class,'update']);
        Route::delete('/commentdelete/{id}',[CommentController::class,'destroy']);



    }
);







