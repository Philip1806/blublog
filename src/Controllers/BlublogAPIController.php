<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\Rate;

class BlublogAPIController extends Controller
{
    function __construct()
    {

    }
    public function set_rating(Request $request)
    {
        if(blublog_setting('no_ratings')){
            return response()->json(false,403);
        }
        $ip = Post::getIp();
        if($request->post and is_numeric($request->star) ){
            $post_id = preg_replace('/\D/', '', $request->post);
            $selected_stars = preg_replace('/\D/', '', $request->star);
            if($selected_stars > 5 or $selected_stars < 1 or !Post::find($post_id) ){
                return response()->json(false,400);
            }
            $have_rating = Rate::where([
                ['post_id', '=', $post_id],
                ['ip', '=', $ip],
            ])->first();
            Post::remove_cache($post_id);
            if($have_rating){
                $have_rating->rating = $selected_stars;
                $have_rating->save();
                return response()->json("Rating changed to " . $selected_stars . " stars.");
            } else {
                $rating = new Rate;
                $rating->post_id = $post_id;
                $rating->rating = $selected_stars;
                $rating->ip = $ip;
                $rating->save();
                return response()->json("You rate this with ". $selected_stars ." stars.");
            }
        }
        return response()->json(false,400);
    }



    /**
     * Used from modal from creating posts.
     *
     */
    public function listimg()
    {
        $files = File::where([
            ['filename', 'LIKE', '%'."posts".'%'],
        ])->latest()->paginate(10);
        $images = File::only_img($files);

        return response()->json($images);

    }

    public function api()
    {
        $codedir = resource_path('views/vendor/blublog/' . blublog_setting('theme') . '/api.php');
        if(file_exists ($codedir)){
            include_once $codedir;
        }

        return response()->json(false);

    }

    public function search(Request $request)
    {
        if($request->type == "post"){
            $posts = Post::where([
                ['title', 'LIKE', '%'.$request->slug.'%'],
            ])->latest()->take(10)->get();
            if($posts->count() > 0){
                return response()->json($posts);
            } else {
                return response()->json(false);
            }
        }

        if($request->type == "file"){
            $files = File::where([
                ['filename', 'LIKE', '%'.$request->slug.'%'],
            ])->latest()->get();
            if($files->count() > 0){
                return response()->json($files);
            } else {
                return response()->json(false);
            }
        }
        if($request->type == "tag"){
            $files = Tag::where([
                ['title', 'LIKE', '%'.$request->slug.'%'],
            ])->latest()->take(10)->get();
            if($files->count() > 0){
                return response()->json($files);
            } else {
                return response()->json(false);
            }
        }
        if($request->type == "comment"){
            $files = Comment::where([
                ['name', 'LIKE', '%'.$request->slug.'%'],
            ])->latest()->get();
            if($files->count() > 0){
                return response()->json($files);
            } else {
                return response()->json(false);
            }
        }
        if($request->type == "comment_ip"){
            $files = Comment::where([
                ['ip', 'LIKE', '%'.$request->slug.'%'],
            ])->latest()->get();
            if($files->count() > 0){
                return response()->json($files);
            } else {
                return response()->json(false);
            }
        }
        return  response()->json(false);

    }
}
