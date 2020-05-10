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
        $categories = Category::get();
        View::share('categories', $categories );
        $ip = Post::getIp();
        if(Ban::is_banned($ip)){
            abort(403);
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

        $path = "blublog::" . config('blublog.theme', 'blublog') . ".index";

        return view($path)->with('posts', $posts)->with('front_page_posts', $front_page_posts);
    }
    public function author($name)
    {
        $user = User::where([
            ['name', '=', $name],
        ])->first();
        if($user){
            $posts = Post::where([
                ['user_id', '=', $user->id],
                ['status', '=', "publish"],
            ])->latest()->paginate(10);
            $posts = Post::processing($posts);
        }
        $path = "blublog::" . config('blublog.theme', 'blublog') . ".author";
        return view($path)->with('user', $user)->with('posts', $posts);
    }
    public function page($slug)
    {
        $page = Page::where([
            ['slug', '=', $slug],
            ['public', '=', true],
        ])->first();

        if(!$page){
            abort(404);
        }
        $path = "blublog::" . config('blublog.theme', 'blublog') . ".pages.show";
        return view($path)->with('page', $page);
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
        $result = Post::processing($result);

        $path = "blublog::" . config('blublog.theme', 'blublog') . ".posts.search";
        return view($path)->with('posts', $result)->with('search', $search);
    }
    public function category_show($slug)
    {
        $category = Category::where([
            ['slug', '=', $slug],
        ])->first();
        if(!$category){
            abort(404);
        }
        $posts = $category->posts()->where("status",'=','publish')->latest()->paginate(blublog_setting('category_posts_per_page'));
        $posts = Post::processing($posts);

        $path = "blublog::" . config('blublog.theme', 'blublog') . ".categories.index";
        return view($path)->with('category', $category)->with('posts', $posts);
    }
    public function tag_show($slug)
    {
        $tag = Tag::where([
            ['slug', '=', $slug],
        ])->first();
        if(!$tag){
            abort(404);
        }
        $posts = $tag->posts()->where("status",'=','publish')->latest()->paginate(blublog_setting('tags_posts_per_page'));
        $posts = Post::processing($posts);

        $path = "blublog::" . config('blublog.theme', 'blublog') . ".tags.index";
        return view($path)->with('tag', $tag)->with('posts', $posts);
    }
    public function post_show($slug)
    {
        $post = Post::where([
            ['slug', '=', $slug],
            ['status', '=', "publish"],
        ])->first();

        if(!$post){
            abort(404);
        }
        PostsViews::add($post->id);
        $post->date = Carbon::parse($post->created_at)->format('d.m.Y');
        if($post->tag_id){
            $maintag_posts = Tag::get_tag_posts($post->tag_id,$post->id);
        } else {
            $maintag_posts = null;
        }
        $post = Post::get_posts_stars($post);
        $post->similar_posts =  Post::processing(Post::public(Post::similar_posts($post->id)));
        $post->total_views = $post->views->count();
        $post->author_url = url(config('blublog.blog_prefix') ) . "/author/". $post->user->name;
        //TO DO get maintag with its 5 last posts
        if($post->comments){
            $comments = $post->allcomments;
            foreach($comments as $comment){
                if($comment->author){
                    $comment->border = "border-primary";
                }
            }
        } else {
            //In case theme do no check if there is comments
            $comments = null;
        }
        $path = "blublog::" . config('blublog.theme', 'blublog') . ".posts.show";
        return view($path)->with('post', $post)->with('maintag_posts', $maintag_posts)->with('comments', $comments);
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

        $rules = [
            'name' => 'required|max:50',
            'comment_body' => 'required|max:1200',
            'email' => 'required|email',
        ];
        $this->validate($request, $rules);
        $ip = Post::getIp();

        if(blublog_setting('approve_comments_from_users_with_approved_comments')){
            $post = Comment::where([
                ['ip', '=', $ip],
                ['public', '=', true],
            ])->first();
            if($post){
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
