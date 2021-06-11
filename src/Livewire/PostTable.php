<?php

namespace Blublog\Blublog\Livewire;

use Livewire\Component;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Services\PostService;
use Livewire\WithPagination;


class PostTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $postservice;

    public $search;
    public $status = "publish";

    protected $queryString = [
        'status' => ['except' => 'publish'],
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(PostService $postservice)
    {
        if ($this->status == "my") {
            $posts = $postservice->findFromUserId(auth()->user()->id);
        } else {
            $posts = $postservice->search($this->search, $this->status);
        }
        return view('blublog::livewire.posts.post-table')->with('posts', $posts);
    }

    public function showOnly($status)
    {
        $this->my_posts = false;
        $this->status = $status;
    }
    public function delete(PostService $postservice, $id)
    {
        $post = $postservice->findById($id);
        $postservice->remove($post);
    }
    public function myPosts()
    {
        $this->status = 'my';
    }
}
