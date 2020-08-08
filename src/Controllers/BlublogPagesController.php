<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Page;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Log;
use Session;

class BlublogPagesController extends Controller
{
    function __construct()
    {
    }
    public function index()
    {
        BlublogUser::check_access('view', Page::class);
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
        BlublogUser::check_access('create', Page::class);
        return view('blublog::panel.pages.create');
    }
    public function store(Request $request)
    {
        BlublogUser::check_access('create', Page::class);
        $rules = [
            'title' => 'required|max:250',
            'descr' => 'required|max:250',
            'content' => 'required',
        ];
        $this->validate($request, $rules);

        $page = new Page;
        $page = Page::handle_request($page, $request);
        $page->save();
        Session::flash('success', __('blublog.page_added'));
        Log::add($request, "info", __('blublog.page_added'));
        return redirect()->route('blublog.pages.index');
    }


    public function edit($id)
    {
        BlublogUser::check_access('update', Page::class);
        $post = Page::find($id);
        if (!$post) {
            abort(404);
        }

        return view('blublog::panel.pages.edit')->with('post', $post);
    }

    public function update(Request $request, $id)
    {
        BlublogUser::check_access('update', Page::class);
        $rules = [
            'title' => 'required|max:255',
            'descr' => 'required|max:255',
            'content' => 'required',
        ];
        $this->validate($request, $rules);

        $page = Page::find($id);
        $page = Page::handle_request($page, $request);
        $page->save();
        Cache::forget('blublog.page.' . $page->slug);
        Session::flash('success', __('blublog.page_edited'));
        Log::add($request, "info", __('blublog.page_edited'));
        return redirect()->route('blublog.pages.index');
    }

    public function destroy($id)
    {
        BlublogUser::check_access('delete', Page::class);
        $page = Page::find($id);
        if (!$page) {
            Session::flash('error', __('blublog.404'));
            Log::add('BlublogPagesController::destroy', "error", __('blublog.404'));
            return redirect()->route('blublog.pages.index');
        }
        $page->delete();

        Session::flash('success', __('blublog.page_deleted'));
        Log::add('BlublogPagesController::destroy', "info", __('blublog.page_deleted'));
        return redirect()->route('blublog.pages.index');
    }
}
