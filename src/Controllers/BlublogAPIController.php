<?php

namespace   Blublog\Blublog\Controllers;

use Blublog\Blublog\Resources\Post as PostResource;
use Blublog\Blublog\Resources\Category as CategoryResource;
use Blublog\Blublog\Resources\Tag as TagResource;
use Blublog\Blublog\Resources\Comment as CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Category;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Tag;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\Rate;

class BlublogAPIController extends Controller
{
    function __construct()
    {
    }
    public function img_upload(Request $request)
    {
        BlublogUser::check_access('upload', File::class);
        $rules = [
            'file' => 'image',
            'post_id' => 'required',
        ];
        $this->validate($request, $rules);

        if ($request->keep_name == "5") {
            $filename = rand(1, 9999) . File::clear_filename($request->file->getClientOriginalName());
        } else {
            $filename = File::random_file_name($request->file->getClientOriginalName());
        }
        $dir = 'posts/' . $request->post_id . '/';
        $path = $dir .  $filename;

        File::check_dir($dir);

        if ($request->original == "5") {
            File::upload_img_direct($request->file, $dir, $filename);
            $size = File::get_file_size($request->file);
        } else {
            $size = File::get_file_size_from_bytes(File::big_img_upload($request->file, $path));
        }

        $file = new File;
        $file->size = $size;
        $file->descr =  $request->post_id;
        $file->filename = $path;
        $file->user_id = blublog_get_user(1);
        $file->is_in_post = true;
        $file->save();

        $response = array(
            'link' => File::get_url_from_dir($path),
        );

        return response()->json($response);
    }
    public function set_rating(Request $request)
    {
        if (blublog_setting('no_ratings')) {
            return response()->json(false, 403);
        }
        $ip = Post::getIp();
        if ($request->post and is_numeric($request->star)) {
            $post_id = preg_replace('/\D/', '', $request->post);
            $selected_stars = preg_replace('/\D/', '', $request->star);
            if (blublog_setting('use_rating_module_as_likes_and_dislikes')) {
                if ($selected_stars != 5 and  $selected_stars != 1) {
                    return response()->json(false, 400);
                }
            }
            if ($selected_stars > 5 or $selected_stars < 1 or !Post::find($post_id)) {
                return response()->json(false, 400);
            }
            $have_rating = Rate::where([
                ['post_id', '=', $post_id],
                ['ip', '=', $ip],
            ])->first();
            Post::remove_cache($post_id);
            if ($have_rating) {
                $have_rating->rating = $selected_stars;
                $have_rating->save();
                return response()->json(__('blublog.thanks_voting'));
            } else {
                $rating = new Rate;
                $rating->post_id = $post_id;
                $rating->rating = $selected_stars;
                $rating->ip = $ip;
                $rating->save();
                return response()->json(__('blublog.thanks_voting'));
            }
        }
        return response()->json(false, 400);
    }



    /**
     * Used from modal from creating posts.
     *
     */
    public function listimg()
    {
        $files = File::where([
            ['filename', 'LIKE', '%' . "posts" . '%'],
            ['public', '=', true],
            ['is_in_post', '=', false],
        ])->latest()->paginate(10);
        File::get_url($files);
        $images = File::only_img($files);

        return response()->json($images);
    }

    public function api(Request $request)
    {
        $codedir = resource_path('views/vendor/blublog/' . blublog_setting('theme') . '/api.php');
        if (file_exists($codedir)) {
            include_once $codedir;
        }
        return response()->json(null, 204);
    }
    public function post($slug)
    {
        if (!Cache::has('blublog.api.post.' . $slug)) {
            $post = Post::by_slug($slug);
            if (!$post) {
                return response()->json(null, 404);
            }
            if ($post->tag_id) {
                $post->on_this_topic = Tag::get_tag_posts($post->tag_id, $post->id);
            }
            $post = Post::get_numb_of_posts_in_tags($post);
            $post = Post::get_likes_dislikes($post);
            $response = new PostResource($post);
            Cache::put('blublog.api.post.' . $slug, $response);
        } else {
            $response = Cache::get('blublog.api.post.' . $slug);
        }

        return $response;
    }
    public function similar_posts($slug)
    {
        if (!Cache::has('blublog.api.post.' . $slug . '.similar_posts')) {
            $post = Post::by_slug($slug);
            if (!$post) {
                return response()->json(null, 404);
            }
            $posts =  Post::processing(Post::public(Post::similar_posts($post->id)));
            if (!$posts) {
                return response()->json(null, 204);
            }
            $response =  PostResource::collection($posts);
            Cache::put('blublog.api.post.' . $slug . '.similar_posts', $response);
        } else {
            $response = Cache::get('blublog.api.post.' . $slug . '.similar_posts');
        }

        return $response;
    }
    public function comments($slug)
    {
        if (!Cache::has('blublog.api.post.' . $slug . '.comments')) {
            $post = Post::by_slug($slug);
            if (!$post) {
                return response()->json(null, 404);
            }
            $comments = $post->allcomments->where("public", '=', true);
            if (!isset($comments[0]->id)) {
                return response()->json(null, 204);
            }
            $response =  CommentResource::collection($comments);
            Cache::put('blublog.api.post.' . $slug . '.comments', $response);
        } else {
            $response = Cache::get('blublog.api.post.' . $slug . '.comments');
        }

        return $response;
    }

