<?php

namespace   Philip1503\Blublog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\Comment;
use Session;
use Philip1503\Blublog\Models\Post;
use Philip1503\Blublog\Models\Log;


class BlublogCommentsController extends Controller
{
    public function __construct()
    {
        if(blublog_setting('disable_comments_modul')){
            abort(403);
        }
    }

    public function index()
    {
        $comments = Comment::latest()->paginate(15);
        if(!$comments){
            abort(404);
        }
        foreach ($comments as $comment) {
            $post = Post::find($comment->commentable_id);
            $comment->post_title = $post->title;
            $comment->post_slug = $post->slug;
        }
        return view('blublog::panel.comments.index')->with('comments', $comments);
    }
    public function edit($id)
    {
        $comment = Comment::find($id);
        if(!$comment){
            Log::add($id . "|BlublogCommentsController::edit", "alert", __('panel.404') );
            abort(404);
        }
        return view('blublog::panel.comments.edit')->with('comment', $comment);
    }
    public function update(Request $request, $id)
    {

        $comment = Comment::find($id);
        if(!$comment){
            Log::add($request, "alert", __('panel.404') );
            abort(404);
        }
        if($request->public){
            $comment->public =true;
        }else{
            $comment->public =false;
        }
        $comment->name = $request->name;
        $comment->created_at = $request->created_at;
        $comment->email = $request->email;
        $comment->ip = $request->ip;
        $comment->body = $request->body;
        Session::flash('success', __('panel.comment_edited'));
        Log::add($request, "info", __('panel.comment_edited') );
        return back();
    }
    public function approve($id)
    {
        preg_replace('/\D/', '', $id);
        $comment = Comment::find($id);
        if($comment){
            if($comment->public){
                $comment->public = false;
                Session::flash('success', __('panel.not_approved'));
            } else {
                $comment->public = true;
                Session::flash('success', __('panel.approved'));
            }
            Log::add($id . "|BlublogCommentsController::approve", "info", __('panel.approved') );
            $comment->save();
            return back();
        }
        Session::flash('error', __('panel.404'));
        Log::add($id . "|BlublogCommentsController::approve", "info", __('panel.404') );
        return back();
    }
    public function destroy($id){
        $comment = Comment::find($id);
        if($comment){
            $comment->delete();
            Log::add($id . "|BlublogCommentsController::destroy", "info", __('panel.comment_deleted') );
            Session::flash('success', __('panel.comment_deleted'));
            return redirect()->back();
        } else {
            Log::add($id . "|BlublogCommentsController::destroy", "alert", __('panel.404') );
            Session::flash('error', __('panel.404'));
            return redirect()->back();
        }
    }
}
