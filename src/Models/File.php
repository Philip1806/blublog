<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Exceptions\BlublogNoFileDriver;
use Session;
use Auth;
use Blublog\Blublog\Exceptions\BlublogNotFound;
use Exception;
use InvalidArgumentException;

class File extends Model
{
    protected $table = 'blublog_files';

    public function user()
    {
        return $this->belongsTo(BlublogUser::class);
    }
    public static function putFileAs($dir, $file, $address)
    {
        return Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs($dir, $file, $address);
    }
    public static function delete_file($dir)
    {
        return Storage::disk(config('blublog.files_disk', 'blublog'))->delete($dir);
    }
    public static function find_by_id($id)
    {
        $File = File::find($id);
        if (!$File) {
            throw new BlublogNotFound;
        }
        return $File;
    }
    public static function exists($file)
    {
        if ($file->public) {
            $exists = Storage::disk(config('blublog.files_disk', 'blublog'))->exists($file->filename);
        } else {
            $exists = Storage::disk('local')->exists($file->filename);
        }
        if (!$exists) {
            throw new BlublogNotFound;
        }
        return true;
    }
    public static function check_driver()
    {
        try {
            Storage::disk(config('blublog.files_disk', 'blublog'));
        } catch (\InvalidArgumentException $exception) {
            throw new BlublogNoFileDriver();
        }
    }
    public static function remove($file)
    {
        // Delete File
        if ($file->public) {
            $dir = pathinfo($file->filename, PATHINFO_DIRNAME);
            /*  Need to be checked - Fixes bug
                If you create two post with difrend images and change the image of
                one of the post with the image of the other posts,
                then you can delete the image that is not used by any post,
                but it won't delete thumbnail.
            */
            try {
                if ($dir == "posts") {
                    $ext = pathinfo($file->filename, PATHINFO_EXTENSION);
                    $filename = pathinfo($file->filename, PATHINFO_FILENAME);
                    $thumbnail = "thumbnail_" . $filename . "." . $ext;
                    $blur_thumbnail = "blur_" . $thumbnail;
                    Storage::disk(config('blublog.files_disk', 'blublog'))->delete('posts/' . $thumbnail);
                    Storage::disk(config('blublog.files_disk', 'blublog'))->delete('posts/' . $blur_thumbnail);
                }
                $removed = Storage::disk(config('blublog.files_disk', 'blublog'))->delete($file->filename);
            } catch (InvalidArgumentException $exception) {
                Log::add($file, "error", __('blublog.error_removing') . ' exception');
                throw new BlublogNoFileDriver;
            }
        } else {
            $removed = Storage::disk('local')->delete($file->filename);
        }
        return $removed;
    }
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
    public static function get_url($files)
    {
        foreach ($files as $file) {
            $file->url = Storage::disk(config('blublog.files_disk', 'blublog'))->url($file->filename);
        }
        return $files;
    }
    public static function check_dir($dir)
    {
        $save_dir = File::AdapterPathPrefix() . $dir;
        if (!file_exists($save_dir)) {
            mkdir($save_dir, 0777, true);
        }
    }
    public static function AdapterPathPrefix()
    {
        try {
            $prefix = Storage::disk(config('blublog.files_disk', 'blublog'))->getAdapter()->getPathPrefix();
        } catch (\InvalidArgumentException $exception) {
            throw new BlublogNoFileDriver();
        }
        return $prefix;
    }
    public static function create_new($request)
    {
        $size = File::get_file_size($request->file);
        $file = new File;
        $address = rand(0, 9999) .  File::clear_filename($request->file->getClientOriginalName());
        try {
            if ($request->public) {
                $saved = File::putFileAs('files', $request->file, $address);
                $file->public = true;
            } else {
                $saved = File::putFileAs('files', $request->file, $address);
                $file->public = false;
            }
        } catch (InvalidArgumentException $exception) {
            Log::add($request, "error", __('blublog.error_uploading') . ' exception');
            throw new BlublogNoFileDriver;
        }
        if (!$saved) {
            Session::flash('error', __('blublog.error_uploading'));
            Log::add($request, "error", __('blublog.error_uploading'));
            return redirect()->route('blublog.files.index');
        }
        $file->user_id = blublog_get_user(1);
        $file->size = $size;
        $file->descr = $request->descr;
        $file->filename = 'files/' . $address;
        $file->save();

        return true;
    }
    public static function get_url_from_dir($filename)
    {
        $url = Storage::disk(config('blublog.files_disk', 'blublog'))->url($filename);

        return $url;
    }

