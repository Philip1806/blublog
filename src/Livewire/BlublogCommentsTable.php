<?php

namespace Blublog\Blublog\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Blublog\Blublog\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;


class BlublogCommentsTable extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;

    public function render()
    {

        $comments = Comment::where('name', 'like', '%' . $this->search . '%')->latest()->paginate(10);
        return view('blublog::livewire.blublog-comments-table')->with('comments', $comments);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);
        $this->authorize('blublog_delete_comments', $comment);
        $comment->delete();
    }
    public function togglePublic($id)
    {
        $comment = Comment::find($id);
        $this->authorize('blublog_approve_comments', $comment);
        if ($comment->public) {
            $comment->public = false;
        } else {
            $comment->public = true;
        }
        $comment->save();
    }
}
