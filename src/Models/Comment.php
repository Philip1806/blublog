<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Philip1503\Blublog\Models\Post;
use Philip1503\Blublog\Models\Log;
use Session;
use Carbon\Carbon;

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
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'commentable');
    }
    public static function addcomment($request, $ip, $notpublic = 1)
    {
            $comment = new Comment;
            if($notpublic){
                $comment->public = false;
            } else {
                $comment->public = true;
            }
            if($request->name){
                $comment->name = $request->name;
            } else{
                $comment->name = 'Гост';
            }
            $comment->email = $request->email;
            $comment->ip = $ip;
            $post = Post::find($request->get('post_id'));
            if(isset($request->user()->name)){
                if($post->user->name == $request->user()->name){
                    $comment->author = true;
                    $comment->public = true;
                    Session::flash('success',  __('panel.comment_added'));
                }
            } else {
                if($notpublic){
                    Session::flash('success',  __('panel.comment_added_wait'));
                } else {
                    Session::flash('success',  __('panel.comment_added'));
                }
            }
            $comment->body = $request->get('comment_body');
            $comment->parent_id = $request->get('comment_id');
            $post = Post::find($request->get('post_id'));
            if(!$post->comments){
                Session::flash('warning', __('panel.comments_not_allowed'));
                Log::add($request, "warning", "Trying to add comment for a post that do not allow commenting." );
                return back();
            }
            $post->allcomments()->save($comment);

            return back();
    }
    public static function limit_unapproved_comments_reached_soon()
    {
        $ban = Log::where([
            ['type', '=', "error"],
            ['message', '=', __('panel.max_unaproved_comments')],
            ['created_at', '>', Carbon::today()->subHour()],
        ])->first();
        if($ban){
            return true;
        }
        return false;
    }

}
