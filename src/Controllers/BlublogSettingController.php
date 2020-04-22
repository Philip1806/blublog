<?php

namespace   Philip1503\Blublog\Controllers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Philip1503\Blublog\Models\Setting;
use Philip1503\Blublog\Models\Log;
use App\User;
use Session;

class BlublogSettingController extends Controller
{

    public function index()
    {
        $settings = Setting::latest()->get();
        return view('blublog::panel.settings.index')->with('settings', $settings);
    }
    public function logs()
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
    public function general_settings()
    {
        $settings = Setting::latest()->get();
        return view('blublog::panel.settings.general')->with('settings', $settings);
    }
    public function store(Request $request)
    {
        // no validation...It's only for admins

        $keys = array_keys($request->all());
        $value = array_values($request->all());

        for($i = 1; $i < count($request->all()); $i++){
            $setting = Setting::where([
                ['name', '=', $keys[$i]],
            ])->first();
            if($setting->type == "bool"){
                if($value[$i]){
                    $setting->val = serialize(true);
                }else{
                    $setting->val = serialize(false);
                }
            } else{
                $setting->val = serialize($value[$i]);
            }
            $setting->save();
        }
        return redirect()->back();
    }
}
