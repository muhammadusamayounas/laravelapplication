<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\testmail;

class signuplogin extends Controller
{
    public function __construct()
    {
       $this->middleware('auth:api',['except'=>['login','register','welcome','logout']]) ;
    }
    public function register(Request $request)//sign_up
    {

       $validator=Validator::make($request->all(),[
        'name'=>'required',
        'email'=>'required|string|email|unique:users',
        'password'=>'required|string|confirmed|min:8',
        'gender'=>'required'
     ]);

     if($validator->fails())
     {
        return response()->json($validator->errors()->toJson(),400);
     }
         $user=User::create(array_merge(
             $validator->validated(),
             ['password'=>bcrypt($request->password)]
         ));
         
        $email=$request->input('email');//
        $user_token=rand(10,500);//
        DB::table('users')->where('email',$email)->update(['verify_token'=>$user_token]);//

        $this->sendmail($email,$user_token);

         return response()->json([
             'message'=>'User Successfully registered',
             'User'=>$user
         ],200);         
    }
    public function sendmail($email,$user_token)
    { 

        $details=[
            'title'=>'You are successfully sign up to our SocialApp',
             'body'=>'http://127.0.0.1:8000/api/welcome'.'/'.$email.'/'.$user_token];

        Mail::to($email)->send(new testmail($details));
        return "email send";
    }

    public function login(Request $request)//login 

     {
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|string|min:8'
        ]);

        $email=$request->input('email');//
        
        if($validator->fails())
        {
            return response()->json($validator->errors(),422);
        }
        if(!$token=Auth::attempt($validator->validated()))
        {
            return response()->json(['error'=>'Unauthorized'],401);
        }
        else{
            DB::table('users')->where('email',$email)->update(['status'=>'1']);//
            DB::table('users')->where('email',$email)->update(['remember_token'=>$token]);//
            return response()->json([
                'access_token'=>$token
            ]);
        }
     }
     //public function createNewToken($token)
     //{
       //   return response()->json([ 'access_token'=>$token]);
     //}
     public function welcome($email, $user_token)//email verify
    {
        $sql=DB::table('users')->where('email', $email)->where('email_verified_at', NULL)->get();
        $wordCount = count($sql);
        if($wordCount > 0)
        {

          $data = DB::table('users')->where('email', $email)->where('verify_token', $user_token)->get();
          $wordCount = count($data);
          if($wordCount > 0)
          {
              DB::table('users')->where('email', $email)->update(['email_verified_at'=> now()]);
              DB::table('users')->where('email', $email)->update(['updated_at'=> now()]);
              return response(['Result'=>'Your Email has been Verified']);
          }
         else
          {
               return response(['Result' => 'Something went wrong in Welcome To Login Api..!!!']);
          }
       }
       else
       {
         return response(['Result'=>'Your Email has been Already Verified']);
       }
    }
    public function logout(Request $request)
    {
      $key=$request->access_token;
      
      $data = DB::table('users')->where('remember_token', $key)->get();
      $wordCount = count($data);
      if($wordCount > 0)
      {
         $userid=$data[0]->id;
         $updateDetails = [
            'remember_token' => null,
            'status' =>0
        ];
        DB::table('users')->where('id',$userid)->update($updateDetails);  
        return response(['message'=>'Logout']);  
      }
      else
      {
        return response(['message'=>'Token Error Please Login Again']);
    }
    }
}




