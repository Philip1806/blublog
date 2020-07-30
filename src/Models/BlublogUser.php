<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Role;
use Auth;

class BlublogUser extends Model
{
    protected $table = 'blublog_users';
    public function user_role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public static function get_user($user)
    {
        $Blublog_User = BlublogUser::where([
            ['user_id', '=', $user->id],
        ])->first();
        if(!$Blublog_User){
            abort(403);
        }
        return $Blublog_User;
    }
    public static function check_access($action,$resource)
    {
        $user = Auth::user();
        if (!$user->can($action, $resource)) {
            Log::add($user, "alert", __('blublog.403') );
            abort(403);
        }
        return true;
    }
}
