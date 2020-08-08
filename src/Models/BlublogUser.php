<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Role;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Comment;

use Auth;

class BlublogUser extends Model
{
    protected $table = 'blublog_users';
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function user_role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
    public function files()
    {
        return $this->hasMany(File::class, 'user_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }
    public static function get_user($user)
    {
        $Blublog_User = BlublogUser::where([
            ['user_id', '=', $user->id],
        ])->first();
        if (!$Blublog_User) {
            abort(403);
        }
        return $Blublog_User;
    }
    public static function check_access($action, $resource)
    {
        $user = Auth::user();
        if (!$user->can($action, $resource)) {
            Log::add($user, "alert", __('blublog.403'));
            abort(403);
        }
        return true;
    }
    public static function add($user, $role_id = 3)
    {
        $Blublog_User = new BlublogUser;
        $Blublog_User->user_id = $user->id;
        $Blublog_User->name = $user->name;
        $Blublog_User->email = $user->email;
        $Blublog_User->role_id = $role_id;
        $Blublog_User->save();
        return true;
    }
}
