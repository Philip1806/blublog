<?php

namespace   Philip\blublog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Philip\Blublog\Models\Page;
use Philip\Blublog\Models\Post;
use Philip\Blublog\Models\Log;
use Session;

class BlublogPagesController extends Controller
{
    function __construct()
    {

    }
    public function index()
    {
        $pages = Page::where([
            ['public', '=', true],
        ])->latest()->paginate(10);
        $hidden_pages = Page::where([
            ['public', '=', false],
        ])->latest()->paginate(10);

        return view('blublog::panel.pages.index')->with('pages', $pages)->with('hidden_pages', $hidden_pages);
    }
    public function create()
    {
        return view('blublog::panel.pages.create');
    }
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:250',
            'descr' => 'required|max:250',
            'content' => 'required',
        ];
        $this->validate($request, $rules);



        $page = new Page;

        if($request->slug){
            $page->slug = $request->slug;
        } else {
            //Make slug from title
            $page->slug = Post::makeslug($request->title);

        }
        $page->title = $request->title;
        $page->img = $request->img;
        $page->descr = $request->descr;
        $page->tags = $request->tags;
        $page->content = $request->content;
        if($request->public){
            $page->public = true;
        } else {
            $page->public =false;
        }
        if($request->sidebar){
            $page->sidebar = true;
        } else {
            $page->sidebar =false;
        }
        $page->save();
        Session::flash('success', __('panel.page_added'));
        Log::add($request, "info", __('panel.page_added') );
        return redirect()->route('blublog.pages.index');
    }


    public function edit($id)
    {
        $post = Page::find($id);
        if(!$post){
            abort(404);
        }

        return view('blublog::panel.pages.edit')->with('post', $post);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|max:255',
            'descr' => 'required|max:255',
            'content' => 'required',
        ];
        $this->validate($request, $rules);

        $page = Page::find($id);
        if(!$page){
            Session::flash('error', __('panel.404'));
            Log::add($request, "error", __('panel.404') );
            return redirect()->route('blublog.pages.index');
        }
        if($request->slug){
            $page->slug = $request->slug;
        } else {
            $page->slug = Post::makeslug($request->title);
        }
        $page->title = $request->title;
        $page->img = $request->img;
        $page->descr = $request->descr;
        $page->tags = $request->tags;
        $page->content = $request->content;
        if($request->public){
            $page->public = true;
        } else {
            $page->public =false;
        }
        if($request->sidebar){
            $page->sidebar = true;
        } else {
            $page->sidebar =false;
        }
        $page->save();
        Session::flash('success', __('panel.page_edited'));
        Log::add($request, "info", __('panel.page_edited') );
        return redirect()->route('blublog.pages.index');

    }

    public function destroy($id)
    {

        $page = Page::find($id);
        if(!$page){
            Session::flash('error', __('panel.404'));
            Log::add('BlublogPagesController::destroy', "error", __('panel.404') );
            return redirect()->route('blublog.pages.index');
        }
        $page->delete();

        Session::flash('success', __('panel.page_deleted'));
        Log::add('BlublogPagesController::destroy', "info", __('panel.page_deleted') );
        return redirect()->route('blublog.pages.index');

    }



}
