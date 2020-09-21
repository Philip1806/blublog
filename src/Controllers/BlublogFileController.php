<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Exceptions\BlublogNoAccess;
use Blublog\Blublog\Exceptions\BlublogNoFileDriver;
use Blublog\Blublog\Exceptions\BlublogNotFound;
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
        try {
            $file = File::find_by_id($id);
            BlublogUser::check_access('download', $file);
        } catch (BlublogNoAccess $exception) {
            Log::add($id . "|BlublogFileController::download", "error", __('blublog.BlublogNoAccess'));
            throw new BlublogNoAccess;
        }
        if ($file->public) {
            return response()->download(File::AdapterPathPrefix() .  $file->filename);
        } else {
            return response()->download(storage_path('app/' .  $file->filename));
        }
    }

    public function index()
    {
        if (Gate::allows('can_delete_all_files')) {
            $files = File::latest()->paginate(10);
        } else {
            $files = File::where([
                ['user_id', '=', blublog_get_user(1)],
            ])->latest()->paginate(10);
        }
        try {
            $files = File::get_url($files);
        } catch (\InvalidArgumentException $exception) {
            throw new BlublogNoFileDriver();
        }
        return view('blublog::panel.files.index')->with('files', File::get_url($files));
    }

    public function create()
    {
        BlublogUser::check_access('upload', File::class);
        return view('blublog::panel.files.create')->with('filesize', ini_get('post_max_size'));
    }

    public function store(Request $request)
    {
        BlublogUser::check_access('upload', File::class);
        $rules = [
            'descr' => 'required',
            'file' => 'required',
        ];
        $this->validate($request, $rules);
        File::create_new($request);
        Log::add($request, "info", __('blublog.file_added'));
        Session::flash('success', __('blublog.file_added'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $file = File::find_by_id($id);
            BlublogUser::check_access('delete', $file);
            // Check if the file you try to delete exists
            if (File::exists($file)) {
                // Check if there is a post or category image associated with the file
                $filename = File::remove_directory($file->filename);
                $post = Post::with_filename($filename);
                $category = Category::with_filename($filename);
                if ($post) {
                    Log::add($id, "alert", __('blublog.delete_post_img'));
                    Session::flash('error', __('blublog.delete_post_img'));
                    return redirect()->route('blublog.posts.show', $post->id);
                }
                if ($category) {
                    Log::add($id, "alert", __('blublog.delete_category_img'));
                    Session::flash('error', __('blublog.delete_category_img'));
                    return redirect()->route('blublog.categories.edit', $category->id);
                }
                if (File::remove($file)) {
                    Log::add($file, "info", __('blublog.contentdelete'));
                    $file->delete();
                    Session::flash('success', __('blublog.contentdelete'));
                    return redirect()->route('blublog.files.index');
                }
            }
        } catch (BlublogNoAccess $exception) {
            Log::add($id . "|BlublogFileController::destroy", "error", __('blublog.BlublogNoAccess'));
            throw new BlublogNoAccess;
        } catch (BlublogNotFound $exception) {
            if (isset($file)) {
                Log::add($file, "alert", __('blublog.delete_only_record'));
                $file->delete();
                Session::flash('warning', __('blublog.delete_only_record'));
                return redirect()->route('blublog.files.index');
            } else {
                throw new BlublogNotFound;
            }
        }

        Log::add($id, "error", __('blublog.error_removing'));
        Session::flash('error', __('blublog.error_removing'));
        return redirect()->route('blublog.files.index');
    }
}
