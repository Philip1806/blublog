<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\PostsViews;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\Rate;
use Blublog\Blublog\Models\Comment;
use Carbon\Carbon;
use Session;
use Auth;

class BlublogPostsController extends Controller
{
    /**
     * Display a listing of the posts.
     *
     * @return Blublog\Blublog\Models\Post
     */
    public function index()
    {
        $posts = Post::where([
            ['status', '=', 'publish'],
        ])->latest()->paginate(14);
        $private_posts = Post::where([
            ['status', '=', 'private'],
            ['user_id', '=', Auth::user()->id],
        ])->latest()->paginate(14);
        $draft_posts = Post::where([
            ['status', '=', 'draft'],
        ])->latest()->paginate(14);

        return view("blublog::panel.posts.index")->with('draft_posts', $draft_posts)->with('posts', $posts)->with('private_posts', $private_posts);
    }

    /**
     * Show the form for creating a new post.
     *
     */
    public function create()
    {
        if(!extension_loaded('gd')){
            Session::flash('error', __('blublog.gd_not_installed'));
            return redirect()->back();
        }
        $tags = Tag::latest()->get();
        $categories = Category::latest()->get();
        $date =  Carbon::now()->format('d/m/Y');
        return view("blublog::panel.posts.create")->with('tags', $tags)->with('date', $date)->with('categories', $categories);
    }


