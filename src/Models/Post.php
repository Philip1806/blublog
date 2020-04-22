<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Philip1503\Blublog\Models\Category;
use Philip1503\Blublog\Models\Tag;
use Philip1503\Blublog\Models\Post;
use Philip1503\Blublog\Models\Comment;
use Carbon\Carbon;
use Philip1503\Blublog\Models\Rate;
use Philip1503\Blublog\Models\PostsViews;
use Session;
class Post extends Model
{
    protected $table = 'blublog_posts';

    public function user() {
        return $this->belongsTo('App\User');
    }
    public function views() {
        return $this->hasMany(PostsViews::class, 'post_id');
    }
    public function ratings() {
        return $this->hasMany(Rate::class, 'post_id');
    }
    public function categories() {
        return $this->belongsToMany(Category::class, 'blublog_posts_categories','post_id','category_id');
    }
    public function tags() {
        return $this->belongsToMany(Tag::class, 'blublog_posts_tags');
    }
    public function allcomments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }
    public static function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }
    public static function next_post_id()
    {
        if(!is_null(Post::latest()->first())){
            $lastPost = Post::latest()->first();
        }else{
            return 1;
        }
        return ++$lastPost->id;
    }
    public static function makeslug($title)
    {
            $numb = rand(0, 999) . Post::next_post_id();
            $slug = str_replace( " ", "-", $title);
            $slug = preg_replace("/[^A-Za-z0-9\p{Cyrillic}-]/u","",$slug);
            $slug = $slug . "-" . $numb ;
            $slug = mb_strimwidth($slug, 0, 190, null);
            return $slug;
    }
    public static function processing($posts, $null = 0)
    {
        $foo =0;
        foreach ($posts as $post){
            if($post->status != "publish"){
                unset($posts[$foo]);
            }
            $post->date = Carbon::parse($post->created_at)->format('d.m.Y');
            $post->slug_url = "/" . config('blublog.blog_prefix') . "/posts/" . $post->slug;
            $post->img_url = url('/uploads/posts/') . "/thumbnail_". $post->img;
            $foo++;
        }
        if(!$posts->count() and $null == 1){
            $posts = null;
        }
        return $posts;
    }


    /*
        Get post from id
        If $user_id is false, returns post only if is publish.

        @param int $post_id
        @param int/bool $user_id

        @return mixed
    */
    public static function getpost($post_id, $user_id)
    {
        $post = Post::find($post_id);
        if(!$post){
            return abort(404);
        }
        if($user_id){
            if($post->status == "private" and  $user_id != $post->user_id){
                return abort(404);
            }
        } else {
            if($post->status != "publish"){
                return abort(404);
            }
        }

        return $post;
    }
    //Post::img_used_by_other_post($id)

    /*
        Check if post img is been used for other post.

        @param int $post_id

        @return bool
    */
    public static function img_used_by_other_post($post_id)
    {
        $post = Post::find($post_id);
        $post = Post::where([
            ['img', '=', $post->img],
        ])->latest()->get();
        if($post->count() > 1){
            return true;
        }
        return false;
    }

    /*
        Get all post that are public.

        @param int $pages

        @return collection
    */
    public static function get_public_posts($pages = 10)
    {
        $posts = Post::where([
            ['status', '=', "publish"],
        ])->latest()->paginate($pages);
        if($posts){
            foreach ($posts as $post){
                $post->slug_url = "/" . config('blublog.blog_prefix') . "/posts/" . $post->slug;
                $post->img_url = url('/uploads/posts/') . "/thumbnail_". $post->img;
                $post->date = Carbon::parse($post->created_at)->format('d.m.Y');

                if($post->tag_id){
                    $post->maintag_id = $post->tag_id;
                } else {
                    $post->maintag_id = $post->categories[0]->id;
                }
                $post->maintag_title = $post->categories[0]->title;
            }
            return $posts;
        }
        return null;
    }

    public static function check_if_files_uploaded($mainfile,$thumbnail_file,$blur_thumbnail_file)
    {
        $message = '';
        if(!$mainfile){
            $message = $message . "Main file was not uploaded. ";
        }
        if(!$thumbnail_file){
            $message = $message . "Thumbnail file was not uploaded. ";
        }
        if(!$blur_thumbnail_file){
            $message = $message . "Blur thumbnail file was not uploaded. ";
        }
        if(!$mainfile or !$thumbnail_file or !$blur_thumbnail_file){
            $message = $message . "Check if you're set up blublog file driver in config/filesystems and blublog settings.";
            Session::flash('warning', $message);
            return false;
        }
        return true;

    }

    public static function thismonth()
    {
        $now = Carbon::today();
        // geting this month
        $thisyear = Carbon::parse($now)->format('Y');
        $thismonth = Carbon::parse($now)->format('m');
        $from = Carbon::create($thisyear, $thismonth, 1, 0)->toDateTimeString();
        return $from;
    }

    public static function nextmonth()
    {
        $now = Carbon::today();
        //geting next month
        $now->addMonth();
        $thisyear = Carbon::parse($now)->format('Y');
        $thismonth = Carbon::parse($now)->format('m');
        $to = Carbon::create($thisyear, $thismonth, 1, 0)->toDateTimeString();
        return $to;
    }

    public static function lastmonth()
    {
        $now = Carbon::today();
        //geting next month
        $now->subMonth();
        $thisyear = Carbon::parse($now)->format('Y');
        $thismonth = Carbon::parse($now)->format('m');
        $to = Carbon::create($thisyear, $thismonth, 1, 0)->toDateTimeString();
        return $to;
    }

}
