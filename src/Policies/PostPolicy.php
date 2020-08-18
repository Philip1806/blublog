<?php

namespace Blublog\Blublog\Policies;

use Blublog\Blublog\Models\Post;
use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->is_admin) {
            return true;
        }
    }
    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function view(User $user, Post $post)
    {
        if ($post->status == "private" and  $user->id != $post->user_id) {
            return false;
        }
        return true;
    }

    public function create(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->create_posts) {
            return true;
        }
        return false;
    }
    public function rating(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->control_post_rating) {
            return true;
        }
        return false;
    }
    public function view_stats(User $user, Post $post)
    {
        $Blublog_User = BlublogUser::get_user($user);

        if ($Blublog_User->user_role->view_stats_all_posts) {
            return true;
        }
        if (blublog_get_user(1) == $post->user_id and $Blublog_User->user_role->view_stats_own_posts) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->update_all_posts) {
            return true;
        }
        if (blublog_get_user(1) == $post->user_id and $Blublog_User->user_role->update_own_posts) {
            return true;
        }
        return false;
    }

    public function delete(User $user, Post $post)
    {
        $Blublog_User = BlublogUser::get_user($user);

        if ($Blublog_User->user_role->delete_all_posts) {
            return true;
        }
        if (blublog_get_user(1) == $post->user_id and $Blublog_User->user_role->delete_own_posts) {
            return true;
        }
        return false;
    }
}
