<?php

namespace   Blublog\Blublog\Controllers;

use Blublog\Blublog\Models\BlublogUser;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Rate;
use Blublog\Blublog\Models\Post;
use Session;

class BlublogRatingController extends Controller
{
    public function index()
    {
        BlublogUser::check_access('rating', Post::class);
        $ratings = Rate::latest()->paginate(10);
        if ($ratings) {
            foreach ($ratings as $rating) {
                $rating->postname = $rating->post->title;
            }
        }
        return view('blublog::panel.posts.rating')->with('ratings', $ratings);
    }

    public function duplicate($id)
    {
        BlublogUser::check_access('rating', Post::class);
        $original = Rate::findOrFail($id);
        Rate::copy($original);
        return redirect()->back();
    }

    public function destroy($id)
    {
        BlublogUser::check_access('rating', Post::class);
        $rating = Rate::findOrFail($id);
        $rating->delete();
        Session::flash('success', __('blublog.contentdelete'));
        return redirect()->back();
    }
}
