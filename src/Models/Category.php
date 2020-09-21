<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Exceptions\BlublogNotFound;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $table = 'blublog_categories';
    protected $fillable = [
        'category_id', 'post_id',
    ];
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'blublog_posts_categories', 'category_id', 'post_id');
    }
    public static function with_filename($filename)
    {
        return Category::where('img', 'LIKE', '%' . $filename . '%')->first();
    }
    public static function get_img_url($img)
    {
        return Storage::disk(config('blublog.files_disk', 'blublog'))->url('categories/' . $img);;
    }
    public static function create_new($request)
    {
        $category = new Category;
        $category->title = $request->title;
        $category->descr = $request->descr;
        $category->slug =  Post::makeslug($request->title);
        if ($request->rgb) {
            $category->colorcode = $request->rgb . ";";
        } else {
            $category->colorcode = "rgb(" . rand(1, 255) . "," . rand(1, 255) . "," . rand(1, 255) . ");";
        }
        if ($request->file) {
            $category->img = File::handle_img_upload_from_category($request);
        }
        $category->save();
        return true;
    }
    public static function edit_by_id($request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new BlublogNotFound;
        }
        if ($request->file) {
            $old_img = File::get_category_img_file($category->img);
            if ($old_img) {
                if (!Storage::disk(config('blublog.files_disk', 'blublog'))->delete($old_img->filename)) {
                    Log::add($request, "error", __('blublog.error_removing'));
                    Session::flash('error', __('blublog.error_removing'));
                } else {
                    $old_img->delete();
                }
            }
            $address = File::handle_img_upload_from_category($request);
        } else {
            $address = $category->img;
        }

        if ($request->rgb) {
            $category->colorcode = $request->rgb . ";";
        } else {
            $category->colorcode = $request->colorcode;
        }
        $category->title = $request->title;
        $category->descr = $request->descr;
        $category->slug = $request->slug;
        $category->img = $address;
        $category->save();
        return true;
    }
    public static function find_by_id($id)
    {
        $category = Category::find($id);
        if (!$category) {
            throw new BlublogNotFound;
        }
        return $category;
    }
    public static function delete_by_id($id)
    {
        $Category = Category::find_by_id($id);

        $path = 'categories/' . $Category->img;
        $file = File::where([
            ['filename', '=', $path],
        ])->first();
        if ($file) {
            $file->delete();
            if (!Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path)) {
                Log::add($Category, "error", __('blublog.error_removing'));
            }
        }
        $Category->posts()->detach();
        Log::add($Category, "info", __('blublog.contentdelete'));
        $Category->delete();
        return true;
    }
    public static function by_slug($slug)
    {
        $category = Category::where([
            ['slug', '=', $slug],
        ])->first();
        if (!$category) {
            return false;
        }
        $category->get_posts = $category->posts()->where("status", '=', 'publish')->latest()->paginate(blublog_setting('category_posts_per_page'));
        $category->get_posts = Post::processing($category->get_posts);
        return $category;
    }
}
