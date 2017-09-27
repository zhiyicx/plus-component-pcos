<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Middleware;

use Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {   
        // 跳转登录
        if ( !Session::get('token') ) {
            return redirect(route('pc:login'));
        }
        
        return $next($request);
    }
}
