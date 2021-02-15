<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Comment;
use Carbon\Carbon;
use Blublog\Blublog\Models\Rate;
use Blublog\Blublog\Models\PostsViews;
use Session;
use Auth;

class Post extends Model
{
    protected $table = 'blublog_posts';

    public function user()
    {
        return $this->belongsTo(BlublogUser::class);
    }
    public function views()
    {
        return $this->hasMany(PostsViews::class, 'post_id');
    }
    public function ratings()
    {
        return $this->hasMany(Rate::class, 'post_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blublog_posts_categories', 'post_id', 'category_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blublog_posts_tags');
    }
    public function allcomments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
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
    public static function by_views($paginate = true)
    {
        $posts = Post::all_posts();
        $posts = $posts->sortBy('total_views');
        if ($paginate) {
            $posts =  collect($posts)->paginate(15, null, 'views');
        }
        return $posts;
    }
    public static function by_author($paginate = true)
    {
        $posts = Post::all_posts();
        $posts = $posts->sortBy('user_id');
        if ($paginate) {
            $posts =  collect($posts)->paginate(15, null, 'author');
        }
        return $posts;
    }

    public static function remove_cache($post_id)
    {
        $post = Post::find($post_id);
        Cache::forget('blublog.post.' . $post->slug);
        Cache::forget('blublog.comments.' . $post->slug);
    }
    public static function author_name($post)
    {
        if ($post->user->full_name) {
            return $post->user->full_name;
        } else {
            return $post->user->name;
        }
    }
    public static function rating_votes($post)
    {
        $five_star = 0;
        $four_star = 0;
        $three_star = 0;
        $two_star = 0;
        $one_star = 0;
        foreach ($post->ratings as $rating) {
            if ($rating->rating == 5) {
                $five_star++;
            } elseif ($rating->rating == 4) {
                $four_star++;
            } elseif ($rating->rating == 3) {
                $three_star++;
            } elseif ($rating->rating == 2) {
                $two_star++;
            } else {
                $one_star++;
            }
        }
        return array(
            'five_star' => $five_star,
            'four_star' => $four_star,
            'three_star' => $three_star,
            'two_star' => $two_star,
            'one_star' => $one_star,
        );
    }
    public static function recommended($limit = 20)
    {
        $posts = Post::where([
            ['recommended', '=', true],
            ['status', '=', "publish"],
        ])->latest()->limit($limit)->get();
        if ($posts) {
            return Post::processing($posts);
        }
    }
    public static function slider()
    {
        $posts = Post::where([
            ['slider', '=', true],
            ['status', '=', "publish"],
        ])->latest()->get();
        if ($posts) {
            return Post::processing($posts);
        }
    }
    public static function for_front_page($limit = 20)
    {
        $posts = Post::where([
            ['front', '=', true],
            ['status', '=', "publish"],
        ])->latest()->paginate($limit);
        if ($posts) {
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
        if ($carbon) {
            $date = Carbon::createFromDate($date[2], $date[1], $date[0]);
        } else {
            $date = Carbon::createFromDate($date[2], $date[1], $date[0])->toDateTimeString();
        }
        return $date;
    }
    public static function remove_post_from_collection($collection, $post)
    {
        $collection = $collection->filter(function ($value, $key) use ($post) {
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
            $cat_post = Post::remove_post_from_collection($cat_post, $mainpost);
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
        $similarpost = Post::remove_post_from_collection($similarpost, $mainpost);

        if ($similarpost->count() <= $half_needed_similar_posts) {
            // We have enough similar posts
            return $similarpost;
        }
        // We have too many similar post. Continue processing

        // Get average rating of all similar posts
        $rating_average = Post::get_rating_avg_of_posts_collection($similarpost);
        $rating_average = $rating_average - 0.5;

        // Remove posts with below average rating
        $similarpost_rating = $similarpost->filter(function ($value, $key) use ($rating_average) {
            if (Post::get_rating_avg($value)) {
                return Post::get_rating_avg($value) >= $rating_average;
            }
        });

        if ($similarpost_rating->count() >= $half_needed_similar_posts) {
            // We have enough similar posts
            return $similarpost_rating->take($needed_similar_posts);
        }
        // We have too little posts with above avg rating.

        // Get average views of all similar posts
        $average_views = Post::get_views_avg_of_posts_collection($similarpost);
        $average_views = $average_views - 1.5;

        // Remove all posts that have below avg views from the collection
        $similarpost_views = $similarpost->filter(function ($value, $key) use ($average_views) {
            return $value['views']->count() >= $average_views;
        });

        // Merge posts with above average rating and posts with above views
        $ready_similarpost = $similarpost_views->merge($similarpost_rating)->unique('id')->take($needed_similar_posts);

        return  $ready_similarpost;
    }
    public static function get_category_posts($category_id, $limit = 10)
    {
        $category = Category::find($category_id);
        if ($limit) {
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
            if (Post::get_rating_avg($post)) {
                array_push($all_ratings, Post::get_rating_avg($post));
            }
        }
        if (count($all_ratings)) {
            return array_sum($all_ratings) / count($all_ratings);
        } else {
            return 0;
        }
    }
    public static function get_views_avg_of_posts_collection($posts)
    {
        $all_views = array();
        foreach ($posts as $post) {
            array_push($all_views, $post->views->count());
        }
        return array_sum($all_views) / count($all_views);
    }
    public static function get_rating_avg($post)
    {
        if ($post->ratings->count()) {
            $total = 0;
            foreach ($post->ratings as $rate) {
                $total = $total + $rate->rating;
            }
            $avg_stars = $total / $post->ratings->count();
        } else {
            $avg_stars = 0;
        }
        return $avg_stars;
    }
    public static function output_post_status($post_status)
    {
        $BlubloUser = BlublogUser::get_user(Auth::user());
        if ($BlubloUser->user_role->is_admin or $BlubloUser->user_role->is_mod) {
            return $post_status;
        }
        if ($BlubloUser->user_role->posts_wait_for_approve) {
            $posts_count = Post::where([
                ['status', '=', 'publish'],
                ['user_id', '=', $BlubloUser->id],
            ])->count();
            if ($posts_count > blublog_setting('number_of_approved_post')) {
                return $post_status;
            } else {
                Session::flash('warning', __('blublog.needs_approve'));
                return "draft";
            }
        }
        return $post_status;
    }
    public static function next_post_id()
    {
        if (!is_null(Post::latest()->first())) {
            $lastPost = Post::latest()->first();
        } else {
            return 1;
        }
        return ++$lastPost->id;
    }
    public static function make_seo_descr($content)
    {
        return mb_strimwidth(strip_tags($content), 0, 155, "...");
    }
    public static function make_seo_title($title)
    {
        return mb_strimwidth($title, 0, 60, null);
    }
    public static function makeslug($title, $convert = true)
    {
        $cyr = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        ];
        $lat = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
        ];
        $numb = rand(0, 999) . Post::next_post_id();
        $slug = str_replace(" ", "-", $title);
        $slug = preg_replace("/[^A-Za-z0-9\p{Cyrillic}-]/u", "", $slug);
        $slug = $slug . "-" .  $numb;
        $slug = mb_strimwidth($slug, 0, 70, null);
        if ($convert) {
            $slug = str_replace($cyr, $lat, $slug);
        }
        return $slug;
    }
    public static function public($posts)
    {
        $foo = 0;
        foreach ($posts as $post) {
            if ($post->status != "publish") {
                unset($posts[$foo]);
            }
            $foo++;
        }
        return $posts;
    }
    public static function all_posts()
    {
        $posts = Post::where([
            ['status', '=', 'publish'],
        ])->latest()->get();
        return  Post::processing($posts);
    }
    public static function processing($posts, $null = 0)
    {
        foreach ($posts as $post) {
            $post->date = Carbon::parse($post->created_at)->format(blublog_setting('date_format'));
            $post->slug_url = "/" . config('blublog.blog_prefix') . "/posts/" . $post->slug;
            $post->img_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/' . $post->img);
            $post->img_thumb_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/thumbnail_' . $post->img);
            $post->img_blur_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/blur_thumbnail_' . $post->img);
            $post = Post::get_posts_stars($post, false);
            $post->author_url = url(config('blublog.blog_prefix')) . "/author/" . $post->user->name;
            $post->total_views = $post->views->count();
            $post->tags = $post->tags()->get();
            $post->categories = $post->categories()->get();
            if (blublog_setting('use_rating_module_as_likes_and_dislikes')) {
                $post = Post::get_likes_dislikes($post);
            }
        }
        if (!$posts->count() and $null == 1) {
            $posts = null;
        }
        return $posts;
    }
    public static function get_likes_dislikes($post)
    {
        $likes = 0;
        $dislikes = 0;
        foreach ($post->ratings as $rating) {
            if ($rating->rating == 5) {
                $likes++;
            }
            if ($rating->rating == 1) {
                $dislikes++;
            }
        }
        $post->likes = $likes;
        $post->dislikes = $dislikes;
        return $post;
    }
    public static function get_posts_stars($post, $show_avg = true)
    {
        if (blublog_setting('no_ratings')) {
            return $post;
        }

        $avg_stars = Post::get_rating_avg($post);

        $STARS_HTML = "";

        for ($i = 1; $i < 6; $i++) {
            if ($show_avg) {
                $js_fun = "('" . $i . "_star')";
                $onclick = 'onclick="set_ratingto' . $js_fun . '"';
            } else {
                $onclick = "";
            }
            if ($i <= $avg_stars) {
                $STARS_HTML = $STARS_HTML . '<span ' . ' style="color:#2780E3;"' . ' class="oi oi-star" id="' . $i . '_star"  ' . $onclick . ' ></span>';
            } else {
                $STARS_HTML = $STARS_HTML . '<span ' . ' style="color:black;"' . ' class="oi oi-star" id="' . $i . '_star"  ' . $onclick . ' ></span>';
            }
        }
        if ($show_avg) {
            $STARS_HTML = $STARS_HTML . " (" . round($avg_stars, 2) . ")" . '<p id="rating_info">' . __('blublog.give_rating') . '</p>';
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
    public static function getpost($post_id)
    {
        $post = Post::find($post_id);
        $post->img_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/' . $post->img);
        $post->img_thumb_url = Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/thumbnail_' . $post->img);
        if (!$post) {
            return abort(404);
        }
        return $post;
    }

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
        if ($post->count() > 1) {
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
        if ($posts) {
            $posts = Post::processing($posts);
            return $posts;
            foreach ($posts as $post) {
                $post = Post::get_posts_stars($post, false);
                $post->slug_url = "/" . config('blublog.blog_prefix') . "/posts/" . $post->slug;
                $post->img_url = Post::get_img_url($post->img);
                $post->img_thumb_url =  Post::get_thumb_url($post->img);
                $post->date = Carbon::parse($post->created_at)->format(blublog_setting('date_format'));
                $post->total_views = $post->count();

                if ($post->tag_id) {
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
        foreach ($post->tags as $tag) {
            $tag->number_of_posts = $tag->posts()->where("status", '=', 'publish')->count();
        }
        return $post;
    }
    public static function get_img_url($img)
    {
        return Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/' . $img);;
    }
    public static function get_thumb_url($img)
    {
        return Storage::disk(config('blublog.files_disk', 'blublog'))->url('posts/thumbnail_' . $img);;
    }

    public static function check_if_files_uploaded($mainfile, $thumbnail_file, $blur_thumbnail_file)
    {
        $message = '';
        if (!$mainfile) {
            $message = $message . "Main file was not uploaded. ";
        }
        if (!$thumbnail_file) {
            $message = $message . "Thumbnail file was not uploaded. ";
        }
        if (!$blur_thumbnail_file) {
            $message = $message . "Blur thumbnail file was not uploaded. ";
        }
        if (!$mainfile or !$thumbnail_file or !$blur_thumbnail_file) {
            $message = $message . "Check if you're set up blublog file driver in config/filesystems and blublog settings.";
            Session::flash('warning', $message);
            return false;
        }
        return true;
    }
    public static function delete_post_imgs($img)
    {
        $img_status = Storage::disk(config('blublog.files_disk', 'blublog'))->delete("posts/" . $img);
        $path2 = 'posts/' . "thumbnail_" . $img;
        $thumbnail_img_status = Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path2);
        $path3 = 'posts/' . "blur_thumbnail_" . $img;
        $blur_img_status = Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path3);
        if ($img_status and $thumbnail_img_status and $blur_img_status) {
            return true;
        }
        return false;
    }
    public static function edit_by_id($request, $id)
    {
        $post = Post::find($id);
        BlublogUser::check_access('update', $post);
        if ($request->file) {
            if ($post->img != "no-img.png") {
                //The post had a image before.
                if (!Post::img_used_by_other_post($post->id)) {
                    // Old post img is not used by other posts. Image can be deleted.
                    $file = File::where([
                        ['filename', '=', "posts/" . $post->img],
                    ])->first();
                    if ($file) {
                        $file->delete();
                    }
                    Post::delete_post_imgs($post->img);
                }
            }
            $address = File::handle_img_upload($request);
        } elseif ($request->customimg != "") {
            $address = $request->customimg;
        } else {
            $address = $post->img;
        }
        $post->title = $request->title;
        if ($request->seo_title) {
            $post->seo_title = $request->seo_title;
        } else {
            $post->seo_title = Post::make_seo_title($request->title);
        }
        if ($request->seo_descr) {
            $post->seo_descr = $request->seo_descr;
        } else {
            $post->seo_descr = Post::make_seo_descr($request->content);
        }
        $post->headlight = $request->headlight;
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        if ($request->slug) {
            $post->slug = $request->slug;
        }
        $post->status = Post::output_post_status($request->status);
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
        if ($request->comments) {
            $post->comments = true;
        } else {
            $post->comments = false;
        }
        if ($request->main_tag_id) {
            $post->tag_id = $request->main_tag_id;
        }
        if ($request->slider) {
            $post->slider = true;
        } else {
            $post->slider = false;
        }
        if ($request->new_date) {
            $post->created_at = Post::convert_date($request->new_date);
        }
        $post->img = $address;
        $post->save();
        return $post;
    }
    public static function create_new($request)
    {
        if ($request->file) {
            $address = File::handle_img_upload($request);
        } elseif ($request->customimg != "") {
            $address = $request->customimg;
        } else {
            $address = "no-img.png";
        }
        $user = BlublogUser::get_user(Auth::user());
        $post = new Post;
        $post->user_id = $user->id;
        $post->img = $address;
        $post->title = $request->title;
        if ($request->seo_title) {
            $post->seo_title = $request->seo_title;
        } else {
            $post->seo_title = Post::make_seo_title($request->title);
        }
        if ($request->seo_descr) {
            $post->seo_descr = $request->seo_descr;
        } else {
            $post->seo_descr = Post::make_seo_descr($request->content);
        }
        $post->headlight = $request->headlight;
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        $post->slug = Post::makeslug($request->title);
        $post->status = Post::output_post_status($request->status);
        if ($request->front) {
            $post->front = true;
        } else {
            $post->front = false;
        }
        if ($request->main_tag_id) {
            $post->tag_id = $request->main_tag_id;
        }
        if ($request->recommended) {
            $post->recommended = true;
        } else {
            $post->recommended = false;
        }

        if ($request->comments) {
            $post->comments = true;
        } else {
            $post->comments = false;
        }

        if ($request->slider) {
            $post->slider = true;
        } else {
            $post->slider = false;
        }
        if ($request->new_date) {
            $post->created_at = Post::convert_date($request->new_date);
        }
        $post->save();
        return $post;
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
