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
        $comment = new Comment;
        if ($notpublic) {
            $comment->public = false;
        } else {
            $comment->public = true;
        }

        $post = Post::findOrFail($request->get('post_id'));

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
                Session::flash('success', 'Comment added.');
            } elseif (config('blublog.auto-approve')) {
                if (Comment::where('author_id', '=', $user->id)->latest()->first()) {
                    $comment->public = true;
                    Session::flash('success', 'Comment added.');
                }
            } else {
                Session::flash('success',  'Comment waits to be approved.');
            }
        } else {
            $comment->name = $request->name;
            $comment->email = $request->email;

            if ($notpublic) {
                Session::flash('success',  'Comment waits to be approved.');
            } else {
                Session::flash('success', 'Comment added.');
            }
        }

        $comment->body = $request->get('comment_body');
        $comment->parent_id = $request->get('comment_id');

        if (!$post->comments) {
            Session::flash('warning', 'Comments not allowed.');
            Log::add($request, "alert", "Trying to add comment for a post that do not allow commenting.");
            return back();
        }
        $post->comments()->save($comment);

        return back();
    }
}
