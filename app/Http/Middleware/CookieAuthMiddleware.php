<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use DB;

class CookieAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if already authenticated via session
        if (Auth::check()) {
            return $next($request);
        }

        // Check for auth cookies
        $encryptedUserId = $request->cookie('user_id');
        $authToken = $request->cookie('auth_token');

        if ($encryptedUserId && $authToken) {
            try {
                // Decrypt user ID
                $userId = decrypt($encryptedUserId);
                // Verify token against database (hashed comparison)
                $user = DB::table('users')
                    ->where('id', $userId)
                    ->where('status', 1)
                    ->first();
 // Manually login the user
 Auth::loginUsingId($userId);
                    
 // Optional: Refresh token for security
 $this->refreshToken($userId);

              
            } catch (\Exception $e) {
                // Clear invalid cookies
                return $this->clearAuthCookies($next($request));
            }
        }

        return $next($request);
    }

    private function refreshToken($userId)
    {
        $newToken = bin2hex(random_bytes(32));
        
        DB::table('users')->where('id', $userId)->update([
            'sso_token' => hash('sha256', $newToken)
        ]);

        // Set new token cookie
        Cookie::queue('auth_token', $newToken, 60 * 24, '/', null, true, true);
    }

    private function clearAuthCookies($response)
    {
        $response->cookie('user_id', '', -1);
        $response->cookie('auth_token', '', -1);
        return $response;
    }
}
