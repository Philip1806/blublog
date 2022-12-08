<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\Tag;
use Session;
use Auth;
use Blublog\Blublog\Requests\CommentRequest;
use Blublog\Blublog\Services\CategoryService;
use Blublog\Blublog\Services\PostService;
use Blublog\Blublog\Services\TagService;
use Illuminate\Database\Eloquent\Collection;

class BlublogFrontController extends Controller
{
    protected $postService;
    protected $categoryService;
    public function __construct(PostService $postservice, CategoryService $categoryservice, TagService $tagservice)
    {
        $this->postService = $postservice;
        $this->categoryService = $categoryservice;
        $this->tagService = $tagservice;

        Paginator::useBootstrap();
        if (!Cache::has('blublog.rec_posts')) {
            $rec_posts = $this->postService->recommended();
            Cache::put('blublog.rec_posts', $rec_posts,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $rec_posts = Cache::get('blublog.rec_posts');
        }
        View::share('rec_posts', $rec_posts);

        if (!Cache::has('blublog.categories')) {

            $categories = $this->categoryService->topCategories();
            Cache::put('blublog.categories', $categories,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $categories = Cache::get('blublog.categories');
        }
        View::share('categories', $categories);
    }

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if (!Cache::has('blublog.index.page' . $page)) {
            $posts = $this->postService->withStatus('publish');
            Cache::put('blublog.index.page' . $page, $posts,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $posts = Cache::get('blublog.index.page' . $page);
        }
        return view('blublog::front.index')->with('posts', $posts);
    }
    public function category($slug)
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if (!Cache::has('blublog.category.' . $slug . 'page' . $page)) {
            $category = $this->categoryService->bySlug($slug);

            $posts = $this->categoryService->getPosts($category);

            Cache::put('blublog.category.' . $slug . 'page' . $page, $category,  now()->addMinutes(config('blublog.setting_cache')));
            Cache::put('blublog.category.' . $slug . '.posts.page' . $page, $posts,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $category = Cache::get('blublog.category.' . $slug . 'page' . $page);
            $posts = Cache::get('blublog.category.' . $slug . '.posts.page' . $page);
        }
        return view('blublog::front.category')->with('category', $category)->with('posts', $posts);
    }
    public function tag($slug)
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if (!Cache::has('blublog.tag.' . $slug . 'page' . $page)) {
            $tag = $this->tagService->bySlug($slug);
            $posts = $this->tagService->getPosts($tag);
            Cache::put('blublog.tag.' . $slug . 'page' . $page, $tag,  now()->addMinutes(config('blublog.setting_cache')));
            Cache::put('blublog.tag.' . $slug . '.posts.page' . $page, $posts,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $tag = Cache::get('blublog.tag.' . $slug . 'page' . $page);
            $posts = Cache::get('blublog.tag.' . $slug . '.posts.page' . $page);
        }
        return view('blublog::front.tag')->with('tag', $tag)->with('posts', $posts);
    }
    public function show($slug)
    {
        if (!Cache::has('blublog.post.' . $slug)) {
            $post = $this->postService->bySlug($slug);
            $this->postService->registerView($post);
            $post->similar = $this->postService->similarPosts($post);
            if ($post->tag_id) {
                $post->fromThisTopic = $this->postService->onThisTopic($post);
            }
            $comments = $post->comments()->where('public', '=', true)->get();
            Cache::put('blublog.post.' . $slug, $post,  now()->addMinutes(config('blublog.setting_cache')));
            Cache::put('blublog.post.comments.' . $slug, $comments,  now()->addMinutes(config('blublog.setting_cache')));
        } else {
            $post = Cache::get('blublog.post.' . $slug);
            $comments = Cache::get('blublog.post.comments.' . $slug);
        }

        return view('blublog::front.post')->with('post', $post)->with('comments', $comments);
    }
    public function like($slug)
    {
        $post = $this->postService->bySlug($slug);
        $this->postService->like($post);
        Session::flash('success', "Post liked.");
        Cache::forget('blublog.post.' . $slug);
        return back();
    }
    public function search()
    {
        // If no search parameter is provided, abort with a 404 error
        if (!isset($_GET['search'])) {
            abort(404);
        }

        $search = $_GET['search'];
        Log::add($search, 'info', 'Search used');

        $result = $this->postService->search($search, 'publish', false);

        // Keep only unique results and take the first 30
        $result = $result->unique('id')->take(30);

        // Get all tags and find the ones that are similar to the search term
        $tags = $this->tagService->getAll();
        $similar_tags = $this->findSimilarTags($tags, $search);

        // Return the view with the search results and similar tags
        return view('blublog::front.search')->with('posts', $result)->with('tags', $similar_tags);
    }
    private function findSimilarTags(Collection $tags, string $search)
    {
        $similar_tags = collect(new Tag);

        foreach ($tags as $tag) {
            // Calculate the similarity between the search term and the current tag
            similar_text($tag->title, $search, $percent);

            // If the similarity is above 20%, add the tag to the collection
            if ($percent > config('blublog.tags-search-similarity', 20.0)) {
                $similar_tags->push($tag);
            }
        }

        // Keep only unique tags and take the first 20
        return $similar_tags->unique('id')->take(config('blublog.tags-search-limit', 20));
    }
    public function comment_store(CommentRequest $request)
    {
        if (!config("blublog.allow-comments")) {
            return abort(403);
        }
        if (!Auth::check() and ($request->get('question_answer') != config('blublog.spam-question-answer'))) {
            Session::flash('error', 'Wrong answer.');
            return back();
        }
        Comment::addComment($request);
        Cache::flush();
        return back();
    }
}
