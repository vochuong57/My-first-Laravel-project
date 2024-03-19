<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
//thêm thư viện kiểm tra đăng nhập
use Illuminate\Support\Facades\Auth;

class AuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::id()==null){
            return redirect()->route('auth.admin')->with('error','Vui lòng đăng nhập để sử dụng chức năng này');
        }
        return $next($request);
    }
}
