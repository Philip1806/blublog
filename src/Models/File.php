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
    public function post()
    {
        return $this->belongsTo(Post::class, 'filename', 'img');
    }
    public function children()
    {
        return $this->hasMany(File::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
    public function getChildren()
    {
        return self::where('parent_id', '=', $this->id)->get();
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
    public function isParant()
    {
        return ($this->parent_id) ? false : true;
    }

    /**
     * Delete image/file.
     *
     * @return boolean True if successful, false if there was error.
     */

    public function deleteImage()
    {
        if (!Gate::allows('blublog_delete_files', $this)) {
            Log::add($this->id, 'alert', 'User do not have permission to delete this image.');
            abort(403);
        }
        try {
            foreach ($this->children as $image) {
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($image->filename);
                $image->delete();
            }
            Storage::disk(config('blublog.files_disk', 'blublog'))->delete($this->filename);
            Log::add($this->id, 'info', 'Image deleted.');

            $causes = Post::where('file_id', '=', $this->id)->get();
            foreach ($causes as $cause) {
                $cause->file_id = null;
                $cause->save();
            }
            $posts = Post::where('file_id', '=', $this->id)->get();
            foreach ($posts as $post) {
                $post->file_id = null;
                $post->save();
            }
            $this->delete();
            if (!$causes->isEmpty() or !$posts->isEmpty()) {
                return 2;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            Log::add($e->getMessage(), 'error', 'Can not delete image.');
            return false;
        }
    }
    public function usedInPost()
    {
        $result = true;
        $posts = Post::where([
            ['file_id', '=', $this->id],
        ])->get();
        if ($posts->isEmpty()) {
            $result = false;
        }
        if (!$result) {
            $img_in_posts = Post::where([
                ['content', 'LIKE', '%' . $this->filename . '%'],
            ])->orWhere('content', 'LIKE', '%' . $this->getChildren()->first()->filename . '%')->latest()->get();
            if (!$img_in_posts->isEmpty()) {
                $result = true;
            }
        }

        return $result;
    }
    /**
     * Returns image url of the last image size in "image_sizes" array.
     *
     * @return string
     */
    public function thumbnailUrl()
    {
        if (!$this->isParant()) {
            return $this->url();
        }
        if ($this->is_video) {
            return $this->children->last()->url();
        }
        $lastSize = last(config('blublog.image_sizes'));
        return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($this->getFilenameForSize($lastSize['name']));
    }
    public function getFilenameForSize(string $size)
    {
        if ($this->is_video) {
            $videoInfo = pathinfo($this->children->last()->filename);
            $info = pathinfo($this->filename);
            return $videoInfo['dirname'] . '/' . $info['filename'] . '_' . $size . '.' .  $videoInfo['extension'];
        } else {
            $info = pathinfo($this->filename);
            return $info['dirname'] . '/' . $info['filename'] . '_' . $size . '.' . $info['extension'];
        }
    }
    public function imageSizeUrl(string $sizeName)
    {
        if (!$this->isParant()) {
            return $this->url();
        }
        if (File::sizeExist($sizeName)) {
            return  Storage::disk(config('blublog.files_disk', 'blublog'))->url($this->getFilenameForSize($sizeName));
        }
        return $this->url();
    }
    /**
     * Make a Intervention Image Instance
     *
     * @param string $original_file
     */
    public static function makeImage(string $original_file)
    {
        $img = Image::make($original_file);
        if (extension_loaded('exif')) $img->orientate();
        return $img;
    }
    public static function getImage(string $imagePath)
    {
        return Storage::disk(config('blublog.files_disk', 'blublog'))->get($imagePath);
    }
    public static function sizeExist($sizeName)
    {
        foreach (config('blublog.image_sizes') as $size) {
            if ($size['name'] == $sizeName) {
                return true;
            }
        }
        return false;
    }
    /**
     * Give the disk drive image path to the file.
     *
     * @return string
     */
    public static function getImageDir(): string
    {
        $dir = 'photos/' . Carbon::now()->year . '/' . Carbon::now()->month . '/';
        if (!Storage::disk(config('blublog.files_disk', 'blublog'))->exists($dir)) {
            Storage::disk(config('blublog.files_disk', 'blublog'))->makeDirectory($dir, 0777, true, true);
        }
        return $dir;
    }
    public static function getVideoDir(): string
    {
        $dir = 'videos/' . Carbon::now()->year . '/' . Carbon::now()->month . '/';
        if (!Storage::disk(config('blublog.video_disk'))->exists($dir)) {
            Storage::disk(config('blublog.video_disk'))->makeDirectory($dir, 0777, true, true);
        }
        return $dir;
    }

    public static function addImage(string $filename, $parent_id = '', $is_video = false): File
    {
        $file = new File;
        if ($parent_id) {
            $file->parent_id = $parent_id;
        }
        $file->filename = $filename;
        $file->is_video = $is_video;
        $file->user_id = auth()->user()->id;
        $file->save();
        return $file;
    }
    public static function saveVideo(string $videopath, string $imagepath)
    {
        $video = File::addImage($videopath, '', true);
        $original_image = File::getImage($imagepath);
        $info = pathinfo($imagepath);
        $videoInfo = pathinfo($videopath);

        foreach (config('blublog.image_sizes') as $size) {
            $newfilename = $info['dirname'] . '/' . $videoInfo['filename'] . '_' . $size['name'] . '.' . $info['extension'];
            File::createImageSize($original_image, $newfilename, $video, $size);
        }
        Storage::disk(config('blublog.files_disk', 'blublog'))->delete($imagepath);
        return $video->id;
    }
    public static function createSizes(string $filename)
    {
        $original_file = File::getImage($filename);
        $info = pathinfo($filename);
        $parent_image = File::addImage($filename);

        foreach (config('blublog.image_sizes') as $size) {
            $newfilename = $info['dirname'] . '/' . $info['filename'] . '_' . $size['name'] . '.' . $info['extension'];
            File::createImageSize($original_file, $newfilename, $parent_image, $size);
        }

        return $parent_image->id;
    }
    public static function createImageSize(string $original_filename, string $new_filename, File $parent_image, array $size)
    {
        $img = File::makeImage($original_filename);
        if ($size['crop']) {
            $img->fit($size['w'], $size['h'], function ($constraint) {
                $constraint->upsize();
            })->interlace();
        } else {
            if ($size['h'] and !$size['w']) {
                $img->resize(null, $size['h'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $img->resize($size['w'], null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
        }
        $img->save(Storage::disk(config('blublog.files_disk', 'blublog'))->path('/') . $new_filename, $size['quality']);
        File::addImage($new_filename, $parent_image->id);
    }
}
