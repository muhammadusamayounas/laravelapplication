<?php
namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class CreatePostController extends Controller
{
    public function post(PostRequest $request)
    {
        dd($request->data[0]->id);
      $request->validated();
      $key=$request->access_token;     
      $data = DB::table('users')->where('remember_token', $key)->get();//query to check token is present or not
      $wordCount = count($data);

      if($wordCount > 0)
      {
          $id=$data[0]->id;//geting user id
          $post = new post;
          $path = $request->file('file')->store('post');
          $post->user_id=$id;
          $post->file = $path;
          $post->access=$request->access;
          $post->save();
          return response()->json(['message'=>'Post Created Sucessfully']);
      }
      else
      {
          return response(['message'=>'Token Error Please Login Again']);
      }
    }


}


