<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'blublog_posts_ratings';
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    public static function copy($original)
    {
        $rating = new Rate;
        $rating->post_id = $original->post_id;
        $rating->rating = $original->rating;
        $rating->ip = $original->ip;
        $rating->save();
    }
}
