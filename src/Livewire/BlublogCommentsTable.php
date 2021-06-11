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

    /**
     * Delete comment with id.
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): void
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('blublog_delete_comments', $comment);
        $comment->delete();
    }

    /**
     * Make comment public or hidden.
     *
     * @param integer $id
     * @return void
     */
    public function togglePublic(int $id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('blublog_approve_comments', $comment);
        if ($comment->public) {
            $comment->public = false;
        } else {
            $comment->public = true;
        }
        $comment->save();
    }
}
