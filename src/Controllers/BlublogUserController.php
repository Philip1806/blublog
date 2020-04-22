<?php

namespace   Philip1503\Blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\BlublogUser;
use App\User;
use Session;
use Auth;

class BlublogUserController extends Controller
{

    public function index()
    {
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
        return view('blublog::panel.users.create');
    }
    public function add(Request $request)
    {
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
        $user->remember_token = $request->remember_token;
        $user->save();

        $User = User::where([
            ['email', '=',  $request->email],
        ])->first();

        $Blublog_User = new BlublogUser;
        $Blublog_User->user_id = $User->id;
        if($request->role){
            $Blublog_User->role = $request->role;
        }else {
            $Blublog_User->role = "Author";
        }
        $Blublog_User->save();

        return redirect()->route('blublog.users.index');
    }
    public function edit($id)
    {
        $user = User::find($id);
        if(!$user){
            abort(404);
        }
        $Blublog_User = BlublogUser::where([
            ['user_id', '=', $id],
        ])->first();
        $user->role = $Blublog_User->role;

        return view('blublog::panel.users.edit')->with('user', $user);
    }
    public function update(Request $request, $id)
    {
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

        if($request->role){
            $Blublog_User = BlublogUser::where([
                ['user_id', '=', $id],
            ])->first();
            if($request->user()->id == $id){
                Session::flash('warning', "You can not change your role. Other changes are saved.");
            } else {
                $Blublog_User->role = $request->role;
                $Blublog_User->save();
            }
        }
        Session::flash('success', "User is edited.");

        return redirect()->route('blublog.users.index');
    }

    public function destroy($id)
    {
        $Blublog_User = BlublogUser::find($id);
        if(!$Blublog_User){
            Session::flash('error', __('panel.404'));
            return redirect()->route('blublog.users.index');
        }
        $user = User::find($Blublog_User->user_id);
        if(Auth::user()->id == $user->id){
            Session::flash('warning', "Can't delete your profile.");
            return redirect()->route('blublog.users.index');
        }
        $Blublog_User->delete();
        $user->delete();
        Session::flash('success', 'Профила е премахнат.');
        return redirect()->route('blublog.users.index');
    }
}
