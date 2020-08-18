<?php

namespace Blublog\Blublog\Policies;

use Blublog\Blublog\Models\Comment;
use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
     * Determine whether the user can view the comment.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function approve(User $user, Comment $comment)
    {
        $Blublog_User = BlublogUser::get_user($user);

        if ($Blublog_User->user_role->approve_all_comments) {
            return true;
        }
        if (blublog_get_user(1) == $comment->post->user_id and $Blublog_User->user_role->approve_comments_from_own_posts) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the comment.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        $Blublog_User = BlublogUser::get_user($user);

        if ($Blublog_User->user_role->update_all_comments) {
            return true;
        }
        if (blublog_get_user(1) == $comment->post->user_id and $Blublog_User->user_role->moderate_comments_from_own_posts) {
            return true;
        }
        if ($Blublog_User->user_role->moderate_own_comments and blublog_get_user(1) == $comment->author_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        $Blublog_User = BlublogUser::get_user($user);

        if ($Blublog_User->user_role->delete_all_comments) {
            return true;
        }
        if (blublog_get_user(1) == $comment->post->user_id and $Blublog_User->user_role->moderate_comments_from_own_posts) {
            return true;
        }
        if ($Blublog_User->user_role->moderate_own_comments and blublog_get_user(1) == $comment->author_id) {
            return true;
        }
        return false;
    }

    public function ban(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->ban_user_from_commenting) {
            return true;
        }
        return false;
    }
}
