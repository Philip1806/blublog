<?php

namespace Philip1503\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Philip1503\Blublog\Models\Post;
use Session;

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

    public static function get_img_path($dir, $type, $filename, $id = 0)
    {
        if($id == 0){
            $numb = rand(10, 99);
            $numb2 = rand(100, 999);
            $numb = $numb . $numb2;
        } else {
            $numb = $id;
        }

        $ext = pathinfo($filename);
        $filename = File::clear_filename($ext['filename']);


        $address = $numb. "-" . $filename  . "." . $ext['extension'];
        $path = "uploads/". $dir . "/" . $type . "_" . $address;

        return $path;
    }

    public static function img_thumbnail($file, $path)
    {

        $img = Image::make($file);
        $img->fit(blublog_setting('img_height'), blublog_setting('img_width'), function ($constraint) {
            $constraint->upsize();
        })->interlace();
        $img->save(public_path($path), blublog_setting('img_quality'));
        return true;
    }

    public static function img_blurthumbnail($file, $path)
    {

        $img = Image::make($file);
        $img->fit(100, 56, function ($constraint) {
            $constraint->upsize();
        })->blur(1)->interlace();
        $img->save(public_path($path), blublog_setting('img_quality'));

        return true;
    }
    public static function upload_file($dir, $file, $filename, $descr = 'Uploaded file')
    {
        $numb = rand(0, 99);
        $numb2 = rand(99, 9999);
        $numb = $numb . $numb2;
        $address = $numb . $request->file->getClientOriginalName();
        Storage::disk('public')->putFileAs('posts', $request->file, $address);
        $file = new File;
        $file->slug = $numb2;
        $file->size = $size;
        $file->descr =  "'". $post->title . "'". __('panel.post_image');
        $file->filename = 'posts/' . $address;
        $file->save();

    }

    public static function only_img($files)
    {
        $img_extensions = array("jpg", "jpeg", "jpe", "jif", "jfif", "jfi", "png", "gif",
        "webp", "tiff", "bmp", "dib", "jpx", "Linux", "svg", "svgz", "Linux");

        if($files){
            //Make collection
            $images = collect(new Post);

            //Add only files with image extension to the collection
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

    // input $request->file
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
