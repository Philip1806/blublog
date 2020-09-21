<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Role;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Exceptions\BlublogNoAccess;
use App\User;
use Session;
use Auth;

class BlublogUserController extends Controller
{

    public function index()
    {
        if (Gate::denies('blublog_create_users')) {
            throw new BlublogNoAccess;
        }
        $Blublog_Users = BlublogUser::latest()->paginate(5);
        return view('blublog::panel.users.index')->with('users', $Blublog_Users);
    }
    public function profile()
    {
        $Blublog_User = BlublogUser::get_user(Auth::user());
        return view('blublog::panel.users.profile')->with('user', $Blublog_User);
    }
    public function create()
    {
        if (Gate::denies('blublog_create_users')) {
            throw new BlublogNoAccess;
        }
        return view('blublog::panel.users.create')->with('roles', Role::get_roles_in_array());
    }
    public function add(Request $request)
    {
        if (Gate::denies('blublog_create_users')) {
            throw new BlublogNoAccess;
        }
        $rules = [
            'email' => 'required|unique:users',
            'name' => 'required',
            'password' => 'min:6|max:120',
        ];
        $this->validate($request, $rules);
        $user = BlublogUser::create_new($request);
        BlublogUser::add($user, $request->role_id);
        Session::flash('success', __('blublog.user_added'));
        return redirect()->back();
    }
    public function edit($id)
    {
        if (Gate::denies('blublog_edit_users', $id)) {
            throw new BlublogNoAccess;
        }
        $Blublog_User = BlublogUser::findOrFail($id);
        $Blublog_User->role_id = $Blublog_User->role_id;
        $Blublog_User->latest_actions = Log::by_user($Blublog_User->id);
        $Blublog_User->all_roles = Role::get_roles_in_array();
        return view('blublog::panel.users.edit')->with('user', $Blublog_User);
    }
    public function update(Request $request, $id)
    {
        if (Gate::denies('blublog_edit_users', $id)) {
            throw new BlublogNoAccess;
        }
        $Blublog_User = BlublogUser::findOrFail($id);

        if ($request->newpassword) {
            $user = User::findOrFail($Blublog_User->user_id);
            $user->password = Hash::make($request->newpassword);
            $user->save();
        }
        $Blublog_User->name = $request->name;
        $Blublog_User->full_name = $request->full_name;
        $Blublog_User->descr = $request->descr;
        $Blublog_User->img_url = $request->img_url;
        if (blublog_is_admin()) {
            $Blublog_User->email = $request->email;
            $Blublog_User->role_id = $request->role_id;
        }
        $Blublog_User->save();

        Session::flash('success', __('blublog.user_edited'));

        return redirect()->route('blublog.users.profile');
    }

    public function destroy_role($id)
    {
        if (!blublog_is_admin()) {
            throw new BlublogNoAccess;
        }
        if ($id == 1 or $id == 2 or $id == 3) {
            Log::add($id, "error", __('blublog.delete_main_role'));
            Session::flash('error', __('blublog.delete_main_role'));
            return redirect()->route('blublog.roles');
        }

        $BlublogUsers = BlublogUser::where([
            ['role_id', '=', $id],
        ])->get();

        if ($BlublogUsers) {
            foreach ($BlublogUsers as $BlublogUser) {
                $BlublogUser->role_id = 3;
                $BlublogUser->save();
            }
            Log::add($id, "alert", __('blublog.users_roles_changed'));
            Session::flash('warning', __('blublog.users_roles_changed'));
            return redirect()->route('blublog.roles');
        }

        $role = Role::findOrFail($id);
        $role->delete();

        Session::flash('success', __('blublog.contentdelete'));
        return redirect()->route('blublog.roles');
    }
    public function destroy($id)
    {
        if (Gate::denies('blublog_delete_users')) {
            throw new BlublogNoAccess;
        }
        $Blublog_User = BlublogUser::findOrFail($id);
        if (!$Blublog_User) {
            Session::flash('error', __('blublog.404'));
            return redirect()->route('blublog.users.index');
        }
        $user = User::findOrFail($Blublog_User->user_id);
        if (Auth::user()->id == $user->id) {
            Session::flash('warning', __('blublog.cant_delete_your_profile'));
            return redirect()->route('blublog.users.index');
        }
        $Blublog_User->delete();
        $user->delete();
        Session::flash('success', __('blublog.contentdelete'));
        return redirect()->route('blublog.users.index');
    }
}
