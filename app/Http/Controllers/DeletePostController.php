<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAccessRequest;
use App\Models\post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DeletePostController extends Controller
{

    public function delete(LoginAccessRequest $request)
    {
          $user_id=$request->data[0]->id;//geting user id
          $postid=$request->id; 
          DB::table('comments')->where('post_id',$postid)->delete();
          $data = DB::table('posts')->where('user_id',$user_id)->where('id', $postid)->delete();//query to read post data on the bases of user id 
          return response()->json(['message'=>'Post Deleted']);

    }
}
