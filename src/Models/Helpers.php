<?php

use Blublog\Blublog\Models\Setting;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\BlublogUser;
use Blublog\Blublog\Models\MenuItem;
use Illuminate\Support\Facades\Cache;
use Blublog\Blublog\Models\Menu;

if (! function_exists('blublog_setting')) {
    function blublog_setting($name)
    {
        if (!Cache::has('blublog.settings.'. $name)){
            $setting = Setting::where([
                ['name', '=', $name],
            ])->first();
            if($setting){
                Cache::put('blublog.settings.'. $name, $setting,  now()->addMinutes(config('blublog.setting_cache')));
            }
        } else {
            $setting = Cache::get('blublog.settings.'. $name);
        }
        if(isset($setting->val)){
            return unserialize($setting->val);

        } else {
            $set = "blublog." . $name;
            $type = $set. "_type";
            $setting = new Setting;
            $setting->name = $name;
            $setting->val = serialize(config($set));
            $setting->type = config($type);

            $setting->save();
            return config($set);
        }
    }
}
if (! function_exists('blublog_main_menu')) {
    function blublog_main_menu()
    {
        return blublog_draw_menu(blublog_setting('main_menu_name'));
    }
}
if (! function_exists('blublog_draw_menu')) {
    function blublog_draw_menu($menu_name)
    {
        $get_menu = Menu::where([
            ['name', '=', $menu_name],
        ])->first();
        //
        if($get_menu){
        $menu = MenuItem::where([
            ['parent', '=', "0"],
            ['menu', '=', $get_menu->id],
        ])->get();
        }
        if($get_menu){
            foreach($menu as $item){
                $sublinks = MenuItem::where([
                    ['parent', '=', $item->id],
                    ['menu', '=', $get_menu->id],
                ])->get();
                if($sublinks){
                    $item->sublinks = $sublinks;
                } else{
                    $item->sublinks = null;
                }

            }

            $HTML = "";

            foreach($menu as $item){
                if($item->sublinks->count() > 1){
                    $HTML = $HTML . '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="' .$item->url. '" id="navbarDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.
                    $item->label. '</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                    foreach($item->sublinks as $link){
                        $HTML = $HTML . '<a class="dropdown-item" href="' . $link->url . '"> '. $link->label . '</a>';
                    }
                    $HTML = $HTML . '</div></li>';
                } else {
                    $HTML = $HTML . '<li class="nav-item">';
                    $HTML = $HTML . '<a class="nav-link" href="' .$item->url . '">' .  $item->label . '</a></li>';
                }

            }
            return $HTML;
        }
        return false;

    }
}
if (! function_exists('blublog_can_edit_post')) {
    function blublog_can_edit_post($post_id, $user_id)
    {
        $post = Post::find($post_id);
        $Blublog_User = BlublogUser::where([
            ['user_id', '=', $user_id],
        ])->first();
        if(!$Blublog_User){
            return false;
        }
        if($Blublog_User->role == "Administrator" or $Blublog_User->role == "Moderator"){
            return true;
        }
        if($user_id == $post->user_id){
            return true;
        }
        return false;

    }
}
if (! function_exists('blublog_is_admin')) {
    function blublog_is_admin()
    {
        if(auth()->check()){
            $Blublog_User = BlublogUser::where([
                ['user_id', '=', Auth::user()->id],
            ])->first();
            if(!$Blublog_User){
                return false;
            }
            if( $Blublog_User->role == "Administrator"){
                return true;
            }
            return false;
        } else {
            return false;
        }

    }
}
if (! function_exists('blublog_is_mod')) {
    function blublog_is_mod()
    {
        if(auth()->check()){
            $Blublog_User = BlublogUser::where([
                ['user_id', '=', Auth::user()->id],
            ])->first();
            if(!$Blublog_User){
                return false;
            }
            if( $Blublog_User->role == "Administrator" or $Blublog_User->role == "Moderator"){
                return true;
            }
            return false;
        } else {
            return false;
        }

    }
}

?>
