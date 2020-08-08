<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;

class Tag extends Model
{
    protected $table = 'blublog_tags';
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'blublog_posts_tags');
    }
    public static function get_tag_posts($tag_id, $remove = false)
    {
        $tag = Tag::find($tag_id);
        $posts = $tag->posts()->latest()->limit(blublog_setting('number_main_tag_posts'))->get();
        if ($remove) {
            $foo = 0;
            foreach ($posts as $post) {
                if ($post->id == $remove) {
                    unset($posts[$foo]);
                }
                $foo++;
            }
        }
        $posts = Post::public($posts);
        $posts = Post::processing($posts);
        return $posts;
    }
    public static function by_slug($slug)
    {
        $tag = Tag::where([
            ['slug', '=', $slug],
        ])->first();
        if (!$tag) {
            return false;
        }
        $posts = $tag->posts()->where("status", '=', 'publish')->latest();
        $tag->number_of_posts = $posts->count();
        $tag->get_posts = $posts->paginate(blublog_setting('tags_posts_per_page'));
        $tag->get_posts = Post::processing($tag->get_posts);
        return $tag;
    }
}
