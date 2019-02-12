<?php

namespace App\Http\Middleware;

use Closure;

class is_accountant
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
        if(! $request->user()->isAccountant() && ! $request->user()->isAdmin() && ! $request->user()->isSuperadmin())
        {
            return redirect('home');
        }
        return $next($request);
    }
}
