<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Services\PostService;
use Blublog\Blublog\Services\TagService;
use Session;


class BlublogBackController extends Controller
{
    protected $postService;
    protected $tagService;

    /**
     * Constructor
     *
     * @param PostService $postservice
     * @param TagService $tagservice
     */
    public function __construct(PostService $postservice, TagService $tagservice)
    {
        $this->postService = $postservice;
        $this->tagService = $tagservice;

        $this->middleware('auth');
    }

    /**
     * Index Page of BLUblog Panel
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        return view("blublog::panel.index", [
            'total_images' => File::all()->count(),
            'total_comments' => Comment::all()->count(),
            'total_posts' => $this->postService->totalPosts(),
            'my_posts' => $this->postService->myPosts(),
            'my_images' => File::where('user_id', '=', auth()->user()->id)->count(),
            'my_posts_last_month' => $this->postService->myLastMonthPosts(),
            'my_posts_this_month' => $this->postService->myThisMonthPosts(),
            'trending_post' => $this->postService->trendingThisWeek(),
            'mostPopularLastMonth' => $this->postService->mostPopularLastMonth(),
            'latest_logs' => Log::latest_important(),
        ]);
    }

    /**
     * Panel Tags Page
     *
     * @return \Illuminate\View\View
     */
    public function tags(): \Illuminate\View\View
    {
        return view('blublog::panel.tags');
    }

    /**
     * Handle Tag Update Request
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tagsUpdate(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $tag = $this->tagService->findById($id);
        $this->authorize('blublog_edit_tags', $tag);
        $this->tagService->update($tag, $request);
        Session::flash('success', "Tag edited.");
        Log::add(json_encode($tag->toArray()), 'info', 'A tag was edited.');
        return back();
    }

    /**
     * Panel Image Page
     *
     * @return \Illuminate\View\View
     */
    public function images(): \Illuminate\View\View
    {
        return view('blublog::panel.images');
    }

    /**
     * Panel Logs Page
     *
     * @return \Illuminate\View\View
     */
    public function logs(): \Illuminate\View\View
    {
        return view('blublog::panel.logs.index');
    }

    /**
     * Generates RSS
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rss(): \Illuminate\Http\RedirectResponse
    {
        Artisan::call('blublog:sitemap');
        Session::flash('success', 'Generated RSS');
        return back();
    }

    /**
     * Panel Page for full informacion about a log
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function logsShow(int $id): \Illuminate\View\View
    {
        $log = Log::findOrFail($id);
        return view('blublog::panel.logs.show')->with('log', $log);
    }
}
