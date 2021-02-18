<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
    protected function recordRevision()
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
        /*
            'before' => json_encode(array_intersect_key($post->fresh()->toArray(), $post->getDirty())),
            'after' => json_encode($post->getDirty()),
        */
    }
    public function registerView()
    {
        if (!Log::userSeenPost($this->id)) {
            Log::add($this->id, "visit");
            $this->views++;
            $this->save();
        }
    }
    public function like()
    {
        if (!Log::postLiked($this->id)) {
            Log::add($this->id, "like", "Post liked.");
            $this->likes++;
            $this->save();
        }
    }
    public function imageUrl()
    {
        if (config('blublog.post_image_size') === false) {
            return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($this->img);
        }
        $number = config('blublog.post_image_size') + 1;
        $info = pathinfo($this->img);
        $newfilename = $info['dirname'] . '/' . $info['filename'] . '_' . $number . '.' . $info['extension'];

        return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($newfilename);
    }
    public function thumbnailUrl()
    {
        $number = (int)array_key_last(config('blublog.image_sizes')) + 1;
        $info = pathinfo($this->img);
        $newfilename = $info['dirname'] . '/' . $info['filename'] . '_' . $number . '.' . $info['extension'];

        return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($newfilename);
    }
    public function changeImage($filename)
    {
        if ($filename) {
            $this->img = $filename;
        } else {
            $this->img = 'no-image.jpg';
        }
    }

    public static function withStatus($status)
    {
        $list_of_status = config('blublog.post_status');
        $list_of_status_access = config('blublog.post_status_access');
        $user = auth()->user();

        if (!in_array($status, $list_of_status)) {
            return null;
        }
        for ($i = 0; $i < count(--$list_of_status); $i++) {
            if ($list_of_status[$i] != $status) {
                continue;
            }
            $user = auth()->user();


            if ($list_of_status_access[$i] == 3 and $user->blublogRoles->first()->havePermission('edit-' . $status)) {
                return Post::Where('status', '=',  $status);
            } elseif ($list_of_status_access[$i] == 2) {
                return Post::where([
                    ['user_id', '=', $user->id],
                    ['status', '=', $status],
                ]);
            } elseif ($list_of_status_access[$i] == 1 and blublog_is_mod()) {

                return Post::where([
                    ['status', '=', $status],
                ]);
            } elseif ($list_of_status_access[$i] == 0) {
                return Post::Where('status', '=',  $status);
            }
            abort(403);
            break;
        }

        return null;
    }
    public function setPostStatus($status)
    {
        if (auth()->user()->blublogRoles->first()->havePermission('wait-for-approve')) {
            $this->status = 'waits';
            return true;
        }
        if (!in_array($status, blublog_list_status())) {
            Session::flash('warning', "Unvalid post status.");
            return false;
        }
        if (auth()->user()->id != $this->user_id and !blublog_is_mod()) {
            Session::flash('warning', "You are not post author or moderator. You can NOT change post status.");
            return false;
        }
        $this->status = $status;
    }
    public function remove()
    {
        if (!Gate::allows('blublog_delete_posts', $this)) {
            abort(403);
        }
        $this->categories()->detach();
        $this->tags()->detach();
        $this->delete();
        return true;
    }
    public static function cleanInput($content)
    {
        if (auth()->user()->blublogRoles->first()->havePermission('no-html')) {
            $content = preg_replace('@<(script|style)[^>]*?>.*?@si', '', $content);
            $content = strip_tags($content);

            return nl2br(trim($content));
        } elseif (auth()->user()->blublogRoles->first()->havePermission('restrict-html')) {
            if (class_exists('Mews\Purifier\Facades\Purifier')) {
                return \Mews\Purifier\Facades\Purifier::clean($content);
            }
            return e($content);
        } else {
            return $content;
        }
    }
    public static function createPost(Request $request)
    {
        $post = new Post;

        if ($request->seo_title) {
            $post->seo_title = $request->seo_title;
        } else {
            $post->seo_title = mb_strimwidth($request->title, 0, 60, null);
        }
        if ($request->seo_descr) {
            $post->seo_descr = $request->seo_descr;
        } else {
            $post->seo_descr = mb_strimwidth(strip_tags($request->content), 0, 100, "...");
        }
        if ($request->slug) {
            $post->slug = $request->slug;
        } else {
            $post->slug = blublog_create_slug($request->title);
        }

        $post->user_id = auth()->user()->id;
        $post->title = $request->title;
        $post->content = Post::cleanInput($request->content);
        $post->save();

        $post->tags()->sync($request->tags, false);
        $post->categories()->sync($request->categories, false);
        $post->changeImage($request->img);
        if (isset(blublog_list_status()[$request->status])) {
            $post->setPostStatus(blublog_list_status()[$request->status]);
        } else {
            Session::flash('warning', "Unvalid post status.");
        }
        $post->save();
    }
    public static function updatePost(Request $request, $id)
    {
        $post = Post::findORfail($id);

        $post->title = $request->title;
        $post->excerpt = $request->excerpt;
        $post->content = Post::cleanInput($request->content);
        $post->slug = $request->slug;
        $post->seo_title = $request->seo_title;
        $post->seo_descr = $request->seo_descr;

        $post->tags()->sync($request->tags);
        $post->categories()->sync($request->categories);
        $post->changeImage($request->img);
        if (isset(blublog_list_status()[$request->status])) {
            $post->setPostStatus(blublog_list_status()[$request->status]);
        } else {
            Session::flash('warning', "Unvalid post status.");
        }

        if ($request->comments) {
            $post->comments = true;
        } else {
            $post->comments = false;
        }
        if ($request->front) {
            $post->front = true;
        } else {
            $post->front = false;
        }
        if ($request->recommended) {
            $post->recommended = true;
        } else {
            $post->recommended = false;
        }

        $post->save();
    }
    public static function recommended()
    {
        return Post::where([
            ['status', '=', 'publish'],
            ['recommended', '=', true],
        ])->latest()->get();
    }
    public static function bySlug($slug)
    {
        $post = Post::where([
            ['status', '=', 'publish'],
            ['slug', '=', $slug],
        ])->first();
        if (!$post) {
            abort(404);
        }
        return $post;
    }
    public function similarPosts()
    {
        $needed_similar_posts = config('blublog.similar-posts');

        // Check if post do not have tags
        $category_posts = $this->categories[0]->getPosts()->limit($needed_similar_posts)->get()->shuffle();
        if (!isset($this->tags[0]->id)) {
            return $category_posts;
        }

        // Make collection
        $similarpost = collect(new Post);

        // Add all posts from all tags in the collection
        foreach ($this->tags as $tag) {
            foreach ($tag->posts as $post) {
                $similarpost->push($post);
            }
        }
        // Add some post from the same category in the collection
        foreach ($category_posts as $post) {
            $similarpost->push($post);
        }

        // Filter the collection. No duplicates.
        // TODO: Remove main post from collection.
        $similarpost = $similarpost->unique('id')->shuffle();

        return $similarpost->take($needed_similar_posts);
    }

    public static function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
}
