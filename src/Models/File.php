<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Blublog\Blublog\Models\Post;
use Session;
use Auth;

class File extends Model
{
    protected $table = 'blublog_files';

    public static function clear_filename($OriginalFilename)
    {
        $OriginalFilename = str_replace(' ', '_', $OriginalFilename);
        $NewFilename = preg_replace('/\s+/', '', $OriginalFilename);
        return $NewFilename;
    }
    public static function remove_directory($dir_and_filename)
    {
        return substr($dir_and_filename, strpos($dir_and_filename, "/") + 1);
    }
    public static function its_uploated_by_user($file, $user_id)
    {
        $Blublog_User = BlublogUser::where([
            ['user_id', '=', $user_id],
        ])->first();
        if(!$Blublog_User){
            return false;
        }
        if($Blublog_User->role == "Administrator" or $Blublog_User->role == "Moderator"){
            return true;
        }
        if($user_id == $file->user_id){
            return true;
        }
        return false;

    }
    public static function get_url($files)
    {
        foreach($files as $file){
            $file->url = Storage::disk(config('blublog.files_disk', 'blublog'))->url( $file->filename);
        }
        return $files;
    }
    public static function get_category_img_file($img)
    {
        $path = 'categories/' . $img;
        $img_file = File::where([
            ['filename', '=', $path],
        ])->first();
        return $img_file;
    }

    public static function img_thumbnail($file, $path)
    {

        $img = Image::make($file);
        $img->fit(blublog_setting('img_height'), blublog_setting('img_width'), function ($constraint) {
            $constraint->upsize();
        })->interlace();
        $img->save(Storage::disk(config('blublog.files_disk', 'blublog'))->getAdapter()->getPathPrefix() . $path, blublog_setting('img_quality'));
        return true;
    }

    public static function img_blurthumbnail($file, $path)
    {

        $img = Image::make($file);
        $img->fit(100, 56, function ($constraint) {
            $constraint->upsize();
        })->blur(1)->interlace();
        $img->save(Storage::disk(config('blublog.files_disk', 'blublog'))->getAdapter()->getPathPrefix() . $path, blublog_setting('img_quality'));
        return true;
    }

    public static function handle_img_upload($request)
    {
        $size = File::get_file_size($request->file);
        $address = Post::next_post_id() . "-" . File::clear_filename($request->file->getClientOriginalName());
        $main_file = Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('posts', $request->file, $address);

        $file = new File;
        $file->size = $size;
        $file->descr =  "'". $request->title . "'". __('blublog.post_image');
        $file->filename = 'posts/' . $address;
        $file->user_id = Auth::user()->id;
        $file->save();

        // thumbnail
        $path = "/posts/thumbnail_". $address;
        $thumbnail_file =File::img_thumbnail($request->file('file'), $path);

        $path = "/posts/blur_thumbnail_". $address;
        $blur_thumbnail_file =File::img_blurthumbnail($request->file('file'), $path);
        Post::check_if_files_uploaded($main_file,$thumbnail_file,$blur_thumbnail_file);
        return $address;
    }
    public static function handle_img_upload_from_category($request)
    {
        $size = File::get_file_size($request->file);
        $numb = rand(99, 9999);
        $address =  $numb . $request->file->getClientOriginalName();
        $file_uploated = Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('categories', $request->file, $address);

        if($file_uploated){
            $file = new File;
            $file->size = $size;
            $file->descr = __('files.image_for_category') . $request->title;
            $file->filename = 'categories/' . $address;
            $file->save();
            return $address;
        } else {
            Log::add($request, "error", __('blublog.error_uploading'));
            return null;
        }

    }

    public static function only_img($files)
    {
        $img_extensions = array("jpg", "jpeg", "jpe", "jif", "jfif", "jfi", "png", "gif",
        "webp", "tiff", "bmp", "dib", "jpx", "Linux", "svg", "svgz", "Linux");

        if($files){
            //Make collection
            $images = collect(new Post);

            //Add only files with extension from the collection
            foreach ($files as $file) {
                $ext = pathinfo($file->filename, PATHINFO_EXTENSION);
                if(in_array($ext, $img_extensions)){
                    $images->push($file);
                }
            }
            return $images;
        } else{
            return $files;
        }

    }

    public static function get_file_size($file)
    {
        //GET THE SIZE OF FILE
        if($file->getClientSize() > 1000000000 ){
            $size = File::convert_bytes($file->getClientSize(), 'G') . " GB";
        } elseif ($file->getClientSize() > 1000000) {
            $size = File::convert_bytes($file->getClientSize(), 'M') . " MB";
        } else {
            $size = File::convert_bytes($file->getClientSize(), 'K') . " KB";
        }
        return $size;
    }

    public static function convert_bytes($bytes, $to, $decimal_places = 1)
    {
            $formulas = array(
                'K' => number_format($bytes / 1024, $decimal_places),
                'M' => number_format($bytes / 1048576, $decimal_places),
                'G' => number_format($bytes / 1073741824, $decimal_places)
            );
            return isset($formulas[$to]) ? $formulas[$to] : 0;

    }
}
