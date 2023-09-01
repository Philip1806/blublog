<?php

use Illuminate\Support\Str;

if (!function_exists('blublog_get_user')) {
    function blublog_get_user($id)
    {
        return blublog_user_model()::findOrFail($id);
    }
}
if (!function_exists('blublog_is_bot')) {
    function blublog_is_bot()
    {
        return (strpos(\Request::header('User-Agent'), "bot") or !\Request::header('accept-language')) ? true : false;
    }
}
if (!function_exists('blublog_user_model')) {
    function blublog_user_model($model = true)
    {
        $model = config('blublog.userModel', '\\App\\Models\\User');
        if (!$model) {
            return $model;
        } else {
            $model = new $model;
            return $model;
        }
    }
}
if (!function_exists('blublog_have_permission')) {
    function blublog_have_permission($permission)
    {
        $user = auth()->user();
        foreach ($user->blublogRoles as $role) {
            if ($role->havePermission($permission)) {
                return true;
            }
        }
        return false;
    }
}
if (!function_exists('blublog_can_view_status')) {
    function blublog_can_view_status($post_status)
    {
        $statuses = config('blublog.post_status');
        $access_levels = config('blublog.post_status_access');
        $pos = array_search($post_status, $statuses);

        if ($access_levels[$pos] === 3) {
            $user = auth()->user();
            $first_role = $user->blublogRoles->first();
            return $first_role && $first_role->havePermission('edit-' . $post_status);
        }

        if ($access_levels[$pos] === 1) {
            return blublog_is_mod();
        }

        return true;
    }
}
if (!function_exists('blublog_list_status')) {
    function blublog_list_status()
    {
        $status = array();
        foreach (config('blublog.post_status') as $stat) {
            if (blublog_can_view_status($stat)) {
                array_push($status, $stat);
            }
        }
        return $status;
    }
}
if (!function_exists('blublog_is_admin')) {
    function blublog_is_admin()
    {
        if (auth()->check()) {
            if (Auth::user()->blublogRoles->first()->havePermission('is-admin')) {
                return true;
            }
        }
        return false;
    }
}
if (!function_exists('blublog_is_mod')) {
    function blublog_is_mod()
    {
        if (auth()->check()) {
            $user = Auth::user();
            if ($user->blublogRoles->first()->havePermission('is-admin') or $user->blublogRoles->first()->havePermission('is-mod')) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('blublog_panel_url')) {
    function blublog_panel_url($address, $notfull = false)
    {
        if ($notfull) {
            return ltrim(config('blublog.panel_prefix', '/'))  . $address;
        } else {
            return url(config('blublog.panel_prefix') . $address);
        }
    }
}


if (!function_exists('blublog_check_access')) {
    function blublog_check_access($action, $resource)
    {
        return (!Auth::user()->can($action, $resource)) ? abort(403) : true;
    }
}

if (!function_exists('blublog_get_ip')) {
    /**
     * Get user IP address.
     *  
     * @return string|null
     */
    function blublog_get_ip()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
}

if (!function_exists('blublog_create_slug')) {
    function blublog_create_slug($title)
    {
        return Str::slug($title);
    }
}
