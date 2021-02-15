<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\File;
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
        return view('blublog::panel.index');
    }
    public function tags()
    {
        return view('blublog::panel.tags');
    }
    public function tagsUpdate(Request $request, $id)
    {
        $this->authorize('blublog_edit_tags');
        $tag = Tag::findOrFail($id);
        $tag->update($request->all());
        Session::flash('success', "Tag edited.");
        return back();
    }
    public function images()
    {
        return view('blublog::panel.images');
    }
}
