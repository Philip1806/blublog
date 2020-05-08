<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Philip1503\Blublog\Models\Post;
use Philip1503\Blublog\Models\Log;

class PostsViews extends Model
{
    protected $table = 'blublog_posts_views';

    public static function add($post_id)
    {
        if(PostsViews::check($post_id)){
            $view = new PostsViews;
            $view->post_id = $post_id;
            $view->ip = Post::getIp();;
            $view->agent = \Request::header('User-Agent');
            $view->data = $post_id;
            $view->save();
            Log::add('null', "visit");
        }
    }
    public static function check($post_id)
    {
        $view = PostsViews::where([
            ['ip', '=', Post::getIp()],
            ['post_id', '=', $post_id],
        ])->first();
        if(!$view){
            return true;
        }
        return false;

    }

}
