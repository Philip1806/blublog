<?php

namespace Philip\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Philip\Blublog\Models\Post;

class Category extends Model
{
    protected $table = 'blublog_categories';
    protected $fillable = [
        'category_id','post_id',
    ];
    public function posts() {
        return $this->belongsToMany(Post::class, 'blublog_posts_categories', 'category_id', 'post_id');
    }
}
