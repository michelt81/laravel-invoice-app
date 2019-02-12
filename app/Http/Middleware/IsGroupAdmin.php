<?php

namespace App\Http\Middleware;

use Closure;

class IsGroupAdmin
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
        // if not superuser, check if group admin of this group id
        if(! $request->user()->isSuperadmin())
        {
            if (! ($request->route('usergroup') == $request->user()->usergroup_id
                   && $request->user()->isAdmin() )) {
                return redirect('home');
            }
        }
        return $next($request);
    }
}
