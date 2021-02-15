<?php

namespace Blublog\Blublog\Livewire;

use Livewire\Component;
use Blublog\Blublog\Models\Role;
use Livewire\WithPagination;


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
        if ($this->role->id == 1) {
            $this->message = 'Admin role permissions can not be changed.';
        } else {
            $this->role->changePermission($permission);
            $this->message = 'Permission ' . $permission . 'was changed.';
        }
    }
}
