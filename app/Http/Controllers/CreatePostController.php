<?php
namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\LoginAccessRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\postresource;



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
    public function delete(LoginAccessRequest $request)
    {
          $user_id=$request->data[0]->id;//geting user id
          $postid=$request->id; 
          DB::table('comments')->where('post_id',$postid)->delete();
          $data = DB::table('posts')->where('user_id',$user_id)->where('id', $postid)->delete();//query to read post data on the bases of user id 
          return response()->json(['message'=>'Post Deleted']);

    }
    public function update(LoginRequest $request)
    {
            $userid=$request->data[0]->id;//geting user id
            $id=$request->id;
            $path = $request->file('file')->store('post');          
            $updateDetails = [
                'user_id' => $userid,
                'file' => $path,
                'access' => $request->access
            ];
            DB::table('posts')->where('id',$id)->update($updateDetails);

            return response()->json(["messsage" => "Post Updated successfully"]);

    } 
    public function read(LoginAccessRequest $request)
    {
          $id=$request->data[0]->id;//geting user id
          $data = DB::table('posts')->where('user_id', $id)->get();//query to read post data on the bases of user id 
          return new postresource($data);
          //response()->json(['message'=>$data]);
    }
}


