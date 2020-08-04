<?php

namespace Blublog\Blublog\Policies;
use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Carbon\Carbon;

class TagPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->create_tags){
            return true;
        }
        return false;
    }
    public function update(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->update_all_tags){
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
    public function delete(User $user, $tag)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->delete_all_tags){
            return true;
        }
        return false;
    }
}
