<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{  
    function addFriend(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'acccess_token' =>  'required',
            'email'     =>  'required|email',
        ]);

        if($validation->fails())
        {
            return response()->json($validation->errors()->toJson(),400);
        }
        else
        {
            $user = new Friend;
            $access_token = $user->token = $request->input('access_token');
            $email = $user->email = $request->input('email');
           
            if(!empty($access_token))
            {
                $user1 = DB::table('users')->where(['remember_token' => $access_token])->get();
                $user2 = DB::table('users')->where(['email' => $email])->get();
                $wordcount1 = count($user1);
                $wordcount2 = count($user2);    
                $user2_verify = $user2[0]->email_verified_at;
                $id1 = $user1[0]->id;
  
                $id2 = $user2[0]->id;
                // get name of user-2
                $name2 = $user2[0]->name;
    
                // get all data of uers-3 from friends table
                $user3 = DB::table('friends')->where(['user_id1' => $id1, 'user_id2' => $id2])->get();
    
                // get count of all fetch records
                $wordcount3 = count($user3);
    
                // this if is for to check num of rows from user3 variable
                if($wordcount3 == 0)
                {
                    // to check if friend user is email-verified or not
                    if($wordcount1 > 0 && $wordcount2 > 0)
                    {
                        // this if is for to check num of rows from user1 variable  
                        // this if is for to check num of rows from user2 variable  
                        if(!empty($user2_verify))
                        {

                            // add data into friends table    
                            //$values = array('user_id1' => $uid1, 'user_id2' => $uid2);
                            //DB::table('friends')->insert($values);
                            //return response(['Message' => 'Congrats '.$name2.' is your friend now...!!!!']);

                            
                            // user cannot add himself as friend.
                            if($id1 != $id2)
                            {
                                // add data into friends table    
                                $values = array('user_id1' => $id1, 'user_id2' => $id2);
                                DB::table('friends')->insert($values);
                
                                return response(['Message' => 'Congrats '.$name2.' is your friend now...!!!!']);
                            }
                            else
                            {
                                return response(['Message' => 'You cannot add yourself as a friend.']);   
                            }                            
                        }       
                        else
                        {
                            return response(['Message' => 'Friend not Found / Friend is not verified']);                           
                        } 
                    }
                    else
                    {
                        return response(['Message' => 'Friend not Found / Something went wrong with friend.']);
                    }
                }
                else
                {
                    return response(['Message' => 'Alread your Friend. No need to send friend request again.']);
                }
            }
            else
            {
                return response(['Message' => 'Login Account Again / Token expired.']);
            }
        }
    }
}
