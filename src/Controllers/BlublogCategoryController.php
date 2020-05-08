<?php

namespace   Philip1503\Blublog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\Category;
use Philip1503\Blublog\Models\File;
use Philip1503\Blublog\Models\Post;
use Philip1503\Blublog\Models\Log;
use Session;

class BlublogCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        // Getting total views of all post for all categories
        // Useless for now
        foreach($categories as $category){
            $views = 0;
            $numb = 1;
            foreach($category->posts as $post ){

                if( $numb <= $post->views->count()){
                    $numb = $post->views->count();
                    $category->mostviewsid = $post->id;
                    $category->mostviewstitle = $post->title;
                }
                $views = $views + $post->views->count();
            }
            $category->views = $views;
        }

        return view("blublog::panel.categories.index")->with('categories', $categories);
    }
    public function store(Request $request)
    {
            $rules = [
                'title' => 'required|max:255',
            ];
            $this->validate($request, $rules);

            //Make slug from title
            $slug = Post::makeslug($request->title);

        $category = new Category;
        $category->title = $request->title;
        $category->descr = $request->descr;
        $category->slug = $slug;
        if($request->rgb){
            $category->colorcode = $request->rgb . ";";
        }else{
            $category->colorcode = "rgb(". rand(1,255) . "," . rand(1,255) . "," . rand(1,255) . ");";
        }

        if($request->file){
                    $size = File::get_file_size($request->file);
                    $numb = rand(0, 99);
                    $numb2 = rand(99, 99);
                    $numb = $numb . $numb2;
                    $address = $numb . $request->file->getClientOriginalName();
                    Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('categories', $request->file, $address);

                    $file = new File;
                    $file->size = $size;
                    $file->descr = __('files.image_for_category') . $request->title;
                    $file->filename = 'categories/' . $address;
                    $file->save();
                    $category->img = $address;
        }

        $category->save();

        Session::flash('success', __('panel.contentcreate'));
        Log::add($request, "info", __('panel.contentcreate') );
        return redirect()->route('blublog.categories.index');
    }
    public function edit($id)
    {
        $category = Category::find($id);
        if(!$category){
            abort(404);
        }

        return view('blublog::panel.categories.edit')->with('category', $category);

    }
    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|max:255',
            'slug' => 'required|unique:blublog_posts|max:255',
        ];
        $this->validate($request, $rules);


        $category = Category::find($id);

        if($request->file){
            $path = 'categories/' . $category->img;
            $old_img = File::where([
                ['filename', '=', $path],
            ])->first();
            $old_img->delete();
            Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path);
            //GET THE SIZE OF FILE
            $size = File::get_file_size($request->file);
            $numb = rand(99, 9999);
            $address =  $numb . $request->file->getClientOriginalName();
            Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('categories', $request->file, $address);

            $file = new File;
            $file->size = $size;
            $file->descr = __('files.image_for_category') . $request->title;
            $file->filename = 'categories/' . $address;
            $file->save();



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

        Log::add($request, "info", __('panel.contentupdate') );
        Session::flash('success', __('general.contentupdate'));
        return redirect()->back();
    }
    public function destroy($id)
    {



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
            Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path);
        }
        $Category->posts()->detach();
        Log::add($Category, "info", __('panel.contentdelete') );
        $Category->delete();

        Session::flash('success', __('panel.contentdelete'));
        return redirect()->route('blublog.categories.index');
    }
}
