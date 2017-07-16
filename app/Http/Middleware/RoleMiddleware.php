<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/admin');
        }
        if (! $request->user()->hasRole($role)) {
            return redirect('/admin');
            //return response('Unauthorized.', 401);
        }
        return $next($request);
    }
}
