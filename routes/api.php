<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\signuplogin;
use App\Http\Controllers\create_post;
use App\Http\Controllers\read_post;
use App\Http\Controllers\delete_post;
use App\Http\Controllers\update_post;
use App\Http\Controllers\viewuserdetails;






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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
    

Route::group(['middleware'=>'api','perfix'=>'auth'],function($router){
    
    Route::post('/register',[signuplogin::class,'register']);
    Route::post('/login',[signuplogin::class,'login']);
    Route::get('/welcome/{email}/{verify_email}',[signuplogin::class,'welcome']);
    Route::post('/logout',[signuplogin::class,'logout']);
});

Route::post('/create_post',[create_post::class,'post']);
Route::post('/read_post',[read_post::class,'read']);
Route::post('/delete_post',[delete_post::class,'delete']);
Route::post('/update_post',[update_post::class,'update']);

Route::post('/showprofile',[viewuserdetails::class,'showprofile']);




