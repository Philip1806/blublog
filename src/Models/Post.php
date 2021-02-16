<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $table = 'blublog_posts';
    protected $guarded = ['status', 'created_at'];
    public function user()
    {
        return $this->belongsTo(blublog_user_model());
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blublog_posts_categories', 'post_id', 'category_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blublog_posts_tags');
    }
    public function imageUrl()
    {
        return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($this->img);
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
            $post->seo_descr = mb_strimwidth(strip_tags($request->content), 0, 155, "...");
        }
        if ($request->slug) {
            $post->slug = $request->slug;
        } else {
            $post->slug = blublog_create_slug($request->title);
        }

        $post->user_id = auth()->user()->id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        $post->tags()->sync($request->tags, false);
        $post->categories()->sync($request->categories, false);
        $post->changeImage($request->img);
        $post->setPostStatus($request->status);
        $post->save();
    }
    public static function updatePost(Request $request, $id)
    {
        $post = Post::findORfail($id);
        $post->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'slug' => $request->slug,
            'seo_title' => $request->seo_title,
            'seo_descr' => $request->seo_descr,

        ]);
        $post->tags()->sync($request->tags);
        $post->categories()->sync($request->categories);
        $post->changeImage($request->img);
        $post->setPostStatus($request->status);

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
