<?php

namespace Blublog\Blublog\Models;

use App\Models\User;
use Blublog\Blublog\Exceptions\AdminRoleChangeException;
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
    /**
     * Adds permission to the role
     *
     * @param array $data Info for the new permission
     * @return void
     */
    public function addPermission(array $data): void
    {
        $newPermission = new RolePermission;
        $newPermission->permission = $data['permission'];
        $newPermission->permission_descr = $data['permission_descr'];
        $newPermission->section = $data['section'];
        $newPermission->value = $data['value'];
        $newPermission->save();
        $this->permissions()->syncWithoutDetaching($newPermission->id);
        $this->save();
    }

    /**
     * Toggle data value of permission 
     *
     * @param string $permissionToChange
     * @return boolean
     */
    public function changePermission(string $permissionToChange): bool
    {
        if ($this->id == 1) {
            throw new AdminRoleChangeException();
        }
        foreach ($this->permissions as $permission) {
            if ($permission->permission == $permissionToChange) {
                $permission->changePermission();
                break;
            }
        }
        return true;
    }

    /**
     * Check if the role have given permission
     *
     * @param string $permissionToCheck
     * @return boolean
     */
    public function havePermission(string $permissionToCheck): bool
    {
        foreach ($this->permissions as $permission) {
            if ($permission->permission == $permissionToCheck) {
                if ($permission->value) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * Returns all permissions in array by their seciton.
     *
     * @return array
     */
    public function permissionsBySections(): array
    {
        $permissions = $this->permissions->sortBy([
            ['section', 'asc']
        ]);
        $sections = array();

        for ($i = 1; $i < $permissions->last()->section + 1; $i++) {
            $options = $this->arrayOfOptionsBySection($i);
            array_push($sections, $options);
        }

        return $sections;
    }

    /**
     * Array of options
     *
     * @param integer $id
     * @return array
     */
    protected function arrayOfOptionsBySection(int $id): array
    {
        $options = array();
        foreach ($this->permissions as $permission) {
            if ($permission->section == $id) {
                array_push($options, $permission);
            }
        }
        return $options;
    }
}
