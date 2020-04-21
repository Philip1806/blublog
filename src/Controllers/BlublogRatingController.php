<?php

namespace   Philip\blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip\Blublog\Models\Rate;

class BlublogRatingController extends Controller
{
    public function index()
    {
        // NOT USED YET
        $ratings = Rate::latest()->paginate(10);

        if($ratings){
            foreach( $ratings as $rating){
                $postt = Post::find($rating->rateable_id);
                $rating->postname = $postt['title'];
                $rating->postslug = $postt['slug'];
            }
        }
        return view('blublog::panel.posts.rating')->with('ratings', $ratings);

    }
  /*
    public function duplicate($id)
    {


        return redirect()->back();

    }

    public function destroy($id)
    {
        $rating = Rate::find($id);
        if($rating){
            $rating->delete();
            Session::flash('success', __('general.contentdelete'));
            return redirect()->back();
        }

        Session::flash('error', __('general.content_does_not_found'));
        return redirect()->back();


    }
    */
}
