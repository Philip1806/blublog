<?php

namespace   Philip1503\Blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\Rate;
use Philip1503\Blublog\Models\Post;
use Session;

class BlublogRatingController extends Controller
{
    public function index()
    {
        // NOT USED YET
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
            Session::flash('success', __('panel.contentdelete'));
            return redirect()->back();
        }

        Session::flash('error', __('panel.404'));
        return redirect()->back();


    }

}
