<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class adminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::guard('admin')->check()){
//                    dump("---".Route::current()->uri());

                    if(!session('abc')){
                        session(['abc'=>1]);
                    }else{
                        session(['abc'=>session('abc')+1]);
                    }
//                    dump(session('abc'));
            return $next($request);
        }else{
            return redirect('/login');
        }
    }
}
