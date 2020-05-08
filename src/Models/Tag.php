<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Philip1503\Blublog\Models\Post;

class Tag extends Model
{
    protected $table = 'blublog_tags';
    public function posts() {
        return $this->belongsToMany(Post::class, 'blublog_posts_tags');
    }
    public static function get_tag_posts($tag_id,$remove = false){
        $tag = Tag::find($tag_id);
        $posts = $tag->posts()->latest()->limit(blublog_setting('number_main_tag_posts'))->get();
        if($remove){
            $foo =0;
            foreach($posts as $post){
                if($post->id == $remove){
                    unset($posts[$foo]);
                }
                $foo++;
            }
        }
        $posts = Post::public($posts);
        $posts = Post::processing($posts);
        return $posts;
    }
}
