<?php

namespace   Blublog\Blublog\Controllers;

use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Log;

class BlublogLogController extends Controller
{


    public function index()
    {
        //error, alert, visit, info, bot
        $error_logs = Log::where([
            ['type', '=', 'error'],
        ])->latest()->paginate(15);
        $visit_logs = Log::where([
            ['type', '=', 'visit'],
        ])->latest()->paginate(15);
        $alert_logs = Log::where([
            ['type', '=', 'alert'],
        ])->latest()->paginate(15);
        $info_logs = Log::where([
            ['type', '=', 'info'],
        ])->latest()->paginate(15);
        $bot_logs = Log::where([
            ['type', '=', 'bot'],
        ])->latest()->paginate(15);
        return view('blublog::panel.logs.index')->with('error_logs', $error_logs)->with('visit_logs', $visit_logs)->with('info_logs', $info_logs)->with('bot_logs', $bot_logs)->with('alert_logs', $alert_logs);
    }
    public function show($id)
    {
        $log = Log::find($id);
        return view('blublog::panel.logs.show')->with('log', $log);
    }
}
