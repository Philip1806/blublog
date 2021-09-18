<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Exceptions\AdminRoleChangeException;
use Livewire\Component;

class BlublogAuthorChange extends Component
{
    public $search;
    public $users = array();

    public function render()
    {
        if ($this->search) {
            $users = blublog_user_model()::where('name', 'like', '%' . $this->search . '%')->limit(3)->get();
            $this->users =  $users;
        }

        return view('blublog::livewire.posts.blublog-author-change');
    }
    public function select($id)
    {
        $this->search = '';
        $this->users = array();
        $this->emit('AuthorChanged', $id);
    }
}
