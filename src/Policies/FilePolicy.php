<?php

namespace Blublog\Blublog\Policies;

use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->is_admin) {
            return true;
        }
    }
    public function download(User $user, $file)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->is_mod) {
            return true;
        }
        if ($Blublog_User->id == $file->user_id) {
            return true;
        }
        return false;
    }

    public function upload(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->upload_files) {
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
    public function delete(User $user, $file)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->delete_all_files) {
            return true;
        }
        if ($Blublog_User->user_role->delete_own_files and $Blublog_User->id == $file->user_id) {
            return true;
        }
        return false;
    }
}
