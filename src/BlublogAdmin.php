<?php
namespace Philip1503\Blublog;

use Closure;
use Philip1503\Blublog\Models\BlublogUser;
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

        $user = BlublogUser::where([
            ['user_id', '=', $request->user()->id],
        ])->first();

        if(isset($user->role)){
            if($user->role === "Administrator"){
                return $next($request);
            }
        }

        return abort(403);
    }
}
