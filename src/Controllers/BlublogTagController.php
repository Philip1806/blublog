<?php

namespace   Philip1503\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\Post;
use Illuminate\Support\Facades\Storage;
use Philip1503\Blublog\Models\File;
use Philip1503\Blublog\Models\Tag;
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
        $rules = [
            'title' => 'required|max:200',
        ];
        $this->validate($request, $rules);

            //Make slug from title
            $numb = rand(0, 999);
            $slug = $request->title;
            $slug = str_replace( " ", "-", $slug);
            $slug = preg_replace("/[^A-Za-z0-9\p{Cyrillic}-]/u","",$slug);
            $slug = $slug . "-" . $numb ;


        $tag = new Tag;
        $tag->title = $request->title;
        $tag->descr = $request->descr;
        $tag->slug = $slug;


        $tag->save();

        Session::flash('success', 'Успешно добавено!');
        return redirect()->route('blublog.tags.index');
    }
        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);
        return view('blublog::panel.tags.edit')->with('tag', $tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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

        Session::flash('success', 'Успешна редакция!');
        return redirect()->back();
    }

    public function destroy ($id)
    {
        $tag = Tag::find($id);
        if($tag){
            $tag->posts()->detach();
            $tag->delete();
            Session::flash('success', __('panel.contentdelete'));
        }
        return redirect()->back();
    }

}
