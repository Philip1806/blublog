<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\Revision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Session;

class Post extends Model
{
    protected $table = 'blublog_posts';
    protected $guarded = ['status', 'created_at'];

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public static function boot()
    {
        parent::boot();
        static::updating(function ($post) {
            $post->recordRevision();
        });
    }
    public function user()
    {
        return $this->belongsTo(blublog_user_model());
    }
    public function revisions()
    {
        return $this->hasMany(Revision::class, 'post_id')->latest('updated_at');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blublog_posts_categories', 'post_id', 'category_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blublog_posts_tags');
    }
    public function image()
    {
        return $this->hasOne(File::class, 'filename', 'img');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    /**
     * Create revision if needed.
     *
     * @return boolean
     */
    protected function recordRevision(): bool
    {
        if (!isset($this->getDirty()['content'])) {
            return false;
        }
        $keyOfStatus = array_keys(config('blublog.post_status'), $this->status)[0];
        $revisionSetting = config('blublog.post_status_revisions')[$keyOfStatus];

        if (!$revisionSetting) {
            return false;
        }

        if ($revisionSetting !== true) {
            if ($this->revisions()->count() >= $revisionSetting) {
                $this->revisions->last()->delete();
            }
        }

        $revision = new Revision;
        $revision->user_id = Auth::id();
        $revision->post_id = $this->id;
        $revision->before = $this->fresh()->toArray()['content'];
        $revision->after = $this->getDirty()['content'];
        $revision->save();
        return true;
        /*
            'before' => json_encode(array_intersect_key($post->fresh()->toArray(), $post->getDirty())),
            'after' => json_encode($post->getDirty()),
        */
    }


    public function thumbnailUrl()
    {
        if ($this->file) {
            return $this->file->thumbnailUrl();
        }
        return url('\blublog-uploads\photos\no-image.jpg');
    }
    public function getFileUrl()
    {
        if ($this->file) {
            return $this->file->url();
        }
        return url('\blublog-uploads\photos\no-image.jpg');
    }
}
