<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Auth;
use Session;
use Carbon\Carbon;
use Exception;
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
        return $this->belongsTo(File::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(File::class, 'parent_id');
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Returns URL to the file/image.
     *
     * @return string
     */
    public function url(): string
    {
        return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($this->filename);
    }

    /**
     * Delete image/file.
     *
     * @param File $file
     * @return boolean True if successful, false if there was error.
     */
    public static function deleteImage(File $file): bool
    {
        if (!Gate::allows('blublog_delete_files', $file)) {
            Log::add($file->id, 'alert', 'User do not have permission to delete this image.');
            abort(403);
        }
        try {
            foreach ($file->children as $image) {
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($image->filename);
                $image->delete();
            }
            Storage::disk(config('blublog.files_disk', 'blublog'))->delete($file->filename);
            Log::add($file->id, 'info', 'Image deleted.');
            $file->delete();
            return true;
        } catch (Exception $e) {
            Log::add($e->getMessage(), 'error', 'Can not delete image.');
        }
        return false;
    }



    public static function getImageDir(): string
    {
        return 'photos/' . Carbon::now()->year . '/' . Carbon::now()->month;
    }

    public static function createSizes(string $filename, int $post_id = 0): int
    {

        $original_file = Storage::disk(config('blublog.files_disk', 'blublog'))->get($filename);

        $info = pathinfo($filename);

        $original = File::addImage($filename, null, $post_id);

        $img_number = 1;


        foreach (config('blublog.image_sizes') as $size) {
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
                Log::add($e->getMessage(), 'error', 'Could not convert image to sizes.');
                break;
            }
            $img_number++;
        }

        return $original->id;
    }
    public static function addImage(string $filename, $parent_id = '', int $post_id = 0): File
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
        Log::add($file->id, 'info', 'Image added.');
        return $file;
    }
    public static function get_file_size_from_bytes(int $bytes): string
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

    public static function convert_bytes(int $bytes, string $to, int $decimal_places = 1): int
    {
        $formulas = array(
            'K' => number_format($bytes / 1024, $decimal_places),
            'M' => number_format($bytes / 1048576, $decimal_places),
            'G' => number_format($bytes / 1073741824, $decimal_places)
        );
        return isset($formulas[$to]) ? $formulas[$to] : 0;
    }
}
