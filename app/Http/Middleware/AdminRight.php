<?php

namespace App\Http\Middleware;

use Closure;

class AdminRight
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
        if($request->user()->is_admin != 1) {
            return redirect('login');
        }
        return $next($request);
    }
}
