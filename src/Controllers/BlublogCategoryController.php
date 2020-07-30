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
use Session;

class BlublogCategoryController extends Controller
{
    public function index()
    {
        BlublogUser::check_access('view', Category::class);
        $categories = Category::latest()->get();
        return view("blublog::panel.categories.index")->with('categories', $categories);
    }
    public function store(Request $request)
    {
        BlublogUser::check_access('create', Category::class);
            $rules = [
                'title' => 'required|max:255',
            ];
            $this->validate($request, $rules);

        $category = new Category;
        $category->title = $request->title;
        $category->descr = $request->descr;
        $category->slug =  Post::makeslug($request->title);
        if($request->rgb){
            $category->colorcode = $request->rgb . ";";
        }else{
            $category->colorcode = "rgb(". rand(1,255) . "," . rand(1,255) . "," . rand(1,255) . ");";
        }
        if($request->file){
            $category->img = File::handle_img_upload_from_category($request);
        }
        $category->save();
        Cache::forget('blublog.categories');
        Session::flash('success', __('blublog.contentcreate'));
        Log::add($request, "info", __('blublog.contentcreate') );
        return redirect()->route('blublog.categories.index');
    }
    public function edit($id)
    {
        BlublogUser::check_access('update', Category::class);
        $category = Category::find($id);
        if(!$category){
            abort(404);
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


        $category = Category::find($id);

        if($request->file){
            $old_img = File::get_category_img_file($category->img);
            if($old_img){
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path);
                $old_img->delete();
            }
            $address = File::handle_img_upload_from_category($request);
        } else {
            $address = $category->img;
        }

        if($request->rgb){
            $category->colorcode = $request->rgb . ";";
        }else{
            $category->colorcode = $request->colorcode;
        }
        $category->title = $request->title;
        $category->descr = $request->descr;
        $category->slug = $request->slug;
        $category->img = $address;
        $category->save();
        Cache::forget('blublog.categories');
        Log::add($request, "info", __('blublog.contentupdate') );
        Session::flash('success', __('blublog.contentupdate'));
        return redirect()->back();
    }
    public function destroy($id)
    {
        BlublogUser::check_access('delete', Category::class);
        $Category = Category::find($id);
        if(!$Category){
            Session::flash('error', __('general.content_does_not_found'));
            return redirect()->route('categories.index');
        }

        $path = 'categories/' . $Category->img;
        $file = File::where([
            ['filename', '=', $path],
        ])->first();
        if($file){
            $file->delete();
            if(!Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path)){
                Log::add($Category, "error", __('blublog.error_removing') );
            }
        }
        $Category->posts()->detach();
        Log::add($Category, "info", __('blublog.contentdelete') );
        $Category->delete();

        Session::flash('success', __('blublog.contentdelete'));
        return redirect()->route('blublog.categories.index');
    }
}
