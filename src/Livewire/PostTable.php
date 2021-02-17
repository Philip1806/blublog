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
    public $my_posts = false;
    public $status = "publish";

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->my_posts) {
            $posts = Post::where('user_id', '=', auth()->user()->id)->latest()->paginate(5);
        } else {
            $posts = Post::withStatus($this->status)->Where('title', 'like', '%' . $this->search . '%')->latest()->paginate(5);
        }
        return view('blublog::livewire.posts.post-table')->with('posts', $posts);
    }

    public function showOnly($status)
    {
        $this->my_posts = false;
        $this->status = $status;
    }
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->remove();
    }
    public function myPosts()
    {
        $this->my_posts = true;
        $this->status = 'my';
    }
}
