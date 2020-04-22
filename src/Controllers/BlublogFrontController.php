<?php

namespace   Philip1503\Blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\Post;
use Philip1503\Blublog\Models\Page;
use Philip1503\Blublog\Models\Category;
use Philip1503\Blublog\Models\Comment;
use Philip1503\Blublog\Models\Tag;
use Carbon\Carbon;
use Session;

class BlublogFrontController extends Controller
{
    // Front controller. All request from frontend come here.

    public function __construct()
    {
        $categories = Category::get();
        View::share('categories', $categories );

    }

    //Index page of the blog
    public function index()
    {
        $posts = Post::get_public_posts(blublog_setting('index_posts_per_page'));
        $path = "blublog::" . config('blublog.theme', 'blublog') . ".index";

        return view($path)->with('posts', $posts);
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
        $rules = [
            'search' => 'required|max:150',
        ];
        $this->validate($request, $rules);
        $search=  $request->search;

        $result = collect(new Post);

        $posts = Post::where([
            ['title', 'LIKE', '%' . $search . '%'],
        ])->latest()->get();

        foreach ($posts as $post) {
            $result->push($post);
        }

        $posts = Post::where([
            ['content', 'LIKE', '%' . $search . '%'],
        ])->latest()->get();


        foreach ($posts as $post) {
            $result->push($post);
        }

        $result = $result->unique('id');
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
        $posts = $category->posts()->latest()->paginate(blublog_setting('category_posts_per_page'));

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
        $posts = $tag->posts()->latest()->paginate(blublog_setting('tags_posts_per_page'));

        $posts = Post::processing($posts);

        $path = "blublog::" . config('blublog.theme', 'blublog') . ".tags.index";
        return view($path)->with('tag', $tag)->with('posts', $posts);
    }
    public function post_show($slug)
    {
        $post = Post::where([
            ['slug', '=', $slug],
        ])->first();

        if(!$post){
            abort(404);
        }
        $post->date = Carbon::parse($post->created_at)->format('d.m.Y');
        if($post->tag_id){
            $post->maintag_id = $post->tag_id;
        } else {
            if(isset($post->tags[0]->id)){
                $post->maintag_id = $post->tags[0]->id;
            }
        }
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
        return view($path)->with('post', $post)->with('comments', $comments);
    }
    public function comment_store(Request $request)
    {
        if(blublog_setting('disable_comments_modul')){
            return back();
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

                Session::flash('error', __('panel.max_unaproved_comments'));
                return back();
            }
            if($comments->count() == $limit){
                Session::flash('warning', __('panel.warning_unaproved_comments'));
            }
            //To DO: Ban user if are too many
        }

        Comment::addcomment($request, $ip);

        return back();
    }

}
