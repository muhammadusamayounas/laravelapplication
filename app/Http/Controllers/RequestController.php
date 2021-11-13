<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FriendRequest;

class RequestController extends Controller
{  
    function addFriend(FriendRequest $request)
    {
            $request->validated();
            
            $user = new Friend;
            $access_token = $user->token = $request->input('access_token');
            $email = $user->email = $request->input('email');
           
            if(!empty($access_token))
            {
                $requestsender = DB::table('users')->where(['remember_token' => $access_token])->get();
                $requestreceiver = DB::table('users')->where(['email' => $email])->get();
                $wordcount1 = count($requestsender);//person is making request
                $wordcount2 = count($requestreceiver);//person which you are adding as friend   
                $requestreceiver_verify = $requestreceiver[0]->email_verified_at;
                $id1 = $requestsender[0]->id;
  
                $id2 = $requestreceiver[0]->id;
                $receivername = $requestreceiver[0]->name;
                $user3 = DB::table('friends')->where(['user1_id' => $id1, 'user2_id' => $id2])->get();
                $wordcount3 = count($user3);

                if($wordcount3 == 0)
                {
                    if($wordcount1 > 0 && $wordcount2 > 0)
                    {
                        if(!empty($requestreceiver_verify))
                        {
                            if($id1 != $id2)
                            {   
                                $values = array('user1_id' => $id1, 'user2_id' => $id2);
                                DB::table('friends')->insert($values);
                
                                return response(['Message' => 'Congrats '.$receivername.' is your friend']);
                            }
                            else
                            {
                                return response(['Message' => 'You cannot add yourself as a friend.']);   
                            }                            
                        }       
                        else
                        {
                            return response(['Message' => 'Account not found']);                           
                        } 
                    }
                    else
                    {
                        return response(['Message' => 'Error']);
                    }
                }
                else
                {
                    return response(['Message' => 'Alread your Friend you cannot send him a friend request']);
                }
            }
            else
            {
                return response(['Message' => 'Login Account Again']);
            }
        
    }
}
