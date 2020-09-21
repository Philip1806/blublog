<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Exceptions\BlublogNotFound;
use Blublog\Blublog\Exceptions\BlublogNoAccess;

use Exception;
use Session;

class BlublogCategoryController extends Controller
{
    public function index()
    {
        BlublogUser::check_access('view', Category::class);
        return view("blublog::panel.categories.index")->with('categories', Category::latest()->get());
    }
    public function store(Request $request)
    {
        BlublogUser::check_access('create', Category::class);
        $rules = [
            'title' => 'required|max:255',
        ];
        $this->validate($request, $rules);
        Category::create_new($request);
        Cache::forget('blublog.categories');
        Session::flash('success', __('blublog.contentcreate'));
        Log::add($request, "info", __('blublog.contentcreate'));
        return redirect()->route('blublog.categories.index');
    }
    public function edit($id)
    {
        try {
            BlublogUser::check_access('update', Category::class);
            $category = Category::find_by_id($id);
        } catch (BlublogNotFound $exception) {
            throw new BlublogNotFound;
        } catch (BlublogNoAccess $exception) {
            throw new BlublogNoAccess;
        }
        return view('blublog::panel.categories.edit')->with('category', $category);
    }
    public function update(Request $request, $id)
    {
        BlublogUser::check_access('update', Category::class);
        $rules = [
            'title' => 'required|max:255',
            'slug' => 'required|unique:blublog_posts|max:255',
        ];
        $this->validate($request, $rules);
        Category::edit_by_id($request, $id);
        Cache::forget('blublog.categories');
        Log::add($request, "info", __('blublog.contentupdate'));
        Session::flash('success', __('blublog.contentupdate'));
        return redirect()->back();
    }
    public function destroy($id)
    {
        BlublogUser::check_access('delete', Category::class);
        Category::delete_by_id($id);
        Session::flash('success', __('blublog.contentdelete'));
        return redirect()->route('blublog.categories.index');
    }
}
