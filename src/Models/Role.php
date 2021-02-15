<?php

namespace Blublog\Blublog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\RolePermission;

class Role extends Model
{
    protected $table = 'blublog_roles';
    protected $guarded = [];
    protected $with = ['permissions'];
    public $timestamps = false;
    public function users()
    {
        return $this->belongsToMany(User::class, 'blublog_roles_users', 'user_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(RolePermission::class, 'blublog_roles_permissions', 'role_id', 'permission_id');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function changePermission($permissionToChange)
    {
        foreach ($this->permissions as $permission) {
            if ($permission->permission == $permissionToChange) {
                if ($permission->value) {
                    $permission->value = 0;
                } else {
                    $permission->value = 1;
                }
                $permission->save();
            }
        }
        return true;
    }
    public function havePermission($check)
    {
        foreach ($this->permissions as $permission) {
            if ($permission->permission == $check) {
                if ($permission->value) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    public function permissionsBySections()
    {
        $permissions = $this->permissions->sortBy([
            ['section', 'asc']
        ]);
        $sections = array();
        for ($i = 0; $i < $permissions->last()->section; $i++) {
            $options = array();
            foreach ($permissions as $permission) {
                if ($permission->section == $i) {
                    array_push($options, $permission);
                }
            }
            array_push($sections, $options);
        }


        return $sections;
    }
}
