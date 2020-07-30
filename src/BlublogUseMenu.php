<?php
namespace Blublog\Blublog;

use Closure;
use Blublog\Blublog\Models\BlublogUser;
use Auth;

class BlublogUseMenu
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

        $Blublog_User = BlublogUser::where([
            ['user_id', '=', Auth::user()->id],
        ])->first();
        if(!$Blublog_User){
            abort(403);
        }
        if($Blublog_User->user_role->use_menu){
            return $next($request);
        }
        return abort(403);
    }
}
