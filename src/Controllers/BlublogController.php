<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Comment;
use Blublog\Blublog\Models\Setting;
use Auth;
use Blublog\Blublog\Models\Log;

class BlublogController extends Controller
{
    function __construct()
    {

    }

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
            ['user_id', '=', Auth::user()->id],
        ])->latest()->paginate(14);
        $draft_posts = Post::where([
            ['status', '=', 'draft'],
            ['user_id', '=', Auth::user()->id],
        ])->latest()->paginate(14);
        $numbcomments = Comment::all()->count();
        $numbfiles = File::all()->count();



        $moduls = array();
        /*if(\Route::has('blublog.panel')){
            array_push($moduls, "Calendar");
        }*/
        $this_month_posts = Post::where([
            ['created_at', '>', $thismonth],
            ['created_at', '<', $nextmonth],
        ])->get()->count();
        $last_month_posts = Post::where([
            ['created_at', '>', $lastmonth],
            ['created_at', '<', $thismonth],
        ])->get()->count();

        if(blublog_is_admin()){
            $this_month_logs = Log::where([
                ['created_at', '>', $thismonth],
                ['created_at', '<', $nextmonth],
            ])->get()->count();
            return view("blublog::panel.index")->with('this_month_logs', $this_month_logs)->with('php_errors', $php_errors)->with('notpubliccomments', $notpubliccomments)->with('private_posts', $private_posts)->with('draft_posts', $draft_posts)->with('totalfiles', $numbfiles)->with('totalcomments', $numbcomments)->with('totalposts', $totalposts)->with('last_month_posts', $last_month_posts)->with('this_month_posts', $this_month_posts)->with('moduls', $moduls);
        }elseif(blublog_is_mod()){
            return view("blublog::panel.index_mod")->with('notpubliccomments', $notpubliccomments)->with('private_posts', $private_posts)->with('draft_posts', $draft_posts)->with('totalfiles', $numbfiles)->with('totalcomments', $numbcomments)->with('totalposts', $totalposts)->with('last_month_posts', $last_month_posts)->with('this_month_posts', $this_month_posts)->with('moduls', $moduls);
        }
        //Get posts in this time range
        $this_month_posts = Post::where([
            ['created_at', '>', $thismonth],
            ['created_at', '<', $nextmonth],
            ['user_id', '=', Auth::user()->id],
        ])->get()->count();
        $last_month_posts = Post::where([
            ['created_at', '>', $lastmonth],
            ['created_at', '<', $thismonth],
            ['user_id', '=', Auth::user()->id],
        ])->get()->count();
        $myposts = Post::where([
            ['user_id', '=', Auth::user()->id],
        ])->get()->count();

        return view("blublog::panel.index_author")->with('private_posts', $private_posts)->with('draft_posts', $draft_posts)->with('moduls', $moduls)->with('totalposts', $totalposts)->with('myposts', $myposts)->with('last_month_posts', $last_month_posts)->with('this_month_posts', $this_month_posts);
    }
}
