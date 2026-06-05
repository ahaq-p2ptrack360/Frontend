<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Passport\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SsoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cookie se token uthao
     // Cookie se token uthao
     $token = $request->cookie('sso_token'); // ya jis naam se token store kar rahe ho

     if ($token) {
         // Token database me check karo
         $user = User::where('sso_token',$token)
             ->first();
    
             if ($user) {
             
                // User ko login kara do without password
                Auth::login($user);
            }
         
        }
        return $next($request);
    }
}
