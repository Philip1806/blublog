<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;
use Illuminate\Support\Facades\Gate;

class Tag extends Model
{
    protected $table = 'blublog_tags';
    protected $guarded = ['created_at', 'updated_at'];
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'blublog_posts_tags');
    }
}
