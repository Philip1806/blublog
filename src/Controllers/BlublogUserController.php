<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Role;
use Blublog\Blublog\Models\Log;
use App\User;
use Session;
use Auth;

class BlublogUserController extends Controller
{

    public function index()
    {
        if (Gate::denies('blublog_create_users')) {
            abort(403);
        }
        $Blublog_Users = BlublogUser::latest()->paginate(5);
        return view('blublog::panel.users.index')->with('users', $Blublog_Users);
    }
    public function profile(Request $request)
    {
        $Blublog_User = BlublogUser::where([
            ['user_id', '=', Auth::user()->id],
        ])->first();
        return view('blublog::panel.users.profile')->with('user', $Blublog_User);
    }
    public function create()
    {
        if (Gate::denies('blublog_create_users')) {
            abort(403);
        }
        return view('blublog::panel.users.create')->with('roles', Role::get_roles_in_array());
    }
    public function add(Request $request)
    {
        if (Gate::denies('blublog_create_users')) {
            abort(403);
        }
        $rules = [
            'email' => 'required|unique:users',
            'name' => 'required',
            'password' => 'min:6|max:120',
        ];
        $this->validate($request, $rules);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        BlublogUser::add($user, $request->role_id);
        Session::flash('success', __('blublog.user_added'));
        return redirect()->back();
    }
    public function edit($id)
    {
        if (Gate::denies('blublog_edit_users', $id)) {
            abort(403);
        }
        $Blublog_User = BlublogUser::find($id);
        $Blublog_User->role_id = $Blublog_User->role_id;
        $actions = Log::where([
            ['user_id', '=', $Blublog_User->id],
            ['type', '!=', "visit"],
        ])->limit(10)->latest()->get();
        $Blublog_User->latest_actions = $actions;
        $Blublog_User->all_roles = Role::get_roles_in_array();
        return view('blublog::panel.users.edit')->with('user', $Blublog_User);
    }
    public function update(Request $request, $id)
    {
        if (Gate::denies('blublog_edit_users', $id)) {
            abort(403);
        }
        $Blublog_User = BlublogUser::find($id);

        if ($request->newpassword) {
            $user = User::find($Blublog_User->user_id);
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

    public function destroy($id)
    {
        if (Gate::denies('blublog_delete_users')) {
            abort(403);
        }
        $Blublog_User = BlublogUser::find($id);
        if (!$Blublog_User) {
            Session::flash('error', __('blublog.404'));
            return redirect()->route('blublog.users.index');
        }
        $user = User::find($Blublog_User->user_id);
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
