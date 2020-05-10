<?php
namespace Blublog\Blublog;

use Closure;
use Blublog\Blublog\Models\BlublogUser;
class BlublogMod
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
            if($user->role === "Administrator" or $user->role === "Moderator" ){
                return $next($request);
            }
        }

        return abort(403);
    }
}
