<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAccessRequest;
use App\Models\post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function showprofile(LoginAccessRequest $request)//function return the profile information of the user
    {
          $id=$request->data[0]->id;//geting user id
          $data = DB::table('users')->where('id', $id)->get();//query to read post data on the bases of user id 
          return response()->json(['message'=>$data]);
    }
    
}
