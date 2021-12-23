<?php

namespace App\Http\Middleware;

use App\Accounts;
use Closure;
use Illuminate\Support\Facades\Auth;

class ProfileCheck
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
        if(Auth::check()){
            $account = Accounts::where('user_id',auth()->user()->id)->first();

            if($account->fullname!='' || $account->birth_place!='' || $account->birth_date!='' ||$account->contact!='' ||$account->address!=''){
                return $next($request);
            }
            toast('You need to fill your account information first','warning');
            return redirect()->route('profile.account');
        }
        return redirect()->route('login');
    }
}
