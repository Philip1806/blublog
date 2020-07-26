<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Category;

class Category extends Model
{
    protected $table = 'blublog_categories';
    protected $fillable = [
        'category_id','post_id',
    ];
    public function posts() {
        return $this->belongsToMany(Post::class, 'blublog_posts_categories', 'category_id', 'post_id');
    }
    public static function with_filename($filename)
    {
        return Category::where('img', 'LIKE', '%' . $filename . '%')->first();
    }
    public static function by_slug($slug)
    {
        $category = Category::where([
            ['slug', '=', $slug],
        ])->first();
        if(!$category){
            return false;
        }
        $category->get_posts = $category->posts()->where("status",'=','publish')->latest()->paginate(blublog_setting('category_posts_per_page'));
        $category->get_posts = Post::processing($category->get_posts);
        return $category;
    }
}
