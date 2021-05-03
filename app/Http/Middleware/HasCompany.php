<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasCompany
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
        if (auth()->user()->activeCompany() == false) {
            return response()->json(['status' => 'failed', 'message' => 'Please create or join a company first'], 400);
        }

        return $next($request);
    }
}
