<?php

namespace   Philip1503\Blublog\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\Ban;
use Philip1503\Blublog\Models\Log;
use Carbon\Carbon;
use Session;

class BlublogBanController extends Controller
{
    function __construct()
    {

    }
    public function index()
    {
        $bans = Ban::latest()->paginate(15);
        return view('blublog::panel.settings.bans')->with('bans', $bans);
    }
    public function ban(Request $request)
    {
        $rules = [
            'ip' => 'required|max:150',
            'descr' => 'required|max:150',
        ];
        $this->validate($request, $rules);
        Ban::ip($request->ip,$request->descr, $request->comments);
        Session::flash('success', __('panel.contentcreate'));
        return back();
    }
    public function destroy($id)
    {
        $ban = Ban::find($id);
        if(!$ban){
            abort(404);
        }
        $ban->delete();
        Session::flash('success', __('panel.contentdelete'));
        return back();
    }
}