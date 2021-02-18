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


    public function index()
    {

        return view('blublog::panel.comments.index');
    }
    public function update(Request $request, $id)
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
        return back();
    }
}
