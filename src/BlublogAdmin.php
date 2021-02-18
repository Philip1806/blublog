<?php

namespace Blublog\Blublog;

use Closure;

class BlublogAdmin
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
        if (blublog_is_admin()) {
            return $next($request);
        }
        return abort(403);
    }
}
