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
          $id=$request->data[0]->id;//geting user id
          $post = new post;
          $path = $request->file('file')->store('post');
          $post->user_id=$id;
          $post->file = $path;
          $post->access=$request->access;
          $post->save();
          return response()->json(['message'=>'Post Created Sucessfully']);
    }
}


