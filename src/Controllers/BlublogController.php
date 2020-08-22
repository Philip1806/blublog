<?php

namespace   Blublog\Blublog\Controllers;

use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Setting;
use Blublog\Blublog\Models\Log;

class BlublogController extends Controller
{
    //This is panel index
    public function panel()
    {
        $php_errors = Setting::PhpCheck();
        // geting this month
        $thismonth = Post::thismonth();
        // geting last month
        $lastmonth = Post::lastmonth();
        //geting next month
        $nextmonth = Post::nextmonth();
        $totalposts = Post::all()->count();
        $notpubliccomments = Comment::where([
            ['public', '=', false],
        ])->count();

        $private_posts = Post::where([
            ['status', '=', 'private'],
            ['user_id', '=', blublog_get_user(1)],
        ])->latest()->limit(14)->get();
        $draft_posts = Post::where([
            ['status', '=', 'draft'],
            ['user_id', '=', blublog_get_user(1)],
        ])->latest()->limit(14)->get();
        $numbcomments = Comment::all()->count();
        $numbfiles = File::all()->count();

        $this_month_posts = Post::where([
            ['created_at', '>', $thismonth],
            ['created_at', '<', $nextmonth],
        ])->get()->count();
        $last_month_posts = Post::where([
            ['created_at', '>', $lastmonth],
            ['created_at', '<', $thismonth],
        ])->get()->count();

        if (blublog_is_admin()) {
            $this_month_logs = Log::where([
                ['created_at', '>', $thismonth],
                ['created_at', '<', $nextmonth],
            ])->get()->count();
            return view("blublog::panel.index", [
                'this_month_logs' => $this_month_logs,
                'php_errors' => $php_errors,
                'notpubliccomments' => $notpubliccomments,
                'private_posts' => $private_posts,
                'draft_posts' => $draft_posts,
                'totalfiles' => $numbfiles,
                'totalcomments' => $numbcomments,
                'totalposts' => $totalposts,
                'version_info' => Setting::get_blublog_version(),
                'last_month_posts' => $last_month_posts,
                'this_month_posts' => $this_month_posts,
            ]);
        } elseif (blublog_is_mod()) {
            return view("blublog::panel.index_mod")->with('notpubliccomments', $notpubliccomments)->with('private_posts', $private_posts)->with('draft_posts', $draft_posts)->with('totalfiles', $numbfiles)->with('totalcomments', $numbcomments)->with('totalposts', $totalposts)->with('last_month_posts', $last_month_posts)->with('this_month_posts', $this_month_posts);
        }
        //Get posts in this time range
        $this_month_posts = Post::where([
            ['created_at', '>', $thismonth],
            ['created_at', '<', $nextmonth],
            ['user_id', '=', blublog_get_user(1)],
        ])->get()->count();
        $last_month_posts = Post::where([
            ['created_at', '>', $lastmonth],
            ['created_at', '<', $thismonth],
            ['user_id', '=', blublog_get_user(1)],
        ])->get()->count();
        $myposts = Post::where([
            ['user_id', '=', blublog_get_user(1)],
        ])->get()->count();

        return view("blublog::panel.index_author")->with('private_posts', $private_posts)->with('draft_posts', $draft_posts)->with('totalposts', $totalposts)->with('myposts', $myposts)->with('last_month_posts', $last_month_posts)->with('this_month_posts', $this_month_posts);
    }
}
