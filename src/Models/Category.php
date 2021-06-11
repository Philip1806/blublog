<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Exceptions\BlublogNotFound;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $table = 'blublog_categories';
    protected $guarded = ['role_id'];
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'blublog_posts_categories', 'category_id', 'post_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function getPosts()
    {
        return $this->posts()->where('status', '=', 'publish')->latest();
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
