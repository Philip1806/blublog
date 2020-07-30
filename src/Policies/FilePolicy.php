<?php

namespace Blublog\Blublog\Policies;
use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    public function upload(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->upload_files){
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
    public function delete(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->delete_all_files){
            return true;
        }
        return false;
    }
}