    public function category($slug)
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if (!Cache::has('blublog.api.category.' . $slug . '.page' . $page)) {
            $category = Category::by_slug($slug);
            if (!$category) {
                return response()->json(null, 404);
            }
            $category->get_posts =  PostResource::collection($category->get_posts);
            $response =  new CategoryResource($category);
            Cache::put('blublog.api.category.' . $slug . '.page' . $page, $response);
        } else {
            $response = Cache::get('blublog.api.category.' . $slug . '.page' . $page);
        }
        return $response;
    }
    public function tag($slug)
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if (!Cache::has('blublog.api.tag.' . $slug . '.page' . $page)) {
            $tag = Tag::by_slug($slug);
            if (!$tag) {
                return response()->json(null, 404);
            }
            $tag->get_posts =  PostResource::collection($tag->get_posts);
            $response =  new TagResource($tag);
            Cache::put('blublog.api.tag.' . $slug . '.page' . $page, $response);
        } else {
            $response = Cache::get('blublog.api.tag.' . $slug . '.page' . $page);
        }
        return $response;
    }

    public function search(Request $request)
    {
        if ($request->type == "post") {
            if ($request->search_in != 'content' and $request->search_in != 'title') {
                return response()->json(false);
            }
            $posts = Post::where([
                [$request->search_in, 'LIKE', '%' . $request->slug . '%'],
            ])->latest()->get();
            if ($posts->count() > 0) {
                return response()->json($posts);
            } else {
                return response()->json(false);
            }
        }

        if ($request->type == "file") {
            if ($request->search_in != 'filename' and $request->search_in != 'descr') {
                return response()->json(false);
            }
            if (blublog_is_mod()) {
                $files = File::where([
                    [$request->search_in, 'LIKE', '%' . $request->slug . '%'],
                ])->latest()->get();
            } else {
                $files = File::where([
                    [$request->search_in, 'LIKE', '%' . $request->slug . '%'],
                    ['user_id', '=', blublog_get_user(1)],
                ])->latest()->get();
            }
            if ($files->count() > 0) {
                File::get_url($files);
                return response()->json($files);
            } else {
                return response()->json(false);
            }
        }

        if ($request->type == "post_img") {
            $files = File::where([
                ['filename', 'LIKE', '%' . $request->slug . '%'],
                ['public', '=', true],
                ['is_in_post', '=', false],
            ])->latest()->get();

            if ($files->count() > 0) {
                File::get_url($files);
                return response()->json($files);
            } else {
                return response()->json(false);
            }
        }

        if ($request->type == "tag") {
            $files = Tag::where([
                ['title', 'LIKE', '%' . $request->slug . '%'],
            ])->latest()->take(10)->get();
            if ($files->count() > 0) {
                return response()->json($files);
            } else {
                return response()->json(false);
            }
        }
        if ($request->type == "comment") {
            $comments = Comment::search($request->slug);
            Comment::post_info($comments);
            if ($comments->count() > 0) {
                return response()->json($comments);
            } else {
                return response()->json(false);
            }
        }
        if ($request->type == "comment_ip") {
            if (!blublog_is_mod()) {
                return response()->json(false);
            }
            $comments = Comment::search_by_ip($request->slug);
            Comment::post_info($comments);
            if ($comments->count() > 0) {
                return response()->json($comments);
            } else {
                return response()->json(false);
            }
        }
        return  response()->json(false);
    }
}
