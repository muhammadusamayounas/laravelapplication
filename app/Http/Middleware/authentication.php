<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class authentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
          $key=$request->access_token;     
          $data = DB::table('users')->where('remember_token', $key)->get();//query to check token is present or not
          $wordCount = count($data);   
          if($wordCount > 0)
          {
            return $next($request->merge(["data"=>$data]));
          }
          else
          {
              return response(['message'=>'Token Error Please Login Again']);
          }
       
     }
}
