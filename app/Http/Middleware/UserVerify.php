<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserVerify
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
        $jwt = $request->bearerToken();
        if(!empty($jwt)){
        $data = DB::table('users')
        ->where('remember_token',$jwt)->where('updated_at','>=',now())->first();

        if (!isset($data->id)){
            return response()->error(401);
        }
        else{
        return $next($request->merge(["user_data"=>$data]));}
        }
        else{
            return response()->error(401);
        }
        }
}
