<?php

namespace   Philip1503\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\Post;
use Philip1503\Blublog\Models\File;
use Philip1503\Blublog\Models\Comment;
use Philip1503\Blublog\Models\Tag;

class BlublogAPIController extends Controller
{
    function __construct()
    {

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

    //TAGS POSTS COMMENTS
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
