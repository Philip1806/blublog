<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Exceptions\AdminRoleChangeException;
use Livewire\Component;

class BlublogEditRoles extends Component
{

    public $role;
    public $message = '';

    public function render()
    {
        return view('blublog::livewire.users.blublog-edit-roles-perm');
    }
    public function changePermission($permission)
    {
        try {
            $this->role->changePermission($permission);
            $this->message = 'Permission ' . $permission . ' was changed.';
        } catch (AdminRoleChangeException $e) {
            $this->message = 'Admin role permissions can not be changed.';
            $e->report();
        }
    }
}
