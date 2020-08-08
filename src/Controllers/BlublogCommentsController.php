<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Ban;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Log;
use Session;

class BlublogCommentsController extends Controller
{
    public function __construct()
    {
        if (blublog_setting('disable_comments_modul')) {
            abort(403);
        }
    }

    public function index()
    {
        $comments = Comment::latest()->paginate(10);
        if (!$comments) {
            abort(404);
        }
        foreach ($comments as $comment) {
            $post = Post::find($comment->commentable_id);
            if ($post) {
                $comment->post_title = $post->title;
                $comment->post_slug = $post->slug;
            }
        }
        return view('blublog::panel.comments.index')->with('comments', $comments);
    }
    public function edit($id)
    {
        $comment = Comment::find($id);
        BlublogUser::check_access('update', $comment);
        if (!$comment) {
            Log::add($id . "|BlublogCommentsController::edit", "alert", __('blublog.404'));
            abort(404);
        }
        return view('blublog::panel.comments.edit')->with('comment', $comment);
    }
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        BlublogUser::check_access('update', $comment);
        if (!$comment) {
            Log::add($request, "alert", __('blublog.404'));
            abort(404);
        }
        if ($request->public) {
            $comment->public = true;
        } else {
            $comment->public = false;
        }
        $comment->name = $request->name;
        $comment->created_at = $request->created_at;
        $comment->email = $request->email;
        $comment->ip = $request->ip;
        $comment->body = $request->body;
        $comment->save();
        Session::flash('success', __('blublog.comment_edited'));
        Log::add($request, "info", __('blublog.comment_edited'));
        return back();
    }
    public function approve($id)
    {
        preg_replace('/\D/', '', $id);
        $comment = Comment::find($id);
        BlublogUser::check_access('approve', $comment);
        if ($comment) {
            if ($comment->public) {
                $comment->public = false;
                Session::flash('success', __('blublog.not_approved'));
            } else {
                $comment->public = true;
                Session::flash('success', __('blublog.approved'));
            }
            Post::remove_cache($comment->commentable_id);
            Log::add($id . "|BlublogCommentsController::approve", "info", __('blublog.approved'));
            $comment->save();
            return back();
        }
        Session::flash('error', __('blublog.404'));
        Log::add($id . "|BlublogCommentsController::approve", "info", __('blublog.404'));
        return back();
    }
    public function destroy($id)
    {
        $comment = Comment::find($id);
        BlublogUser::check_access('delete', $comment);
        Post::remove_cache($comment->commentable_id);
        if ($comment) {
            $comment->delete();
            Log::add($id . "|BlublogCommentsController::destroy", "info", __('blublog.comment_deleted'));
            Session::flash('success', __('blublog.comment_deleted'));
            return redirect()->back();
        } else {
            Log::add($id . "|BlublogCommentsController::destroy", "alert", __('blublog.404'));
            Session::flash('error', __('blublog.404'));
            return redirect()->back();
        }
    }
    public function ban($id)
    {
        $comment = Comment::find($id);
        BlublogUser::check_access('ban', Comment::class);
        if ($comment) {
            if (!Ban::is_banned_from_comments($comment->ip)) {
                Ban::ip($comment->ip, __('blublog.banned_from_comments'), 1);
                Log::add($id . "|BlublogCommentsController::ban", "info", __('blublog.banned_from_comments'));
                Session::flash('success', __('blublog.banned_from_comments'));
                return redirect()->back();
            } else {
                Session::flash('success', __('blublog.its_banned'));
                return redirect()->back();
            }
        } else {
            Log::add($id . "|BlublogCommentsController::ban", "alert", __('blublog.404'));
            Session::flash('error', __('blublog.404'));
            return redirect()->back();
        }
    }
}
