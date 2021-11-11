<?php

namespace App\Http\Controllers;

use App\Models\post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DeletePostController extends Controller
{

    public function delete(Request $request)
    {
      $key=$request->access_token;     
      $data = DB::table('users')->where('remember_token', $key)->get();//query to check token is present or not
      $wordCount = count($data);

      if($wordCount > 0)
      {
          $postid=$request->id; 
          $data = DB::table('posts')->where('id', $postid)->delete();//query to read post data on the bases of user id 
          return response()->json(['message'=>'Post Deleted']);
      }
      else
      {
          return response(['message'=>'Token Error Please Login Again']);
      }
    }
}
