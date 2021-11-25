<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAccessRequest;
use App\Models\post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\postresource;
class ReadPostController extends Controller
{

    public function read(LoginAccessRequest $request)
    {
          $id=$request->data[0]->id;//geting user id
          $data = DB::table('posts')->where('user_id', $id)->get();//query to read post data on the bases of user id 
          return new postresource($data);
          //response()->json(['message'=>$data]);
    }

}
