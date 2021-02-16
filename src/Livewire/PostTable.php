<?php

namespace Blublog\Blublog\Livewire;

use Livewire\Component;
use Blublog\Blublog\Models\Post;
use Livewire\WithPagination;


class PostTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $status = "publish";

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::withStatus($this->status)->Where('title', 'like', '%' . $this->search . '%')->paginate(5);
        return view('blublog::livewire.posts.post-table')->with('posts', $posts);
    }

    public function showOnly($status)
    {
        $this->status = $status;
    }
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->remove();
    }
}