    /**
     * Display the specified post.
     *
     * @param  int  $id
     * @return Blublog\Blublog\Models\Post
    */
    public function show($id)
    {
        $post = Post::getpost($id,Auth::user()->id);

        return view('blublog::panel.posts.show')->with('post', $post);

    }
    public function edit($id)
    {
        $post = Post::getpost($id,Auth::user()->id);
        $date =  Carbon::now()->format('d/m/Y');
        $tags = Tag::all();
        $tags2 = array();
        foreach ($tags as $tag){
            $tags2[$tag->id] = $tag->title;
        }

        $categories = Category::all();
        $categories2 = array();
        foreach ($categories as $category){
            $categories2[$category->id] = $category->title;
        }

        return view("blublog::panel.posts.ed")->with('tags', $tags2)->with('date', $date)->with('post', $post)->with('categories', $categories2);
    }
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:250',
            'categories' => 'required',
            'file' => 'image',
            'content' => 'required',
        ];
        $this->validate($request, $rules);
        if($request->file){
                $size = File::get_file_size($request->file);
                $address = Post::next_post_id() . "-" . File::clear_filename($request->file->getClientOriginalName());
                $main_file = Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('posts', $request->file, $address);

                $file = new File;
                $file->size = $size;
                $file->descr =  "'". $request->title . "'". __('blublog.post_image');
                $file->filename = 'posts/' . $address;
                $file->save();

                // thumbnail
                $path = File::get_img_path("posts", "thumbnail", $request->file->getClientOriginalName(), $un_numb);
                $thumbnail_file =File::img_thumbnail($request->file('file'), $path);

                $path = File::get_img_path("posts", "blur_thumbnail", $request->file->getClientOriginalName(), $un_numb);
                $blur_thumbnail_file =File::img_blurthumbnail($request->file('file'), $path);
                Post::check_if_files_uploaded($main_file,$thumbnail_file,$blur_thumbnail_file);
        } elseif($request->customimg != "") {
            $address = $request->customimg;
        } else {
            $address = "no-img.png";
        }

        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->img = $address;
        $post->title = $request->title;
        if($request->seo_title){
            $post->seo_title = $request->seo_title;
        } else {
            $post->seo_title = substr( $request->title, 0, 157); //to do
        }
        if($request->descr){
            $post->seo_descr = $request->descr;
        } else {
            $post->seo_descr = substr( $request->content, 0, 157); //to do
        }
        $post->headlight = $request->headlight;
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        $post->slug = Post::makeslug($request->title);
        $post->status = $request->status;
        if($request->front){
            $post->front = true;
        } else {
            $post->front = false;
        }
        if($request->main_tag_id){
            $post->tag_id = $request->main_tag_id;
        }
        if($request->recommended){
            $post->recommended = true;
        } else {
            $post->recommended = false;
        }

        if($request->comments){
            $post->comments = true;
        } else {
            $post->comments = false;
        }

        if($request->slider){
            $post->slider = true;
        } else {
            $post->slider = false;
        }
        if($request->created_at){
            $post->created_at = Post::convert_date($request->created_at);
        }
        $post->save();
        $post->tags()->sync($request->tags, false);
        $post->categories()->sync($request->categories, false);

        Session::flash('success', __('blublog.contentcreate'));
        return redirect()->route('blublog.posts.show', $post->id);
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|max:250',
            'descr' => 'max:200',
            'categories' => 'required',
            'content' => 'required',
            'slug' => 'max:200',
        ];
        $this->validate($request, $rules);

        Log::add($request, "info", "Post edited" );

        $post = Post::find($id);
        if($request->file){
            $size = File::get_file_size($request->file);
            //New image uploaded.
            $path = 'posts/' . $post->img;
            $thumbnail = 'posts/thumbnail_' . $post->img;
            $blurthumbnail = 'posts/blur_thumbnail_' . $post->img;
            if($post->img != "no-img.png"){
                //The post had a image before. Delete the old ones.
                $file = File::where([
                    ['filename', '=', $path],
                ])->first();
                if($file){
                    $file->delete();
                }
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path);
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($thumbnail);
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($blurthumbnail);
            }
            $un_numb = Post::next_post_id();

            $address = $un_numb . "-" . File::clear_filename($request->file->getClientOriginalName());
            Storage::disk(config('blublog.files_disk', 'blublog'))->putFileAs('posts', $request->file, $address);

            $file = new File;
            $file->size = $size;
            $file->descr =  "'". $request->title . "'". __('blublog.post_image');
            $file->filename = 'posts/' . $address;
            $file->save();

            // thumbnail
            $path = File::get_img_path("posts", "thumbnail", $request->file->getClientOriginalName(), $un_numb);
            File::img_thumbnail($request->file('file'), $path);

            $path = File::get_img_path("posts", "blur_thumbnail", $request->file->getClientOriginalName(), $un_numb);
            File::img_blurthumbnail($request->file('file'), $path);
        }  elseif($request->customimg != "") {
            $address = $request->customimg;
        }else {
            $address = $post->img;
        }

        $post->title = $request->title;
        if($request->descr){
            $post->seo_descr = $request->descr;
        } else {
            $post->seo_descr = substr( $request->content, 0, 157); //todo
        }
        $post->headlight = $request->headlight;
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        if($request->slug){
            $post->slug = $request->slug;
        }
        $post->status = $request->status;
        if($request->front){
            $post->front = true;
        } else {
            $post->front = false;
        }
        if($request->recommended){
            $post->recommended = true;
        } else {
            $post->recommended = false;
        }
        if($request->comments){
            $post->comments = true;
        } else {
            $post->comments = false;
        }
        if($request->main_tag_id){
            $post->tag_id = $request->main_tag_id;
        }
        if($request->slider){
            $post->slider = true;
        } else {
            $post->slider = false;
        }
        if($request->new_date){
            $post->created_at = Post::convert_date($request->new_date);
        }
        $post->img = $address;
        $post->save();
        Post::remove_cache($post->id);
        if (isset($request->categories)){
            $post->categories()->sync($request->categories);
        } else {
            $post->categories()->sync(array());
        }




        if (isset($request->tags)){
            $post->tags()->sync($request->tags);

           } else {
               $post->tags()->sync(array());
           }

        Session::flash('success', __('blublog.contentupdate'));
        return redirect()->route('blublog.posts.show', $post->id);
    }
    public function destroy($id){

        $post =Post::find($id);
        $views = Rate::where([
        ['post_id', '=', $post->id],
        ])->get();
        foreach($views as $view){
            $view->delete();
        }

        if(!isset($post->id)){
            Session::flash('error', __('general.404'));
            return redirect()->back();

        }

        if(!Post::img_used_by_other_post($id)){
            //Main img
            $path = 'posts/' . $post->img;
            $file = File::where([
                ['filename', '=', $path],
            ])->first();
            if($file){
                $file = File::find($file->id);
                $file->delete();
            }

            //thumbnail

            if($post->img != "no-img.png"){
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path);
                $path2 = 'posts/' . "thumbnail_" . $post->img;
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path2);
                $path3 = 'posts/' . "blur_thumbnail_" . $post->img;
                Storage::disk(config('blublog.files_disk', 'blublog'))->delete($path3);
            }
        }
        $post->categories()->detach();
        $post->tags()->detach();
        $comments = Comment::where([
        ['commentable_id', '=', $post->id],
        ])->get();
        foreach($comments as $comments){
            $comments->delete();
        }
        Post::remove_cache($post->id);
        $post->delete();

        Session::flash('success', __('blublog.contentdelete'));
        return redirect()->route('blublog.posts.index');

    }
}
