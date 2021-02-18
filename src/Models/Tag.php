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
    public static function createTag($title)
    {
        if (!Gate::allows('blublog_create_tags')) {
            abort(403);
        }
        Tag::create([
            'title' => $title,
            'slug' => blublog_create_slug($title),
        ]);
        return redirect()->back();
    }
    public function removeTag()
    {
        if (!Gate::allows('blublog_delete_tags', $this)) {
            abort(403);
        }
        $this->posts()->detach();
        $this->delete();
        return redirect()->back();
    }
    public function getPosts()
    {
        return $this->posts()->where('status', '=', 'publish')->latest();
    }
}
