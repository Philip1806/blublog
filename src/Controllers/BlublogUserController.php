<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Session;

class BlublogUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('blublog::panel.users.index');
    }

    public function update(Request $request, $user_id)
    {
        $rules = [
            'name' => 'required|max:250',
            'email' => 'required|email',
            'password' => 'min:8|max:150',
        ];
        $this->validate($request, $rules);

        $user = blublog_user_model()::findOrFail($user_id);
        if ($request->user()->cannot('blublog_edit_users', $user)) {
            abort(403);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->new_password) {
            $user->new_password = Hash::make($request->password);
        }
        $user->save();
        if ($request->role_id and blublog_is_admin()) {
            if (auth()->user()->id != $user->id) {
                $user->blublogRoles()->sync($request->role_id);
            }
        }

        Session::flash('success', "User edited.");
        return back();
    }
    public function store(Request $request)
    {
        if ($request->user()->cannot('blublog_create_users')) {
            abort(403);
        }
        $rules = [
            'name' => 'required|max:250',
            'email' => 'required|email|unique:' . config('blublog.userModel') . ',email',
            'password' => 'required|min:8|max:150',
        ];
        $this->validate($request, $rules);

        $user = blublog_user_model();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        if ($request->role_id == 0) {
            $user->blublogRoles()->sync(1);
        } else {
            $user->blublogRoles()->sync($request->role_id);
        }


        Session::flash('success', "User added.");
        return back();
    }
}
