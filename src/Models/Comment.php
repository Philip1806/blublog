<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;
use Auth;
use Blublog\Blublog\Requests\CommentRequest;
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

    /**
     * Main funcion for adding comment
     * Data must be validated.
     *
     * @param CommentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function addComment(CommentRequest $request): \Illuminate\Http\RedirectResponse
    {
        $post = Post::findOrFail($request->get('post_id'));

        if (!Comment::canAddCommentToPost($post)) {
            Session::flash('error', 'Comments not allowed.');
            Log::add($request, "alert", "Not allowed to add comment for a post.");
            return back();
        }

        if (Auth::check()) {
            $comment = Comment::addCommentAsLoggedIn($request, $post);
        } else {
            $comment = Comment::addCommentAsGuest($request, $post);
        }

        if ($comment->public) {
            Session::flash('success', 'Comment added.');
        } else {
            Session::flash('success',  'Comment waits to be approved.');
        }
        Log::add($comment->id, 'info', 'Comment added.');
        return back();
    }

    /**
     * Add comment from logged in user.
     *
     * @param CommentRequest $request
     * @param Post $post
     * @return Comment
     */
    public static function addCommentAsLoggedIn(CommentRequest $request, Post $post): Comment
    {
        $comment = new Comment;
        $user = Auth::user();

        $comment->name = $user->name;
        $comment->email = $user->email;
        $comment->author_id = $user->id;
        $comment->public = false;
        if ($post->user_id == $request->user()->id) {
            $comment->public = true;
        } elseif (Comment::visibilityForUser($user)) {
            $comment->public = true;
        }
        $comment->body = $request->get('comment_body');
        $comment->parent_id = $request->get('comment_id');
        $post->comments()->save($comment);
        return $comment;
    }

    /**
     * Add comment from guest/not logged in.
     *
     * @param CommentRequest $request
     * @param Post $post
     * @return Comment
     */
    public static function addCommentAsGuest(CommentRequest $request, Post $post): Comment
    {
        $comment = new Comment;

        $comment->name = $request->name;
        $comment->email = $request->email;
        if (config('blublog.auto-approve')) {
            Comment::IphaveApprovedComments(blublog_get_ip()) ? $comment->public = true : $comment->public = false;
        } else {
            $comment->public = false;
        }
        $comment->body = $request->get('comment_body');
        $comment->parent_id = $request->get('comment_id');
        $post->comments()->save($comment);
        return $comment;
    }
    /**
     * Checks if user or guess can add comment to post
     *
     * @param Post $post
     * @return boolean
     */
    public static function canAddCommentToPost(Post $post): bool
    {
        if (!$post->comments) {
            return false;
        }
        $user = Auth::user();
        if ($user) {
            if (!$user->blublogRoles->first()->havePermission('create-comments')) {
                return false;
            }
        } elseif (config('blublog.only-logged-in-can-comment')) {
            return false;
        }
        return true;
    }
    /**
     * Check if there is approved comment with given IP address
     *
     * @param string $ip
     * @return boolean
     */
    public static function IphaveApprovedComments(string $ip): bool
    {
        $havelog = Log::where([
            ['type', '=', 'info'],
            ['message', '=', "Comment added."],
            ['ip', '=', $ip],
        ])->latest()->first();
        if (!$havelog) {
            return false;
        }
        $comment = Comment::find($havelog->data);
        if ($comment and $comment->public) {
            return true;
        }
        return false;
    }
    /**
     * Check if user's comments should be public
     *
     * @param User $user
     * @return boolean
     */
    public static function visibilityForUser($user): bool
    {
        if (config('blublog.approve-if-logged-in')) {
            return true;
        } else if (config('blublog.auto-approve')) {
            if (Comment::UserhaveApprovedComments($user)) {
                return true;
            }
        }
        return false;
    }
    public static function UserhaveApprovedComments($user): bool
    {
        $lastComment = Comment::where('author_id', '=', $user->id)->latest()->first();
        if ($lastComment) {
            if ($lastComment->public) {
                return true;
            }
        }
        return false;
    }
}
