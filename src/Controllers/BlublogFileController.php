<?php

namespace   Philip1503\Blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\File;
use Philip1503\Blublog\Models\Post;
use Philip1503\Blublog\Models\Category;
use Philip1503\Blublog\Models\Log;
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

        $address = rand(0, 9999) .  File::clear_filename($request->file->getClientOriginalName());
        if($request->public){
            $saved = Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('files', $request->file, $address);
            $file->public = true;
        } else{
            $saved = Storage::disk('local')->putFileAs('files', $request->file, $address);
            $file->public = false;
        }

        if(!$saved){
            Session::flash('error', __('panel.error_uploading'));
            Log::add($request->all(), "error", __('panel.error_uploading') );
            return redirect()->route('blublog.files.index');
        }

        $file->size = $size;
        $file->descr = $request->descr;
        $file->filename = 'files/' . $address;
        $file->save();
        Log::add($request->all(), "info", __('panel.file_added') );
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

        $filename = File::remove_directory($file->filename);
        $post = Post::with_filename($filename);
        $category = Category::with_filename($filename);
        if($post){
            Log::add($id, "alert", __('panel.delete_post_img') );
            Session::flash('error', __('panel.delete_post_img'));
            return redirect()->route('blublog.posts.show', $post->id);
        }
        if($category){
            Log::add($id, "alert", __('panel.delete_category_img') );
            Session::flash('error', __('panel.delete_category_img'));
            return redirect()->route('blublog.categories.edit', $category->id);
        }

        if($file->public){
            $removed = Storage::disk(config('blublog.files_disk', 'blublog'))->delete($file->filename);
        } else {
            $removed = Storage::disk('local')->delete($file->filename);
        }

        if($removed){
            Log::add($file, "info", __('panel.contentdelete') );
            $file->delete();
            Session::flash('success', __('panel.contentdelete'));
            return redirect()->route('blublog.files.index');
        }
        Log::add($id, "error", __('panel.error_removing') );
        Session::flash('error', __('panel.error_removing'));
        return redirect()->route('blublog.files.index');
    }

}
