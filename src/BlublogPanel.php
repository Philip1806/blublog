<?php

namespace Blublog\Blublog;

use Closure;
use Illuminate\Support\Facades\Auth;

class BlublogPanel
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
        if (Auth::user()->blublogRoles->count()) {
            return $next($request);
        }
        return abort(403);
    }
}
