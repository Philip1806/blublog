<?php

namespace   Philip\blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip\Blublog\Models\Post;
use Philip\Blublog\Models\Category;
use Philip\Blublog\Models\Comment;
use Philip\Blublog\Models\Tag;
use Illuminate\Support\Facades\Storage;
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

        if($post->tag_id){
            $post->maintag_id = $post->tag_id;
        } else {
            $post->maintag_id = $post->tags[0]->id;
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
        $rules = [
            'name' => 'required|max:50',
            'comment_body' => 'required|max:1200',
            'email' => 'required|email',
        ];
        $this->validate($request, $rules);

        $ip = Post::getIp();
        Comment::addcomment($request, $ip);

        return back();
    }

}
