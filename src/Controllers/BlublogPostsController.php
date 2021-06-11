<?php

namespace   Blublog\Blublog\Controllers;

use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Requests\PostRequest;
use Blublog\Blublog\Services\PostService;
use Illuminate\Support\Facades\Cache;
use Session;


class BlublogPostsController extends Controller
{
    protected $postService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PostService $postservice)
    {
        $this->postService = $postservice;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('blublog::panel.posts.index');
    }
    public function create()
    {
        $this->authorize('blublog_create_posts');
        return view('blublog::panel.posts.create');
    }
    public function edit($id)
    {
        $post = $this->postService->findById($id);
        $this->authorize('blublog_edit_post', $post);
        $post->status = array_search($post->status, config('blublog.post_status'));
        return view('blublog::panel.posts.edit')->with('post', $post);
    }
    public function show($id)
    {
        $post = $this->postService->findById($id);
        if (!$this->postService->canView($post)) {
            abort(403);
        }
        $this->postService->registerView($post);
        $post->viewsLogs = Log::postViews($id);
        return view('blublog::panel.posts.show')->with('post', $post);
    }
    public function store(PostRequest $request)
    {
        $this->authorize('blublog_create_posts');
        $this->postService->create($request);
        Cache::flush();
        Session::flash('success', "Post added.");
        return redirect()->route('blublog.panel.posts.index');;
    }

    public function update(PostRequest $request, $id)
    {
        $this->authorize('blublog_edit_post', $this->postService->findById($id));
        $this->postService->update($request, $id);
        Cache::flush();

        Session::flash('success', "Post edited.");
        return redirect()->route('blublog.panel.posts.index');;
    }
}
