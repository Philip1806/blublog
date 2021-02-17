<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Tag;
use Session;


class BlublogBackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("blublog::panel.index", [
            'total_images' => File::all()->count(),
            'total_comments' => Comment::all()->count(),
            'total_posts' => Post::all()->count(),
            'my_posts' => Post::where('user_id', '=', auth()->user()->id)->count(),
        ]);
    }
    public function tags()
    {
        return view('blublog::panel.tags');
    }
    public function tagsUpdate(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('blublog_edit_tags', $tag);
        $tag->update($request->all());
        Session::flash('success', "Tag edited.");
        return back();
    }
    public function images()
    {
        return view('blublog::panel.images');
    }
    public function logs()
    {
        return view('blublog::panel.logs.index');
    }
    public function logsShow($id)
    {
        $log = Log::findOrFail($id);
        return view('blublog::panel.logs.show')->with('log', $log);
    }
}
