<?php

namespace   Blublog\Blublog\Controllers;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Setting;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\Post;
use Illuminate\Support\Facades\Cache;
use Session;

class BlublogSettingController extends Controller
{

    public function index()
    {
        $settings = Setting::latest()->get();
        return view('blublog::panel.settings.index')->with('settings', $settings);
    }
    public function admin_control($setting)
    {
        if($setting == 0){
            Artisan::call('cache:clear');
            Session::flash('success', __('blublog.cache_clear'));
            Log::add($setting, "info", __('blublog.cache_clear') );
            return redirect()->back();
        }
        if($setting == 1){
            Artisan::call('blublog:sitemap');
            Session::flash('success', __('blublog.rss_generated'));
            Log::add($setting, "info", __('blublog.rss_generated') );
            return redirect()->back();
        }
        if($setting == 2){
            if(!file_exists( storage_path().'/framework/down')){
                Artisan::call('down', [
                    '--allow' => Post::getIp(), '--message' => blublog_setting('maintenance_massage')
                ]);
                Log::add($setting, "info", __('blublog.turn_on_maintenance') );
            } else {
                Artisan::call('up');
                Log::add($setting, "info", __('blublog.turn_off_maintenance') );
            }
            return redirect()->back();
        }
        if($setting == 3){
            $settings = Setting::get();
            if(blublog_setting('under_attack')){
                foreach($settings as $setting){
                    if($setting->name == "disable_comments_modul"){
                        $setting->val = serialize(false);
                    }
                    if($setting->name == "no_ratings"){
                        $setting->val = serialize(false);
                    }
                    if($setting->name == "disable_search_modul"){
                        $setting->val = serialize(false);
                    }
                    if($setting->name == "under_attack"){
                        $setting->val = serialize(false);
                    }
                    $setting->save();
                }
            } else {
                foreach($settings as $setting){
                    if($setting->name == "disable_comments_modul"){
                        $setting->val = serialize(true);
                    }
                    if($setting->name == "no_ratings"){
                        $setting->val = serialize(true);
                    }
                    if($setting->name == "disable_search_modul"){
                        $setting->val = serialize(true);
                    }
                    if($setting->name == "under_attack"){
                        $setting->val = serialize(true);
                    }
                    $setting->save();
                }
            }
            Artisan::call('cache:clear');
            return redirect()->back();
        }
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
        Cache::flush();
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
