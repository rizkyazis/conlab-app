<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if(!auth()->check()){
            return redirect()->route('login');
        }

        foreach($roles as $role){
            if(auth()->user()->hasRole($role)){
                return $next($request);
            }
        }
        return redirect()->route('index');
    }
}
