<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\testmail;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginAccessRequest;

class UserController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth:api',['except'=>['login','register','welcome','logout','readInfo','readComment','seeAllFriend']]) ;
    }
    public function register(SignUpRequest $request)//sign_up
    {
         $user=User::create(array_merge(
             $request->validated(),
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

    public function login(LoginRequest $request)//login 

     {

        $email=$request->input('email');//
        if(!$token=Auth::attempt($request->validated()))
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
    public function logout(LoginAccessRequest $request)
    {
        
      $request->validated();
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

    function forgetPassword(ForgetPasswordRequest $request)
    {

        $request->validated();
        $user = new User;
        $getmail = $user->email = $request->input('email');
        $data = DB::table('users')->where('email', $getmail)->get();
        
        if(count($data) > 0)
        {
            foreach ($data as $key)
            {
                $verfiy =$key->email_verified_at;
            }
            if(!empty($verfiy))
            {
                $verification_code=rand(1000,9999);
                DB::table('users')->where('email', $getmail)->update(['verify_token'=> $verification_code]);
                return response($this->sendNewMail($getmail,$verification_code));
            }
            else{
                return response(['Message'=>'Account is not verified']);
            }
        }
        else{
            return response(['Message'=>'Email doesnot match Please re enter the email address']);
        }
    }
    function sendNewMail($getmail,$verification_code)
    {
        $details=[
            'title'=> 'Forget Password Verification',
            'body'=> 'Your OTP is '. $verification_code . ' Please copy and paste the change Password Api'
        ]; 
        Mail::to($getmail)->send(new testmail($details));
        return response(['Message' => 'An OTP has been sent to '.$getmail.' , Please verify and proceed further.']);
    }
    function userChangePassword(ChangePasswordRequest $request)
    {
        $request->validated();
        $user = new User;
        $getmail = $user->email = $request->input('email');
        $verification_code= $user->verification_code= $request->input('verification_code');
        $password=bcrypt($request->input('password'));
        $data = DB::table('users')->where('email', $getmail)->get();
        $num = count($data);
        
        if($num > 0)
        {
            foreach ($data as $key)
            {
                $getcode=$key->verify_token;
            }
            if($getcode==$verification_code)
            {
                DB::table('users')->where('email', $getmail)->update(['password'=> $password]);
                return response(['Message'=>'Your Password has been updated']);
            }
            else{
                return response(['Message'=>'Otp Does Not Match.']);
            }
        }
        else{
            return response(['Message'=>'Please Enter Valid Mail.']); 
        }
    }
    public function readInfo(LoginAccessRequest $request)//funtion will return the user information and posts by that user
    {
        $request->validated();
        $key=$request->access_token;
        $data = DB::table('users')->where('remember_token', $key)->get();
        $wordCount = count($data);
        if($wordCount > 0)
        {
            $userid=$data[0]->id;
            $sql=User::with("allUserPost")->where('id',$userid)->get();
            return response(['message'=>$sql]);
        }
        else
        {
          return response(['message'=>'Token Error Please Login Again']);
      }
    }
   
     public function readComment(LoginAccessRequest $request)//funtion will return the user information and posts by that user and comments on that post
    {
        $request->validated();
        $key=$request->access_token;
        $data = DB::table('users')->where('remember_token', $key)->get();
        $wordCount = count($data);
        if($wordCount > 0)
        {
            $userid=$data[0]->id;
            $sql=User::with("allUserPost","getComments")->where('id',$userid)->get();
            return response(['message'=>$sql]);
        }
        else
        {
          return response(['message'=>'Token Error Please Login Again']);
      } 
    }
    public function seeAllfriend(LoginAccessRequest $request)//funtion will return the user information and posts by that user
    {
        $request->validated();
        $key=$request->access_token;
        $data = DB::table('users')->where('remember_token', $key)->get();
        $wordCount = count($data);
        if($wordCount > 0)
        {
            $userid=$data[0]->id;
            $sql=User::with("allUserFriend")->where('id',$userid)->get();
            return response(['message'=>$sql]);
        }
        else
        {
          return response(['message'=>'Token Error Please Login Again']);
      }
    }
}
