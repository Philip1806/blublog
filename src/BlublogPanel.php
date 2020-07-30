<?php
namespace Blublog\Blublog;

use Closure;
use Blublog\Blublog\Models\BlublogUser;

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
        if($request->user()){
            $user = BlublogUser::where([
                ['user_id', '=', $request->user()->id],
            ])->first();
            if($user){
                return $next($request);
            }
            return abort(403);
        }

        return abort(404);
    }
}
