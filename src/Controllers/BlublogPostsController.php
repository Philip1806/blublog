<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\Post;
use Session;


class BlublogPostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $post =  Post::findOrFail($id);
        $post->registerView();
        $post->like();
        $this->authorize('blublog_edit_post', $post);
        $post->status = array_search($post->status, config('blublog.post_status'));
        return view('blublog::panel.posts.edit')->with('post', $post);
    }
    public function show($id)
    {
        $post =  Post::findOrFail($id);
        $post->viewsLogs = Log::postViews($id);
        return view('blublog::panel.posts.show')->with('post', $post);
    }
    public function store(Request $request)
    {
        $this->authorize('blublog_create_posts');
        $rules = [
            'title' => 'required|max:250',
            'categories' => 'required',
            'content' => 'min:1',
        ];
        $this->validate($request, $rules);

        Post::createPost($request);

        Session::flash('success', "Post added.");
        return redirect()->route('blublog.panel.posts.index');;
    }

    public function update(Request $request, $id)
    {
        $this->authorize('blublog_edit_post', Post::findOrFail($id));
        $rules = [
            'title' => 'required|max:250',
            'categories' => 'required',
            'content' => 'min:1',
        ];
        $this->validate($request, $rules);

        Post::updatePost($request, $id);

        Session::flash('success', "Post edited.");
        return redirect()->route('blublog.panel.posts.index');;
    }
}
