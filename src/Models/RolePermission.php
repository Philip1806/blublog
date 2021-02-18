<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Role;

class RolePermission extends Model
{
    protected $table = 'blublog_permissions';
    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'blublog_roles_permissions', 'role_id', 'role_id');
    }
}
