<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Requests\UpdateUserRequest;
use Blublog\Blublog\Requests\UserRequest;
use Illuminate\Support\Facades\Gate;
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
        if (
            Gate::denies('blublog_edit_users', blublog_user_model()) and
            Gate::denies('blublog_create_users', blublog_user_model()) and
            Gate::denies('blublog_delete_users', blublog_user_model())
        ) {
            abort(403);
        }
        return view('blublog::panel.users.index');
    }

    public function update(UpdateUserRequest $request, $user_id)
    {
        $user = blublog_user_model()::findOrFail($user_id);
        if ($request->user()->cannot('blublog_edit_users', $user)) {
            abort(403);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->new_password) {
            $user->password = Hash::make($request->new_password);
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
    public function store(UserRequest $request)
    {
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
