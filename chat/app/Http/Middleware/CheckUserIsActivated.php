<?php

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InfyOm\Generator\Utils\ResponseUtil;
use Session;

class CheckUserIsActivated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */

                $user = Auth::user();

      
        return $next($request);
    }
}