    public static function only_post_img($files)
    {
        $images = collect(new Post);

        foreach ($files as $file) {
            $filename = File::remove_directory($file->filename);
            $post = Post::with_filename($filename);
            if ($post) {
                $images->push($file);
            }
        }
        return $images;
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
        $img->save(File::AdapterPathPrefix() . $path, blublog_setting('img_quality'));
        return true;
    }
    public static function big_img_upload($file, $path)
    {
        $save_dir = File::AdapterPathPrefix() . $path;
        $img = Image::make($file);
        $img->resize(blublog_setting('big_img_height'), blublog_setting('big_img_width'), function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save($save_dir, blublog_setting('big_img_quality'));
        $img = Image::make($save_dir);

        return $img->filesize();
    }
    public static function img_blurthumbnail($file, $path)
    {
        $img = Image::make($file);
        $img->fit(blublog_setting('blur_img_height'), blublog_setting('blur_img_width'), function ($constraint) {
            $constraint->upsize();
        })->interlace();
        $img->save(File::AdapterPathPrefix() . $path, 30);
        return true;
    }
    public static function random_file_name($OriginalName, $id = null)
    {
        return $id . rand(1, 9999) . rand(9999, 9999999) . '.' . pathinfo($OriginalName, PATHINFO_EXTENSION);
    }
    public static function handle_img_upload($request)
    {
        $size = File::get_file_size($request->file);

        if (blublog_setting('keep_filename')) {
            $address = Post::next_post_id() . rand(0, 9) . "-" . File::clear_filename($request->file->getClientOriginalName());
        } else {
            $address = File::random_file_name($request->file->getClientOriginalName(), Post::next_post_id());
        }

        if (blublog_setting('do_not_convert_post_img')) {
            $main_file = File::putFileAs('posts', $request->file, $address);
        } else {
            $size = File::get_file_size_from_bytes(File::big_img_upload($request->file, 'posts/' . $address));
            $main_file = $size;
        }

        $file = new File;
        $file->size = $size;
        $file->descr =  "'" . $request->title . "'" . __('blublog.post_image');
        $file->filename = 'posts/' . $address;
        $file->user_id = blublog_get_user(1);
        $file->save();

        // thumbnail
        $path = "/posts/thumbnail_" . $address;
        $thumbnail_file = File::img_thumbnail($request->file('file'), $path);

        if (blublog_setting('make_blur_img')) {
            $path = "/posts/blur_thumbnail_" . $address;
            $blur_thumbnail_file = File::img_blurthumbnail($request->file('file'), $path);
        } else {
            $blur_thumbnail_file = true;
        }

        Post::check_if_files_uploaded($main_file, $thumbnail_file, $blur_thumbnail_file);
        return $address;
    }
    public static function upload_img_direct($img, $dir, $filename)
    {
        return File::putFileAs($dir, $img, $filename);
    }
    public static function handle_img_upload_from_category($request)
    {
        $size = File::get_file_size($request->file);
        if (blublog_setting('keep_filename')) {
            $address = Post::next_post_id() . "-" . File::clear_filename($request->file->getClientOriginalName());
        } else {
            $address = File::random_file_name($request->file->getClientOriginalName());
        }
        $file_uploated = File::putFileAs('categories', $request->file, $address);

        if ($file_uploated) {
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
        $img_extensions = array(
            "jpg", "jpeg", "jpe", "jif", "jfif", "jfi", "png", "gif",
            "webp", "tiff", "bmp", "dib", "jpx", "svg", "svgz"
        );

        if ($files) {
            //Make collection
            $images = collect(new Post);

            //Add only files with extension from the collection
            foreach ($files as $file) {
                if ($file->public) {
                    $ext = pathinfo($file->filename, PATHINFO_EXTENSION);
                    if (in_array($ext, $img_extensions)) {
                        $images->push($file);
                    }
                }
            }
            return $images;
        } else {
            return $files;
        }
    }

    public static function get_file_size($file)
    {
        //GET THE SIZE OF FILE
        if ($file->getClientSize() > 1000000000) {
            $size = File::convert_bytes($file->getClientSize(), 'G') . " GB";
        } elseif ($file->getClientSize() > 1000000) {
            $size = File::convert_bytes($file->getClientSize(), 'M') . " MB";
        } else {
            $size = File::convert_bytes($file->getClientSize(), 'K') . " KB";
        }
        return $size;
    }
    public static function get_file_size_from_bytes($bytes)
    {
        if (!$bytes) {
            return false;
        }
        if ($bytes > 1000000000) {
            $size = File::convert_bytes($bytes, 'G') . " GB";
        } elseif ($bytes > 1000000) {
            $size = File::convert_bytes($bytes, 'M') . " MB";
        } else {
            $size = File::convert_bytes($bytes, 'K') . " KB";
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
