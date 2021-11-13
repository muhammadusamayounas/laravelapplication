<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class CommentController extends Controller
{
    public function createComment(Request $request)
    {
      $key=$request->access_token;
      $comment=$request->comment; 
      $post_id=$request->post_id;
      $data = DB::table('users')->where('remember_token', $key)->get();
      $wordCount = count($data);
      if($wordCount > 0)
      {
        $id=$data[0]->id;//geting user id
        $comments = new Comment;
        $path = $request->file('file')->store('post');
        $comments->comment=$comment;
        $comments->post_id=$post_id;
        $comments->user_id=$id;
        $comments->file = $path;
        
        $comments->save();
        return response()->json(['message'=>'Commented added']);

      } 
   }

//    public function updateComment(Request $request)
//    {

//         $key=$request->token;
//         $data=DB::table('users')->where('remember_token',$key)->get();
//         $count=count($data);
//         if($count>0)
//         {
//             $userid=$data[0]->id;
//             $id=$request->id;
//             $path = $request->file('file')->store('post');          
//             $updateDetails = [
//                 'user_id' => $userid,
//                 'file' => $path,
//                 'access' => $request->access
//             ];
//             DB::table('posts')->where('id',$id)->update($updateDetails);

//             return response()->json(["messsage" => "Post Updated successfully"]);
//         }

//         else{

//             return response(['message'=>'Token Error Please Login Again']);

//         }
//     }
   

}
