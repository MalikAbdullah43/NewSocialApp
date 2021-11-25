<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostDelete
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
        $user = $request->user_data->id;
        $id = $request->pid;
        $allow = DB::table('posts')->where(['id' => $id, 'user_id' => $user])->first();
        if (!empty($allow))
            return $next($request);
        else
            return response()->error(401);
    }
}
