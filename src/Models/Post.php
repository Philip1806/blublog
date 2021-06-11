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

    /**
     * Returns image url of the post.
     * "post_image_size" from BLUblog Settings apply here.
     *
     * @return string
     */
    public function imageUrl(): string
    {
        if (config('blublog.post_image_size') === false) {
            return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($this->img);
        }
        $number = config('blublog.post_image_size') + 1;
        $info = pathinfo($this->img);
        $newfilename = $info['dirname'] . '/' . $info['filename'] . '_' . $number . '.' . $info['extension'];

        return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($newfilename);
    }

    /**
     * Returns image url of the post.
     * Uses the last element of "image_sizes" from BLUblog Settings as thumbnail sizes.
     *
     * @return string
     */
    public function thumbnailUrl()
    {
        $number = (int)array_key_last(config('blublog.image_sizes')) + 1;
        $info = pathinfo($this->img);

        if ($info['filename'] == 'no-image') {
            $newfilename = $this->img;
        } else {

            $newfilename = $info['dirname'] . '/' . $info['filename'] . '_' . $number . '.' . $info['extension'];
        }

        return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($newfilename);
    }

    /**
     * Changes image of the post.
     *
     * @param string $filename
     * @return void
     */
    public function changeImage($filename): void
    {
        if ($filename) {
            $this->img = $filename;
        } else {
            $this->img = 'photos/no-image.jpg';
        }
    }
}
