<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\BlublogUser;
use Session;

class BlublogFileController extends Controller
{


    public function download($id)
    {
        $file = File::find($id);
        BlublogUser::check_access('download', $file);
        if(!$file){
            abort(404);
        }
        if ($file->public) {
            $dir = Storage::disk(config('blublog.files_disk', 'blublog'))->getDriver()->getAdapter()->getPathPrefix();
            return response()->download($dir.  $file->filename);
        }  else {
            return response()->download(storage_path('app/' .  $file->filename));
        }

    }

    public function index()
    {
        if(Gate::allows('can_delete_all_files')){
            $files = File::latest()->paginate(10);
        } else {
            $files = File::where([
                ['user_id', '=', blublog_get_user(1)],
            ])->latest()->paginate(10);
        }
        foreach($files as $file){
            $file->url = Storage::disk(config('blublog.files_disk', 'blublog'))->url( $file->filename);
            $file->own = File::its_uploated_by_user($file, blublog_get_user(1));
        }
        return view('blublog::panel.files.index')->with('files', $files);
    }

    public function create()
    {
        BlublogUser::check_access('upload', File::class);
        $filesize = ini_get('post_max_size');

        return view('blublog::panel.files.create')->with('filesize', $filesize);
    }

    public function store(Request $request)
    {
        BlublogUser::check_access('upload', File::class);
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
            Session::flash('error', __('blublog.error_uploading'));
            Log::add($request->all(), "error", __('blublog.error_uploading') );
            return redirect()->route('blublog.files.index');
        }
        $file->user_id = blublog_get_user(1);
        $file->size = $size;
        $file->descr = $request->descr;
        $file->filename = 'files/' . $address;
        $file->save();
        Log::add($request, "info", __('blublog.file_added') );
        Session::flash('success', __('blublog.file_added'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $file = File::find($id);
        BlublogUser::check_access('delete', $file);
        if(!$file){
            Session::flash('error', __('panel.content_does_not_found'));
            return redirect()->route('blublog.files.index');
        }
        if(!File::its_uploated_by_user($file, blublog_get_user(1)) and !blublog_is_admin()){
            Session::flash('error', __('blublog.403'));
            Log::add($id, "error", __('blublog.403') );
            return redirect()->back();
        }
        $filename = File::remove_directory($file->filename);
        $post = Post::with_filename($filename);
        $category = Category::with_filename($filename);
        if($post){
            Log::add($id, "alert", __('blublog.delete_post_img') );
            Session::flash('error', __('blublog.delete_post_img'));
            return redirect()->route('blublog.posts.show', $post->id);
        }
        if($category){
            Log::add($id, "alert", __('blublog.delete_category_img') );
            Session::flash('error', __('blublog.delete_category_img'));
            return redirect()->route('blublog.categories.edit', $category->id);
        }

        if($file->public){
            $removed = Storage::disk(config('blublog.files_disk', 'blublog'))->delete($file->filename);
        } else {
            $removed = Storage::disk('local')->delete($file->filename);
        }

        if($removed){
            Log::add($file, "info", __('blublog.contentdelete') );
            $file->delete();
            Session::flash('success', __('blublog.contentdelete'));
            return redirect()->route('blublog.files.index');
        }
        Log::add($id, "error", __('blublog.error_removing') );
        Session::flash('error', __('blublog.error_removing'));
        return redirect()->route('blublog.files.index');
    }

}
