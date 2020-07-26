<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Comment;
use Carbon\Carbon;
use Blublog\Blublog\Models\Rate;
use Blublog\Blublog\Models\PostsViews;
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
    public static function remove_cache($post_id)
    {
        $post = Post::find($post_id);
        Cache::forget('blublog.post.'.$post->slug);
        Cache::forget('blublog.comments.'.$post->slug);
    }
    public static function recommended($limit= 20)
    {
        $posts = Post::where([
            ['recommended', '=', true],
            ['status', '=', "publish"],
        ])->latest()->limit($limit)->get();
        if($posts){
            return Post::processing($posts);
        }
    }
    public static function slider()
    {
        $posts = Post::where([
            ['slider', '=', true],
            ['status', '=', "publish"],
        ])->latest()->get();
        if($posts){
            return Post::processing($posts);
        }
    }
    public static function for_front_page($limit= 20)
    {
        $posts = Post::where([
            ['front', '=', true],
            ['status', '=', "publish"],
        ])->latest()->paginate($limit);
        if($posts){
            return Post::processing($posts);
        }
    }
    public static function with_filename($filename)
    {
        return Post::where('img', 'LIKE', '%' . $filename . '%')->first();
    }
    public static function convert_date($unformated_date, $del = "/", $carbon = false)
    {
        $date = explode($del, $unformated_date);
        if($carbon){
            $date = Carbon::createFromDate($date[2], $date[1], $date[0]);
        } else {
            $date = Carbon::createFromDate($date[2], $date[1], $date[0])->toDateTimeString();
        }
        return $date;
    }
    public static function remove_post_from_collection($collection, $post)
    {
        $collection = $collection->filter(function ($value, $key) use($post){
            return $value->id != $post->id;
        });
        return $collection;
    }
    public static function similar_posts($postid)
    {
        // Basically, get the posts from all tags of the post.
        // If they are too many remove lower rated or/and lower viewed.

        $needed_similar_posts = blublog_setting('number_of_similar_post');
        $half_needed_similar_posts = $needed_similar_posts / 2;

        // Get the main post
        $mainpost = Post::find($postid);

        // Check if post do not have tags
        if (!isset($mainpost->tags[0]->id)) {
            $cat_post = Post::get_category_posts($mainpost->categories[0]->id);
            $cat_post = Post::remove_post_from_collection($cat_post,$mainpost);
            return $cat_post->shuffle();
        }

        // Make collection
        $similarpost = collect(new Post);

        // Add all posts from all tags in the collection
        foreach ($mainpost->tags as $tag) {
            foreach ($tag->posts as $post) {
                $similarpost->push($post);
            }
        }

        // Filter the collection. No duplicates. Remove main post from collection.
        $similarpost = $similarpost->unique('id')->shuffle();
        $similarpost = Post::remove_post_from_collection($similarpost,$mainpost);

        if($similarpost->count() <= $half_needed_similar_posts){
            // We have enough similar posts
            return $similarpost;
        }
        // We have too many similar post. Continue processing

        // Get average rating of all similar posts
        $rating_average = Post::get_rating_avg_of_posts_collection($similarpost);
        $rating_average = $rating_average - 0.5;

        // Remove posts with below average rating
        $similarpost_rating = $similarpost->filter(function ($value, $key) use($rating_average){
            if(Post::get_rating_avg($value)){
                return Post::get_rating_avg($value) >= $rating_average;
            }
        });

        if($similarpost_rating->count() >= $half_needed_similar_posts){
            // We have enough similar posts
            return $similarpost_rating->take($needed_similar_posts);
        }
        // We have too little posts with above avg rating.

        // Get average views of all similar posts
        $average_views = Post::get_views_avg_of_posts_collection($similarpost);
        $average_views = $average_views - 1.5;

        // Remove all posts that have below avg views from the collection
        $similarpost_views = $similarpost->filter(function ($value, $key) use($average_views){
            return $value['views']->count() >= $average_views;
        });

        // Merge posts with above average rating and posts with above views
        $ready_similarpost = $similarpost_views->merge($similarpost_rating)->unique('id')->take($needed_similar_posts);

        return  $ready_similarpost;

    }
    public static function get_category_posts($category_id, $limit = 10)
    {
        $category = Category::find($category_id);
        if($limit){
            $reletedposts = $category->posts()->latest()->take($limit)->get();
        } else {
            $reletedposts = $category->posts()->latest()->get();
        }
        return $reletedposts;
    }
    public static function get_rating_avg_of_posts_collection($posts)
    {
        $all_ratings = array();
        foreach ($posts as $post) {
            if(Post::get_rating_avg($post)){
                array_push($all_ratings, Post::get_rating_avg($post));
            }
        }
        return array_sum($all_ratings)/count($all_ratings);
    }
    public static function get_views_avg_of_posts_collection($posts)
    {
        $all_views = array();
        foreach ($posts as $post) {
            array_push($all_views,$post->views->count());
        }
        return array_sum($all_views)/count($all_views);
    }
    public static function get_rating_avg($post)
    {
        if($post->ratings->count()){
            $total = 0;
            foreach($post->ratings as $rate){
                $total = $total + $rate->rating;
            }
            $avg_stars = $total / $post->ratings->count();
        } else {
            $avg_stars = 0;
        }
        return $avg_stars;

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
    public static function public($posts)
    {
        $foo =0;
        foreach($posts as $post){
            if($post->status != "publish"){
                unset($posts[$foo]);
            }
            $foo++;
        }
        return $posts;
    }
    public static function processing($posts, $null = 0)
    {
        foreach ($posts as $post){
            $post->date = Carbon::parse($post->created_at)->format(blublog_setting('date_format'));
            $post->slug_url = "/" . config('blublog.blog_prefix') . "/posts/" . $post->slug;
            $post->img_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/' . $post->img);
            $post->img_thumb_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/thumbnail_' . $post->img);
            $post = Post::get_posts_stars($post,false);
            $post->author_url = url(config('blublog.blog_prefix') ) . "/author/". $post->user->name;
            $post->total_views = $post->views->count();
            $post->tags =$post->tags()->get() ;
            $post->categories = $post->categories()->get() ;
        }
        if(!$posts->count() and $null == 1){
            $posts = null;
        }
        return $posts;
    }

    public static function get_posts_stars($post, $show_avg = true)
    {
        if(blublog_setting('no_ratings')){
            return $post;
        }

        $total_ratings =  $post->ratings->count();
        if($total_ratings){
        $total = 0;
        foreach($post->ratings as $rate){
            $total = $total + $rate->rating;
        }
        $avg_stars = $total / $post->ratings->count();
        } else {
            $avg_stars = 0;
        }
        if(!$show_avg){
            $STARS_HTML = "";
        } else {
            $STARS_HTML = "<hr>";
        }
        for($i=1; $i<6; $i++){
            if($show_avg){
                $js_fun = "('" .$i . "_star')";
                $onclick = 'onclick="set_ratingto'. $js_fun . '"';
            } else {
                $onclick = "";
            }
            if($i <= $avg_stars){
                $STARS_HTML = $STARS_HTML . '<span '. ' style="color:#2780E3;"' . ' class="oi oi-star" id="' . $i . '_star"  '.$onclick.' ></span>';
            } else{
                $STARS_HTML = $STARS_HTML . '<span '. ' style="color:black;"' . ' class="oi oi-star" id="' . $i . '_star"  '.$onclick.' ></span>';
            }
        }
        if($show_avg){
            $STARS_HTML = $STARS_HTML . " (". round($avg_stars, 2) . ")" . '<p id="rating_info">Click on stars to rate this post.</p><hr>';
        } else {
            $STARS_HTML = $STARS_HTML . "";
        }
        $post->STARS_HTML = $STARS_HTML;
        return $post;
    }


    /*
        Get post from id
        If $user_id is false, returns post only if is publish.

        @param int $post_id
        @param int/bool $user_id

        @return mixed
    */
    public static function getpost($post_id, $user_id = false)
    {
        $post = Post::find($post_id);
        $post->img_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/' . $post->img);
        $post->img_thumb_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/thumbnail_' . $post->img);
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
            $posts = Post::processing($posts);
            return $posts;
            foreach ($posts as $post){
                $post = Post::get_posts_stars($post,false);
                $post->slug_url = "/" . config('blublog.blog_prefix') . "/posts/" . $post->slug;
                $post->img_url = Post::get_img_url($post->img);
                $post->img_thumb_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/thumbnail_' . $post->img);
                $post->date = Carbon::parse($post->created_at)->format(blublog_setting('date_format'));
                $post->total_views = $post->count();

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
    public static function by_slug($slug)
    {
        $post = Post::where([
            ['slug', '=', $slug],
            ['status', '=', "publish"],
        ])->first();
        return $post;
    }
    public static function get_numb_of_posts_in_tags($post)
    {
        foreach($post->tags as $tag){
            $tag->number_of_posts = $tag->posts()->where("status",'=','publish')->count();
        }
        return $post;
    }
    public static function get_img_url($img)
    {
        return Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/' . $img);;
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
    public static function delete_post_imgs($img)
    {
        $img_status = Storage::disk(config('blublog.files_disk', 'blublog'))->delete("posts/". $img);
        $path2 = 'posts/' . "thumbnail_" . $img;
        $thumbnail_img_status =Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path2);
        $path3 = 'posts/' . "blur_thumbnail_" . $img;
        $blur_img_status =Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path3);
        if($img_status and $thumbnail_img_status and $blur_img_status){
            return true;
        }
        return false;
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
