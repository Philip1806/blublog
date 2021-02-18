<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Session;
use Auth;

class BlublogFrontController extends Controller
{

    public function __construct()
    {
        Paginator::useBootstrap();
        if (!Cache::has('blublog.rec_posts')) {
            $rec_posts = Post::recommended();
            //Cache::put('blublog.rec_posts', $rec_posts,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $rec_posts = Cache::get('blublog.rec_posts');
        }
        View::share('rec_posts', $rec_posts);

        if (!Cache::has('blublog.categories')) {
            $categories = Category::with('childrenRecursive')->whereNull('parent_id')->get();
            Cache::put('blublog.categories', $categories,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $categories = Cache::get('blublog.categories');
        }
        View::share('categories', $categories);
    }

    public function index()
    {

        return view('blublog::front.index')->with('posts', Post::withStatus('publish')->latest()->paginate(5));
    }
    public function category($slug)
    {
        $category = Category::where([
            ['slug', '=', $slug],
        ])->first();
        if (!$category) {
            abort(404);
        }
        $posts = $category->getPosts()->paginate(5);
        return view('blublog::front.category')->with('category', $category)->with('posts', $posts);
    }
    public function tag($slug)
    {
        $tag = Tag::where([
            ['slug', '=', $slug],
        ])->first();
        if (!$tag) {
            abort(404);
        }
        $posts = $tag->getPosts()->paginate(5);
        return view('blublog::front.tag')->with('tag', $tag)->with('posts', $posts);
    }
    public function show($slug)
    {
        $post = Post::bySlug($slug);
        $post->registerView();
        $comments = $post->comments()->where('public', '=', true)->get();
        return view('blublog::front.post')->with('post', $post)->with('comments', $comments);
    }
    public function like($slug)
    {
        $post = Post::bySlug($slug);
        $post->like();
        Session::flash('success', "Post liked.");
        return back();
    }
    public function search(Request $request)
    {
        $rules = [
            'search' => 'required|min:3|max:150',
        ];
        $this->validate($request, $rules);
        $search =  $request->search;
        Log::add($request->search, 'info', 'Search used');
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
        foreach ($tags as $tag) {
            similar_text($tag->title, $search, $percent);
            if ($percent > 20.0) {
                $similar_tags->push($tag);
            }
        }
        return view('blublog::front.search')->with('posts', $result)->with('tags', $similar_tags);
    }
    public function comment_store(Request $request)
    {

        $rules = [
            'name' => 'required|max:50',
            'comment_body' => 'required|max:9200',
            'email' => 'required|email',
        ];
        $this->validate($request, $rules);

        if (!Auth::check()) {
            if ($request->get('question_answer') != config('blublog.spam-question-answer')) {
                Session::flash('error', 'Wrong answer.');
                return back();
            }
        }
        Comment::addComment($request);

        return back();
    }
}
