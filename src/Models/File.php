<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class File extends Model
{
    protected $table = 'blublog_images';

    public function user()
    {
        return $this->belongsTo(blublog_user_model());
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function getChildren()
    {
        return File::where('parent_id', '=', $this->id)->get();
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function url()
    {
        return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($this->filename);
    }
    public static function deleteImage($file)
    {
        if (!Gate::allows('blublog_delete_files', $file)) {
            abort(403);
        }
        foreach ($file->getChildren() as $image) {
            Storage::disk(config('blublog.files_disk', 'blublog'))->delete($image->filename);
            $image->delete();
        }
        Storage::disk(config('blublog.files_disk', 'blublog'))->delete($file->filename);
        $file->delete();
    }



    public static function getImageDir()
    {
        return 'photos/' . Carbon::now()->year . '/' . Carbon::now()->month;
    }

    public static function createSizes($filename, $post_id = '')
    {

        $original_file = Storage::disk(config('blublog.files_disk', 'blublog'))->get($filename);

        $info = pathinfo($filename);

        $original = File::addImage($filename, null, $post_id);

        $img_number = 1;


        foreach (config('blublog.image_sizes') as $size) {
            //$size['crop'];
            $newfilename = $info['dirname'] . '/' . $info['filename'] . '_' . $img_number . '.' . $info['extension'];

            try {
                $img = Image::make($original_file);
                if ($size['crop']) {
                    $img->fit($size['w'], $size['h'], function ($constraint) {
                        $constraint->upsize();
                    })->interlace();
                } else {
                    $img->widen($size['w'], function ($constraint) {
                        $constraint->upsize();
                    });
                }
                $img->save(Storage::disk(config('blublog.files_disk', 'blublog'))->getAdapter()->getPathPrefix() . $newfilename, config('blublog.image_quality'));
                File::addImage($newfilename, $original->id);
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
                //TODO:LOG
                break;
            }
            $img_number++;
        }

        return $original->id;
    }
    public static function addImage($filename, $parent_id = '', $post_id = '')
    {

        $file = new File;
        if ($parent_id) {
            $file->parent_id = $parent_id;
        }
        if ($post_id) {
            $file->post_id = $post_id;
        }
        $file->user_id = Auth::user()->id;
        $file->size = File::get_file_size_from_bytes(Storage::disk(config('blublog.files_disk', 'blublog'))->size($filename));;
        $file->filename = $filename;
        $file->save();

        return $file;
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
