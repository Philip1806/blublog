<?php

namespace   Blublog\Blublog\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blublog\Blublog\Models\Setting;
use Blublog\Blublog\Models\Log;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Role;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
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
        if (!blublog_is_admin()) {
            abort(403);
        }
        if ($setting == 0) {
            Artisan::call('cache:clear');
            Session::flash('success', __('blublog.cache_clear'));
            Log::add($setting, "info", __('blublog.cache_clear'));
            return redirect()->back();
        }
        if ($setting == 1) {
            Artisan::call('blublog:sitemap');
            Session::flash('success', __('blublog.rss_generated'));
            Log::add($setting, "info", __('blublog.rss_generated'));
            return redirect()->back();
        }
        if ($setting == 2) {
            if (!file_exists(storage_path() . '/framework/down')) {
                Artisan::call('down', [
                    '--allow' => Post::getIp(), '--message' => blublog_setting('maintenance_massage')
                ]);
                Log::add($setting, "info", __('blublog.turn_on_maintenance'));
            } else {
                Artisan::call('up');
                Log::add($setting, "info", __('blublog.turn_off_maintenance'));
            }
            return redirect()->back();
        }
        if ($setting == 3) {
            $settings = Setting::get();
            if (blublog_setting('under_attack')) {
                foreach ($settings as $setting) {
                    if ($setting->name == "disable_comments_modul") {
                        $setting->val = serialize(false);
                    }
                    if ($setting->name == "no_ratings") {
                        $setting->val = serialize(false);
                    }
                    if ($setting->name == "disable_search_modul") {
                        $setting->val = serialize(false);
                    }
                    if ($setting->name == "under_attack") {
                        $setting->val = serialize(false);
                    }
                    $setting->save();
                }
            } else {
                foreach ($settings as $setting) {
                    if ($setting->name == "disable_comments_modul") {
                        $setting->val = serialize(true);
                    }
                    if ($setting->name == "no_ratings") {
                        $setting->val = serialize(true);
                    }
                    if ($setting->name == "disable_search_modul") {
                        $setting->val = serialize(true);
                    }
                    if ($setting->name == "under_attack") {
                        $setting->val = serialize(true);
                    }
                    $setting->save();
                }
            }
            Artisan::call('cache:clear');
            return redirect()->back();
        }

        if ($setting == 4) {
            Log::where([
                ['created_at', '<', Carbon::today()->subMonths(3)],
            ])->delete();
        }
        if ($setting == 5) {
            Log::where([
                ['created_at', '<', Carbon::today()->subMonths(6)],
            ])->delete();
        }
        if ($setting == 6) {
            Log::where([
                ['created_at', '<', Carbon::today()->subMonths(3)],
                ['type', '=', 'bot'],
            ])->delete();
        }
        return redirect()->back();
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
    public function roles()
    {
        $posts = array(
            'create_posts', 'update_own_posts', 'delete_own_posts', 'view_stats_own_posts',
            'update_all_posts', 'delete_all_posts', 'view_stats_all_posts', 'posts_wait_for_approve', 'control_post_rating',
        );
        $comments = array(
            'create_comments', 'moderate_comments_from_own_posts', 'moderate_own_comments', 'update_all_comments',
            'delete_all_comments', 'approve_comments_from_own_posts', 'approve_all_comments', 'ban_user_from_commenting',
        );
        $tags_cat_pages = array(
            'create_tags', 'moderate_tags_created_within_set_time', 'update_all_tags', 'delete_all_tags',
            'view_categories', 'create_categories', 'update_categories', 'delete_categories', 'view_pages',
            'create_pages', 'update_pages', 'delete_pages'
        );
        $others = array(
            'create_users', 'update_own_user', 'update_all_users',
            'delete_users', 'change_settings', 'upload_files', 'delete_own_files', 'delete_all_files', 'use_menu',
            'is_mod', 'is_admin'
        );
        $roles = Role::get();
        foreach ($roles as $role) {
            $role->posts = $posts;
            $role->comments = $comments;
            $role->tags_cat_pages = $tags_cat_pages;
            $role->others = $others;
        }
        return view('blublog::panel.settings.roles')->with('roles', $roles);
    }
    public function role(Request $request)
    {
        if ($request->role_id == 'new') {
            Role::add_new($request->all());
            Session::flash('success', __('blublog.contentcreate'));
            return redirect()->back();
        }
        $role = Role::findOrFail($request->role_id);
        if ($role->id == 1) {
            Session::flash('error', __('blublog.role_admin_change'));
            return redirect()->back();
        }
        Role::edit($role, $request->all());
        Session::flash('success', __('blublog.contentupdate'));
        return redirect()->back();
    }
    public function general_settings()
    {
        return view('blublog::panel.settings.general')->with('settings', Setting::latest()->get());
    }
    public function store(Request $request)
    {
        if (!blublog_is_admin()) {
            abort(403);
        }
        // no validation...It's only for admins
        Cache::flush();
        $keys = array_keys($request->all());
        $value = array_values($request->all());

        for ($i = 1; $i < count($request->all()); $i++) {
            $setting = Setting::where([
                ['name', '=', $keys[$i]],
            ])->first();
            if ($setting->type == "bool") {
                if ($value[$i]) {
                    $setting->val = serialize(true);
                } else {
                    $setting->val = serialize(false);
                }
            } else {
                $setting->val = serialize($value[$i]);
            }
            $setting->save();
        }
        return redirect()->back();
    }
}
