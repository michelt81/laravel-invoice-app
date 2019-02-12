<?php

namespace App\Http\Middleware;

use Closure;

class is_employee
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
        if(! $request->user()->isEmployee() && ! $request->user()->isAdmin() && ! $request->user()->isSuperadmin())
        {
            return redirect('home');
        }
        return $next($request);
    }
}
