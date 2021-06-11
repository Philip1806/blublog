<?php

namespace Blublog\Blublog\Livewire;

use Livewire\Component;

use Blublog\Blublog\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;


class BlublogUsersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $users = blublog_user_model()::where('name', 'like', '%' . $this->search . '%')->paginate(5);
        $all_roles = Role::all();
        $data = array();
        $data[0] = "Default";
        foreach ($all_roles as $role) {
            $data[$role->id] = $role->name;
        }
        return view('blublog::livewire.users.blublog-users-table')->with('users', $users)->with('all_roles', $data);
    }
    public function removeUser($id)
    {
        if (!Gate::allows('blublog_delete_users') or Auth::user()->id == $id) {
            abort(403);
        }
        $user = blublog_user_model()::findOrFail($id);
        $user->blublogRoles()->detach();
        $user->delete();
        session()->flash('success', 'User deleted.');
        return true;
    }
    public function banFromBlog($id)
    {
        if (!blublog_is_admin()) {
            abort(403);
        }
        $user = blublog_user_model()::find($id);
        $user->blublogRoles()->detach();
        $user->save();
        session()->flash('success', 'User deleted.');
        return true;
    }
}
