<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;
use Auth;
use Session;

class Comment extends Model
{
    protected $table = 'blublog_comments';

    public function commentable()
    {
        return $this->morphTo();
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class, 'commentable_id');
    }
    public static function addComment($request, $notpublic = 1)
    {
        /*
            TODO:Rewrite
        */
        $comment = new Comment;
        $notpublic ? $comment->public = false : $comment->public = true;
        $post = Post::findOrFail($request->get('post_id'));
        if (!$post->comments) {
            Session::flash('warning', 'Comments not allowed.');
            Log::add($request, "alert", "Trying to add comment for a post that do not allow commenting.");
            return back();
        }
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->blublogRoles->first()->havePermission('create-comments')) {
                Session::flash('error', 'You do not have permission to create comments :(');
                Log::add($request, "alert", "User do not have permission to create comments.");
                return back();
            }
            $comment->name = $user->name;
            $comment->email = $user->email;
            $comment->author_id = $user->id;
            if ($post->user_id == $request->user()->id) {
                $comment->public = true;
            } elseif (config('blublog.auto-approve')) {
                $lastComment = Comment::where('author_id', '=', $user->id)->latest()->first();
                if ($lastComment) {
                    $lastComment->public ? $comment->public = true : '';
                }
            }
        } else {
            $comment->name = $request->name;
            $comment->email = $request->email;
        }
        $comment->body = $request->get('comment_body');
        $comment->parent_id = $request->get('comment_id');
        if ($comment->public) {
            Session::flash('success',  'Comment waits to be approved.');
        } else {
            Session::flash('success', 'Comment added.');
        }
        $post->comments()->save($comment);
        return back();
    }
}
