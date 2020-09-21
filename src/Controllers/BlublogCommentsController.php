<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Ban;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Exceptions\BlublogModulDisabled;
use Blublog\Blublog\Exceptions\BlublogNoAccess;
use Blublog\Blublog\Exceptions\BlublogNotFound;
use Session;

class BlublogCommentsController extends Controller
{
    public function __construct()
    {
        if (blublog_setting('disable_comments_modul')) {
            throw new BlublogModulDisabled;
        }
    }

    public function index()
    {
        $comments = Comment::latest()->paginate(10);
        $comments = Comment::post_info($comments);
        return view('blublog::panel.comments.index')->with('comments', $comments);
    }
    public function edit($id)
    {
        try {
            $comment = Comment::find_by_id($id);
            BlublogUser::check_access('update', $comment);
        } catch (BlublogNotFound $exception) {
            throw new BlublogNotFound;
        } catch (BlublogNoAccess $exception) {
            Log::add($id . "|BlublogCommentsController::edit", "error", __('blublog.BlublogNoAccess'));
            throw new BlublogNoAccess;
        }

        return view('blublog::panel.comments.edit')->with('comment', $comment);
    }
    public function update(Request $request, $id)
    {
        try {
            $comment = Comment::find_by_id($id);
            BlublogUser::check_access('update', $comment);
        } catch (BlublogNoAccess $exception) {
            Log::add($id . "|BlublogCommentsController::update", "error", __('blublog.BlublogNoAccess'));
            throw new BlublogNoAccess;
        }
        Comment::edit($request, $comment);
        Session::flash('success', __('blublog.comment_edited'));
        Log::add($request, "info", __('blublog.comment_edited'));
        return back();
    }
    public function approve($id)
    {
        try {
            $comment = Comment::find_by_id($id);
            BlublogUser::check_access('approve', $comment);
        } catch (BlublogNoAccess $exception) {
            Log::add($id . "|BlublogCommentsController::approve", "error", __('blublog.BlublogNoAccess'));
            throw new BlublogNoAccess;
        }
        if ($comment->public) {
            $comment->public = false;
            Session::flash('success', __('blublog.not_approved'));
            Log::add($id . "|BlublogCommentsController::approve", "info", __('blublog.not_approved'));
        } else {
            $comment->public = true;
            Session::flash('success', __('blublog.approved'));
            Log::add($id . "|BlublogCommentsController::approve", "info", __('blublog.approved'));
        }
        Post::remove_cache($comment->commentable_id);
        $comment->save();
        return back();
    }
    public function destroy($id)
    {
        try {
            $comment = Comment::find_by_id($id);
            BlublogUser::check_access('delete', $comment);
        } catch (BlublogNoAccess $exception) {
            Log::add($id . "|BlublogCommentsController::destroy", "error", __('blublog.BlublogNoAccess'));
            throw new BlublogNoAccess;
        }
        Post::remove_cache($comment->post->id);
        $comment->delete();
        Log::add($id . "|BlublogCommentsController::destroy", "info", __('blublog.comment_deleted'));
        Session::flash('success', __('blublog.comment_deleted'));
        return redirect()->back();
    }
    public function ban($id)
    {
        try {
            $comment = Comment::find_by_id($id);
            BlublogUser::check_access('ban', Comment::class);
        } catch (BlublogNoAccess $exception) {
            Log::add($id . "|BlublogCommentsController::ban", "error", __('blublog.BlublogNoAccess'));
            throw new BlublogNoAccess;
        }
        $comment = Comment::find($id);
        if (!Ban::is_banned_from_comments($comment->ip)) {
            Ban::ip($comment->ip, __('blublog.banned_from_comments'), 1);
            Log::add($id . "|BlublogCommentsController::ban", "info", __('blublog.banned_from_comments'));
            Session::flash('success', __('blublog.banned_from_comments'));
            return redirect()->back();
        } else {
            Session::flash('success', __('blublog.its_banned'));
            return redirect()->back();
        }
    }
}
