<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Exceptions\AdminRoleChangeException;
use Blublog\Blublog\Models\Role;
use Blublog\Blublog\Models\RolePermission;

use Session;

class BlublogRolesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('BlublogAdmin');
    }

    public function roles()
    {

        return view('blublog::panel.users.roles.index')->with('roles', Role::all());
    }
    public function rolesUpdate(Request $request, $role_id)
    {
        if ($role_id == 1) {
            return back();
        }
        $rules = [
            'name' => 'required|max:250',
            'descr' => 'max:250',
        ];
        $this->validate($request, $rules);
        $role = Role::findOrFail($role_id);
        $role->update($request->all());
        Session::flash('success', "Role edited.");
        return redirect()->back();
    }
    public function rolesEdit($role_id)
    {
        if ($role_id == 1) {
            throw new AdminRoleChangeException();
        }
        $role = Role::findOrFail($role_id);
        return view('blublog::panel.users.roles.edit')->with('role', $role);
    }
    public function rolesStore(Request $request)
    {
        $rules = [
            'name' => 'required|max:250',
            'descr' => 'max:250',
        ];
        $this->validate($request, $rules);


        $newRole = new Role;
        $newRole->name = $request->name;
        $newRole->descr = $request->descr;
        $newRole->save();


        $permissions = Role::find(1)->permissions;
        foreach ($permissions as $permission) {
            $newPermission = new RolePermission;

            $newPermission->permission = $permission->permission;
            $newPermission->permission_descr = $permission->permission_descr;
            $newPermission->section = $permission->section;

            if ($request[$permission->permission]) {
                $newPermission->value = 1;
            } else {
                $newPermission->value = 0;
            }
            $newPermission->save();
            $newRole->permissions()->syncWithoutDetaching($newPermission->id);
            $newRole->save();
        }
        Session::flash('success', "New role created.");
        return back();
    }
    public function rolesDestroy($id)
    {
        if ($id == 1) {
            abort(403);
        }
        $role = Role::findOrFail($id);
        foreach ($role->permissions as $permission) {
            $permission->delete();
        }
        $role->delete();
        $role->permissions()->detach();
        Session::flash('success', "Role deleted.");
        return redirect()->back();
    }
}
