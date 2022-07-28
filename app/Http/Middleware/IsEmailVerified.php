<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsEmailVerified
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
        // check for email verification
        if(is_null($request->user()->email_verified_at)) {
            return response()->json([
                'message' => 'Email address must be verified'
            ], 403);
        }

        return $next($request);
    }
}