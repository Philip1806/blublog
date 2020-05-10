<?php

namespace   Blublog\Blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Rate;
use Blublog\Blublog\Models\Post;
use Session;

class BlublogRatingController extends Controller
{
    public function index()
    {
        $ratings = Rate::latest()->paginate(10);

        if($ratings){
            foreach( $ratings as $rating){
                $postt = Post::find($rating->post_id);
                $rating->postname = $postt['title'];
            }
        }
        return view('blublog::panel.posts.rating')->with('ratings', $ratings);

    }

    public function duplicate($id)
    {
        $current = Rate::find($id);
        if($current){
            $rating = new Rate;
            $rating->post_id = $current->post_id;
            $rating->rating = $current->rating;
            $rating->ip = $current->ip;
            $rating->save();
        }

        return redirect()->back();

    }

    public function destroy($id)
    {
        $rating = Rate::find($id);
        if($rating){
            $rating->delete();
            Session::flash('success', __('blublog.contentdelete'));
            return redirect()->back();
        }

        Session::flash('error', __('blublog.404'));
        return redirect()->back();


    }

}
