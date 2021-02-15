<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;

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
}
