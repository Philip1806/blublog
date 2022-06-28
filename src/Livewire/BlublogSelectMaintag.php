<?php

namespace Blublog\Blublog\Livewire;

use Blublog\Blublog\Models\Tag;
use Livewire\Component;

class BlublogSelectMaintag extends Component
{
    public $search;
    public $selected;
    public $tags = array();

    public function render()
    {
        if ($this->search) {
            $tags = Tag::where('title', 'like', '%' . $this->search . '%')->limit(3)->get();
            $this->tags =  $tags;
        }

        return view('blublog::livewire.posts.blublog-select-maintag');
    }
    public function select($id)
    {
        $this->search = '';
        $this->selected = $id;
        $this->tags = array();
        $this->emit('MainTagSelected', $id);
    }
    public function unset()
    {
        $this->search = '';
        $this->selected = '';
        $this->tags = array();
        $this->emit('MainTagSelected', null);
    }
}
