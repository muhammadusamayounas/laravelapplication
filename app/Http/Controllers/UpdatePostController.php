<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\post;
use Illuminate\Support\Facades\DB;

class UpdatePostController extends Controller
{
    public function update(Request $request)
    {

        $key=$request->token;
        $data=DB::table('users')->where('remember_token',$key)->get();
        $count=count($data);
        if($count>0)
        {
            $userid=$data[0]->id;
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

        else{

            return response(['message'=>'Token Error Please Login Again']);

        }
    }


    
}
