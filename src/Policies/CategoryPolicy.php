<?php

namespace Blublog\Blublog\Policies;
use App\User;
use Blublog\Blublog\Models\BlublogUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->view_categories){
            return true;
        }
        return false;
    }

    public function create(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->create_categories){
            return true;
        }
        return false;
    }

    public function update(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->update_categories){
            return true;
        }
        return false;
    }

    public function delete(User $user)
    {
        $Blublog_User = BlublogUser::get_user($user);
        if($Blublog_User->user_role->delete_categories){
            return true;
        }
        return false;
    }
}
