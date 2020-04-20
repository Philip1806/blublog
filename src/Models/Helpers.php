<?php
use Philip\Blublog\Models\Setting;
use Philip\Blublog\Models\Post;
use Philip\Blublog\Models\BlublogUser;

if (! function_exists('show_route')) {
    function blublog_setting($name)
    {
        $setting = Setting::where([
            ['name', '=', $name],
        ])->first();
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
if (! function_exists('show_route')) {
    function blublog_can_edit_post($post_id, $user_id)
    {
        $post = Post::find($post_id);
        $Blublog_User = BlublogUser::where([
            ['user_id', '=', $user_id],
        ])->first();
        if($Blublog_User->role == "Administrator" or $Blublog_User->role == "Moderator"){
            return true;
        }
        if($user_id == $post->user_id){
            return true;
        }
        return false;

    }
}
if (! function_exists('show_route')) {
    function blublog_is_admin()
    {
        if(auth()->check()){
            $Blublog_User = BlublogUser::where([
                ['user_id', '=', Auth::user()->id],
            ])->first();
            if( $Blublog_User->role == "Administrator"){
                return true;
            }
            return false;
        } else {
            return false;
        }

    }
}
if (! function_exists('show_route')) {
    function blublog_is_mod()
    {
        if(auth()->check()){
            $Blublog_User = BlublogUser::where([
                ['user_id', '=', Auth::user()->id],
            ])->first();
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
