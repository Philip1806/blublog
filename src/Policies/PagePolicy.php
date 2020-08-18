<?php

namespace Blublog\Blublog\Policies;

use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->is_admin) {
            return true;
        }
    }
    public function view(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->view_pages) {
            return true;
        }
        return false;
    }

    public function create(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->create_pages) {
            return true;
        }
        return false;
    }

    public function update(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->update_pages) {
            return true;
        }
        return false;
    }

    public function delete(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if ($Blublog_User->user_role->delete_pages) {
            return true;
        }
        return false;
    }
}
