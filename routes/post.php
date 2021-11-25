<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CreatePostController;
use App\Http\Controllers\ReadPostController;
use App\Http\Controllers\DeletePostController;
use App\Http\Controllers\UpdatePostController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RequestController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware'=>'customauth'],function($router)
{
    Route::post('/create_post',[CreatePostController::class,'post']);
    Route::post('/read_post',[CreatePostController::class,'read']);
    Route::post('/delete_post',[CreatePostController::class,'delete']);
    Route::post('/update_post',[CreatePostController::class,'update']);

});


