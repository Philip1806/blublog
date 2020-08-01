<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Log;
use Session;
use Carbon\Carbon;
use Auth;

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
                    Session::flash('success',  __('blublog.comment_added'));
                }
            } else {
                if($notpublic){
                    Session::flash('success',  __('blublog.comment_added_wait'));
                } else {
                    Session::flash('success',  __('blublog.comment_added'));
                }
            }
            if(Auth::check()){
                $comment->author_id = blublog_get_user(1);
            }
            $comment->body = $request->get('comment_body');
            $comment->parent_id = $request->get('comment_id');
            $post = Post::find($request->get('post_id'));
            if(!$post->comments){
                Session::flash('warning', __('blublog.comments_not_allowed'));
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
            ['message', '=', __('blublog.max_unaproved_comments')],
            ['created_at', '>', Carbon::today()->subHour()],
        ])->first();
        if($ban){
            return true;
        }
        return false;
    }

}
