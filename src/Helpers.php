<?php

if (!function_exists('blublog_get_user')) {
    function blublog_get_user($id)
    {
        return blublog_user_model()::findOrFail($id);
    }
}
if (!function_exists('blublog_is_bot')) {
    function blublog_is_bot()
    {
        if (strpos(\Request::header('User-Agent'), "bot") or !\Request::header('accept-language')) {
            return true;
        }
        return false;
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
if (!function_exists('blublog_can_view_status')) {
    function blublog_can_view_status($post_status)
    {
        $pos = array_search($post_status, config('blublog.post_status'));
        if (config('blublog.post_status_access')[$pos] == 3) {
            if (auth()->user()->blublogRoles->first()->havePermission('edit-' . $post_status)) {
                return true;
            }
            return false;
        } elseif (config('blublog.post_status_access')[$pos] == 1) {
            if (blublog_is_mod()) {
                return true;
            } else {
                return false;
            }
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
            return false;
        } else {
            return false;
        }
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
            return false;
        } else {
            return false;
        }
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
        if (!Auth::user()->can($action, $resource)) {
            abort(403);
        }
        return true;
    }
}

if (!function_exists('blublog_create_slug')) {
    function blublog_create_slug($title, $convert = true)
    {
        $cyr = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        ];
        $lat = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
        ];
        $slug = str_replace(" ", "-", $title);
        $slug = preg_replace("/[^A-Za-z0-9\p{Cyrillic}-]/u", "", $slug);
        $slug = $slug . "-" . rand(0, 99);
        $slug = mb_strimwidth($slug, 0, 70, null);
        if ($convert) {
            $slug = str_replace($cyr, $lat, $slug);
        }
        return $slug;
    }
}
