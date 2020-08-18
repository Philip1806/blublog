<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Log;

class PostsViews extends Model
{
    protected $table = 'blublog_posts_views';

    public static function add($post_id)
    {
        if (PostsViews::check($post_id)) {
            $view = new PostsViews;
            $view->post_id = $post_id;
            $view->ip = Post::getIp();;
            $view->agent = \Request::header('User-Agent');
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
        if (!$view) {
            return true;
        }
        return false;
    }
}
