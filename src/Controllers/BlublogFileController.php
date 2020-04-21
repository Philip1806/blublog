<?php

namespace   Philip\blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip\Blublog\Models\File;
use Philip\Blublog\Models\Post;
use Philip\Blublog\Models\Category;
use Session;

class BlublogFileController extends Controller
{


    public function download($id)
    {
        $file = File::find($id);

        if(!$file){
            abort(404);
        }
        if ($file->public) {
            return response()->download(public_path('uploads/' .  $file->filename));
        }  else {
            return response()->download(storage_path('app/' .  $file->filename));
        }

    }

    public function index()
    {
        $files = File::latest()->paginate(10);

        return view('blublog::panel.files.index')->with('files', $files);
    }

    public function create()
    {
        $filesize = ini_get('post_max_size');

        return view('blublog::panel.files.create')->with('filesize', $filesize);
    }

    public function store(Request $request)
    {
        $rules = [
            'descr' => 'required',
            'file' => 'required',
        ];
        $this->validate($request, $rules);
        //GET THE SIZE OF FILE
        $size = File::get_file_size($request->file);

        $file = new File;

        $numb = rand(0, 9999);
        $address = $numb .  File::clear_filename($request->file->getClientOriginalName());
        if($request->public){
            Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('files', $request->file, $address);
            $file->public = true;
        } else{
            Storage::disk('local')->putFileAs('files', $request->file, $address);
            $file->public = false;
        }

        $file->size = $size;
        $file->descr = $request->descr;
        $file->filename = 'files/' . $address;
        $file->save();

        Session::flash('success', __('panel.file_added'));
        return redirect()->route('blublog.files.index');
    }

    public function destroy($id)
    {


        $file = File::find($id);
        if(!$file){
            Session::flash('error', __('general.content_does_not_found'));
            return redirect()->route('blublog.files.index');
        }

        $filename = substr($file->filename, strpos($file->filename, "/") + 1);

        $post = Post::where('img', 'LIKE', '%' . $filename . '%')->first();
        $category = Category::where('img', 'LIKE', '%' . $filename . '%')->first();
        if($post){
            $massage = 'File you try to delete is related to this post. Upload new image for this post to remove the current image.' ;
            Session::flash('error', $massage);
            return redirect()->route('blublog.posts.show', $post->id);
        }
        if($category){
            $massage = 'File you try to delete is related to this category. Upload new image for this category to remove the current image.' ;
            Session::flash('error', $massage);
            return redirect()->route('blublog.categories.edit', $category->id);
        }

        if($file->public){
            $removed = Storage::disk(config('blublog.files_disk', 'blublog'))->delete($file->filename);
        } else {
            $removed = Storage::disk('local')->delete($file->filename);
        }


        if($removed){
            $file->delete();
            Session::flash('success', __('panel.contentdelete'));
            return redirect()->route('blublog.files.index');
        }

        Session::flash('error', "File could not be removed. Check Laravel filesystem cofiguration file.");
        return redirect()->route('blublog.files.index');
    }

}
