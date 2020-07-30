<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\BlublogUser;
use Session;

class BlublogTagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->paginate(14);

        return view("blublog::panel.tags.index")->with('tags', $tags);
    }
    public function store(Request $request)
    {
        BlublogUser::check_access('create', Tag::class);
        $rules = [
            'title' => 'required|max:200',
        ];
        $this->validate($request, $rules);

        $tag = new Tag;
        if($request->slug){
            $tag->slug = $request->slug;
        } else {
            $tag->slug = Post::makeslug($request->title);
        }
        $tag->title = $request->title;
        $tag->descr = $request->descr;
        $tag->save();
        Log::add($request, "info", __('blublog.contentcreate') );
        Session::flash('success',  __('blublog.contentcreate'));
        return redirect()->route('blublog.tags.index');
    }
    /**
     * Show the form for editing tag.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        BlublogUser::check_access('update', Tag::class);
        $tag = Tag::find($id);
        return view('blublog::panel.tags.edit')->with('tag', $tag);
    }

    /**
     * Update tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        BlublogUser::check_access('update', Tag::class);
        $rules = [
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
        ];
        $this->validate($request, $rules);

        $tag = Tag::find($id);
        $tag->title = $request->title;
        $tag->slug = $request->slug;
        $tag->descr = $request->descr;
        $tag->save();
        Log::add($request, "info", __('blublog.contentedit') );
        Session::flash('success', __('blublog.contentedit'));
        return redirect()->back();
    }

    public function destroy ($id)
    {
        BlublogUser::check_access('delete', Tag::class);
        $tag = Tag::find($id);
        if($tag){
            $tag->posts()->detach();
            $tag->delete();
            Session::flash('success', __('blublog.contentdelete'));
            Log::add($id . "BlublogTagController::destroy", "info", __('blublog.contentdelete'));
        }
        return redirect()->back();
    }

}
