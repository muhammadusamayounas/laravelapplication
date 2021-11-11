<?php

namespace App\Http\Controllers;

use App\Models\post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReadPostController extends Controller
{
    public function read(Request $request)
    {
      $key=$request->access_token;     
      $data = DB::table('users')->where('remember_token', $key)->get();//query to check token is present or not
      $wordCount = count($data);

      if($wordCount > 0)
      {
          $id=$data[0]->id;//geting user id
          $data = DB::table('posts')->where('user_id', $id)->get();//query to read post data on the bases of user id 
          return response()->json(['message'=>$data]);
      }
      else
      {
          return response(['message'=>'Token Error Please Login Again']);
      }
    }

}
