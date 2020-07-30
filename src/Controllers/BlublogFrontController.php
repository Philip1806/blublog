<?php

namespace   Blublog\Blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Page;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Ban;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\PostsViews;
use App\User;
use Carbon\Carbon;
use Session;

class BlublogFrontController extends Controller
{
    // Front controller. All request from frontend come here.

    public function __construct()
    {
        if (!Cache::has('blublog.categories')){
            $categories = Category::get();
            Cache::put('blublog.categories', $categories,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $categories = Cache::get('blublog.categories');
        }
        View::share('categories', $categories );
        $ip = Post::getIp();
        if($ip != blublog_setting('ignore_ip')){
            Log::add('null', "visit");
        }
        if(Ban::is_banned($ip)){
            abort(403);
        }
        $codedir = resource_path('views/vendor/blublog/' . blublog_setting('theme') . '/code.php');
        if(file_exists ($codedir)){
            include_once $codedir;
        }
    }

    //Index page of the blog
    public function index()
    {
        $Page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if (!Cache::has('blublog.index_page.posts'. $Page)){
            if(blublog_setting('front_page_posts_only')){
                $posts = Post::for_front_page(blublog_setting('index_posts_per_page'));
                $front_page_posts = null;
            } else {
                $posts = Post::get_public_posts(blublog_setting('index_posts_per_page'));
                if(blublog_setting('add_front_page_posts')){
                    $front_page_posts = Post::for_front_page(blublog_setting('index_posts_per_page'));
                } else{
                    $front_page_posts = null;
                }
            }
            $posts->slider_posts = Post::slider();
            Cache::put('blublog.index_page.posts'. $Page, $posts,  now()->addMinutes(config('blublog.setting_cache')));
            Cache::put('blublog.index_page.front_page_posts', $front_page_posts,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $posts = Cache::get('blublog.index_page.posts'. $Page);
            $front_page_posts = Cache::get('blublog.index_page.front_page_posts');
        }

        $path = "blublog::" . blublog_setting('theme') . ".index";

        return view(blublog_get_view_path("index"))->with('posts', $posts)->with('front_page_posts', $front_page_posts);
    }
    public function author($name)
    {
        if (!Cache::has('blublog.author'. $name)){
            $user = User::where([
                ['name', '=', $name],
            ])->first();
            if(!$user){
                abort(404);
            }
            $posts = Post::where([
                ['user_id', '=', $user->id],
                ['status', '=', "publish"],
            ])->latest()->paginate(10);
            $posts = Post::processing($posts);
            $posts->author = $user;

            Cache::put('blublog.author'. $name, $posts,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $posts = Cache::get('blublog.author'. $name);
        }
        return view(blublog_get_view_path("author"))->with('posts', $posts);
    }
    public function page($slug)
    {
        if (!Cache::has('blublog.page.'. $slug)){
            $page = Page::where([
                ['slug', '=', $slug],
                ['public', '=', true],
            ])->first();
            if(!$page){
                abort(404);
            }
            Cache::put('blublog.page.'. $slug, $page);
        } else {
            $page = Cache::get('blublog.page.'. $slug);
        }
        return view(blublog_get_view_path("pages.show"))->with('page', $page);
    }
    public function search(Request $request)
    {
        if(blublog_setting('disable_search_modul')){
            Session::flash('error', __('blublog.search_turnoff'));
            return back();
        }
        $rules = [
            'search' => 'required|max:150',
        ];
        $this->validate($request, $rules);
        $search=  $request->search;

        $result = collect(new Post);

        $posts = Post::where([
            ['title', 'LIKE', '%' . $search . '%'],
            ['status', '=', "publish"],
        ])->latest()->get();

        foreach ($posts as $post) {
            $result->push($post);
        }

        $posts = Post::where([
            ['content', 'LIKE', '%' . $search . '%'],
            ['status', '=', "publish"],
        ])->latest()->get();


        foreach ($posts as $post) {
            $result->push($post);
        }

        $result = $result->unique('id')->take(30);

        $tags = Tag::get();
        $similar_tags = collect(new Tag);
        foreach($tags as $tag){
            similar_text($tag->title, $search, $percent);
            if($percent > 20.0){
                $similar_tags->push($tag);
            }
        }
        if(!isset($result[0])){
            $allposts = Cache::remember('blublog.all-posts', now()->addMinutes(config('blublog.setting_cache')), function() {
                return Post::all();
            });
            $result = $allposts->filter(function ($value, $key)use ($search){
                similar_text($value->title, $search, $percent);
                if($percent > 35.0){
                    return $value;
                }

                $words = explode(" ", $search);
                foreach($words as $word){
                    if (strpos($value->title, $word) !== false) {
                        return $value;
                        break;
                    }
                }
            });
        }
        $result = Post::processing($result);
        $path = "blublog::" . blublog_setting('theme') . ".posts.search";
        return view($path)->with('posts', $result)->with('search', $search)->with('similar_tags', $similar_tags->take(20));
    }
    public function category_show($slug)
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if (!Cache::has('blublog.category.'. $slug .'page'.$page)){
            $category = Category::by_slug($slug);
            if(!$category){
                abort(404);
            }
            $category->img_url = Category::get_img_url($category->img);
            Cache::put('blublog.category.'. $slug .'page'.$page, $category,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $category = Cache::get('blublog.category.'. $slug .'page'.$page);
        }
        return view(blublog_get_view_path("categories.index"))->with('category', $category);
    }
    public function tag_show($slug)
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if (!Cache::has('blublog.tag.'. $slug. 'page'.$page)){
            $tag = Tag::by_slug($slug);
            if(!$tag){
                abort(404);
            }
            Cache::put('blublog.tag.'. $slug. 'page'.$page, $tag,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $tag = Cache::get('blublog.tag.'. $slug. 'page'.$page);
        }
        return view(blublog_get_view_path("tags.index"))->with('tag', $tag);
    }
    public function post_show($slug)
    {
        if (!Cache::has('blublog.post.'. $slug)){
            $post = Post::by_slug($slug);
            if(!$post){
                abort(404);
            }
            PostsViews::add($post->id);
            $post->date = Carbon::parse($post->created_at)->format('d.m.Y');
            if($post->tag_id){
                $post->maintag_posts = Tag::get_tag_posts($post->tag_id,$post->id);
            } else {
                $post->maintag_posts = null;
            }
            $post = Post::get_posts_stars($post);
            $post->img_url = Post::get_img_url($post->img);
            $post->similar_posts =  Post::processing(Post::public(Post::similar_posts($post->id)));
            $post->total_views = $post->views->count();
            $post->author_url = url(config('blublog.blog_prefix') ) . "/author/". $post->user->name;
            Cache::put('blublog.post.'. $slug, $post);

        } else {
            $post = Cache::get('blublog.post.'. $slug);
        }

        if (!Cache::has('blublog.comments.'. $slug)){
            if($post->comments){
                $comments = $post->allcomments;
                foreach($comments as $comment){
                    if($comment->author){
                        $comment->border = "border-primary";
                    }
                }
            } else {
                $comments = null;
            }
            Cache::put('blublog.comments.'. $slug, $comments,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $comments = Cache::get('blublog.comments.'. $slug);
        }

        return view(blublog_get_view_path("posts.show"))->with('post', $post)->with('comments', $comments);
    }
    public function comment_store(Request $request)
    {
        if(blublog_setting('disable_comments_modul')){
            return back();
        }
        $ip = Post::getIp();
        if(Ban::is_banned_from_comments($ip)){
            abort(403);
        }
        if(blublog_setting('comment_ask_question')){
            if($request->get('question_answer') != blublog_setting('comment_spam_question_answer')){
                Session::flash('error', __('blublog.wrong_answer'));
                return back();
            }
        }

        $rules = [
            'name' => 'required|max:50',
            'comment_body' => 'required|max:1200',
            'email' => 'required|email',
        ];
        $this->validate($request, $rules);
        $ip = Post::getIp();

        if(blublog_setting('approve_comments_from_users_with_approved_comments')){
            $comments = Comment::where([
                ['ip', '=', $ip],
                ['public', '=', true],
            ])->first();
            if($comments){
                Post::remove_cache($request->get('post_id'));
                Comment::addcomment($request, $ip,0);
                return back();
            }
        }

        if(blublog_setting('max_unaproved_comments')){
            $comments = Comment::where([
                ['ip', '=', $ip],
                ['public', '=', false],
            ])->get();

            $limit = blublog_setting('max_unaproved_comments');
            if($comments->count() > $limit){
                if(Comment::limit_unapproved_comments_reached_soon()){
                    Ban::ip($ip,__('blublog.spam'), 1);
                    Log::add($request, "alert", __('blublog.spam') );
                    abort(403);
                }
                Log::add($request, "error", __('blublog.max_unaproved_comments') );
                Session::flash('error', __('blublog.max_unaproved_comments'));
                return back();
            }
            if($comments->count() == $limit){
                Log::add($request, "alert", __('blublog.warning_unaproved_comments') );
                Session::flash('warning', __('blublog.warning_unaproved_comments'));
            }
        }

        Comment::addcomment($request, $ip);

        return back();
    }

}
