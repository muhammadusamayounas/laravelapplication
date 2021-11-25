<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\post;
use Illuminate\Support\Facades\DB;

class UpdatePostController extends Controller
{
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
}
