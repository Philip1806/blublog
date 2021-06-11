<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Comment;

use Session;

class BlublogCommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): \Illuminate\View\View
    {
        return view('blublog::panel.comments.index');
    }

    /**
     * Edit Comment
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('blublog_edit_comments', $comment);
        if ($request->public) {
            $comment->public = true;
        } else {
            $comment->public = false;
        }
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->body = $request->body;
        $comment->save();
        Log::add(json_encode($comment->toArray()), 'info', 'A comment was edited.');
        return back();
    }
}
