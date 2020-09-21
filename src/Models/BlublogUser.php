<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Role;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Exceptions\BlublogNoAccess;
use App\User;
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
    public static function get_permissions()
    {
        return array(
            'name', 'descr', 'create_posts', 'update_own_posts', 'delete_own_posts', 'view_stats_own_posts',
            'update_all_posts', 'delete_all_posts', 'view_stats_all_posts', 'posts_wait_for_approve', 'control_post_rating',
            'create_comments', 'moderate_comments_from_own_posts', 'moderate_own_comments', 'update_all_comments',
            'delete_all_comments', 'approve_comments_from_own_posts', 'approve_all_comments', 'ban_user_from_commenting',
            'create_tags', 'moderate_tags_created_within_set_time', 'update_all_tags', 'delete_all_tags',
            'view_categories', 'create_categories', 'update_categories', 'delete_categories', 'use_menu', 'view_pages',
            'create_pages', 'update_pages', 'delete_pages', 'create_users', 'update_own_user', 'update_all_users',
            'delete_users', 'change_settings', 'upload_files', 'delete_own_files', 'delete_all_files',
            'is_mod', 'is_admin'
        );
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
            throw new BlublogNoAccess;
        }
        return true;
    }
    public static function create_new($request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
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
