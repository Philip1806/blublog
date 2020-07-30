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
        if(Gate::denies('blublog_create_users')){
            abort(403);
        }
        $Blublog_Users = BlublogUser::latest()->paginate(5);
        foreach ($Blublog_Users as $Blublog_User){
            $user = User::find($Blublog_User->user_id);
            $Blublog_User->name = $user->name;
            $Blublog_User->email = $user->email;
        }
        return view('blublog::panel.users.index')->with('users', $Blublog_Users);
    }
    public function create()
    {
        if(Gate::denies('blublog_create_users')){
            abort(403);
        }
        return view('blublog::panel.users.create')->with('roles', Role::get_roles_in_array());
    }
    public function add(Request $request)
    {
        if(Gate::denies('blublog_create_users')){
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

        $Blublog_User = new BlublogUser;
        $Blublog_User->user_id = $user->id;
        $Blublog_User->role_id = $request->role_id ;
        $Blublog_User->save();

        return redirect()->back();
    }
    public function edit($id)
    {
        if(Gate::denies('blublog_edit_users')){
            abort(403);
        }
        $user = User::find($id);
        if(!$user){
            abort(404);
        }
        $Blublog_User = BlublogUser::where([
            ['user_id', '=', $id],
        ])->first();
        $user->role_id = $Blublog_User->role_id;
        $actions = Log::where([
            ['user_id', '=', $user->id],
            ['type', '!=', "visit"],
        ])->limit(10)->latest()->get();
        $user->latest_actions = $actions;
        $user->all_roles = Role::get_roles_in_array();
        return view('blublog::panel.users.edit')->with('user', $user);
    }
    public function update(Request $request, $id)
    {
        if(Gate::denies('blublog_edit_users')){
            abort(403);
        }
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->newpassword){
            $user->password = Hash::make($request->newpassword);
        }
        $user->remember_token = $request->remember_token;
        $user->created_at = $request->created_at;
        $user->updated_at = $request->updated_at;
        $user->save();

        $Blublog_User = BlublogUser::where([
            ['user_id', '=', $id],
        ])->first();
        if($request->user()->id == $id){
            Session::flash('warning', __('blublog.cant_change_your_role'));
        } else {
            $Blublog_User->role_id = $request->role_id;
            $Blublog_User->save();
        }

        Session::flash('success', __('blublog.user_edited'));

        return redirect()->route('blublog.users.index');
    }

    public function destroy($id)
    {
        if(Gate::denies('blublog_delete_users')){
            abort(403);
        }
        $Blublog_User = BlublogUser::find($id);
        if(!$Blublog_User){
            Session::flash('error', __('blublog.404'));
            return redirect()->route('blublog.users.index');
        }
        $user = User::find($Blublog_User->user_id);
        if(Auth::user()->id == $user->id){
            Session::flash('warning', __('blublog.cant_delete_your_profile'));
            return redirect()->route('blublog.users.index');
        }
        $Blublog_User->delete();
        $user->delete();
        Session::flash('success', __('blublog.contentdelete'));
        return redirect()->route('blublog.users.index');
    }
}
