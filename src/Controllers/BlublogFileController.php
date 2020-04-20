<?php

namespace   Philip\blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip\Blublog\Models\File;

class BlublogFileController extends Controller
{
    /*

        Not used. From Blublog app. Need to be adapted

    public function download($id) // NOT USED FOR NOW
    {
        $slug = preg_replace('/\D/', '', $slug);
        $file = File::where('slug', '=', $slug)->get();

        if(empty($file[0]->id)){
            abort(404);
        } elseif (Auth::check() & !$file[0]->public) {

            return response()->download(storage_path('app/files/' .  $file[0]->filename));
        }  elseif (Auth::check()) {
            return response()->download(public_path('uploads/' .  $file[0]->filename));
        } else {
            abort(403);
        }

    }

    public function create()
    {
        $filesize = ini_get('post_max_size');

        $path = "blublog::" . config('blublog.theme', 'blublog') . ".files.create";
        return view($path)->with('filesize', $filesize);
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
        $address = $numb . $request->file->getClientOriginalName();
        if($request->public){
            Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('files', $request->file, $address);
        } else{
            Storage::disk('local')->putFileAs('files', $request->file, $address);
        }

        $file->slug = $numb;
        $file->size = $size;
        $file->descr = $request->descr;
        if($request->public){
            $file->public = true;
        } else{
            $file->public = false;
        }
        $file->filename = 'files/' . $address;
        $file->save();

        Session::flash('success', __('files.file_added'));
        return redirect()->route('files.index');
    }

    public function index()
    {
        $files = File::latest()->paginate(10);

        return view('blublog::panel.files.index')->with('files', $files);
    }

    public function destroy($id)
    {


        $file = File::find($id);
        if(!$file){
            Session::flash('error', __('general.content_does_not_found'));
            return redirect()->route('files.index');
        }

        $filename = substr($file->filename, strpos($file->filename, "/") + 1);
        $post = Post::where('img', 'LIKE', '%' . $filename . '%')->first();
        if($post and setting('reasonable_control')){
            $massage = 'Заявката за изтриване отказана! Публикация с името "' . $post->title . '" изисква това изображение. Ам за игнориране, изключете опцията "Разумен контрол" ' ;
            Session::flash('warning', $massage);
            return redirect()->route('files.index');
        }

        Storage::disk('public')->delete($file->filename);
        $file->delete();

        Session::flash('success', __('general.contentdelete'));
        return redirect()->route('files.index');
    }
    */
}
