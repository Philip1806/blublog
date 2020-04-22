<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'blublog_tags';
    public function posts() {
        return $this->belongsToMany(Post::class, 'blublog_posts_tags');
    }
}
